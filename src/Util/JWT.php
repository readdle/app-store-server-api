<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Util;

use Exception;
use Readdle\AppStoreServerAPI\Exception\JWTCreationException;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Key;
use Readdle\AppStoreServerAPI\Payload;

use function array_key_exists;
use function array_map;
use function count;
use function explode;
use function is_null;
use function join;
use function json_decode;
use function json_encode;
use function json_last_error;
use function openssl_sign;
use function openssl_x509_verify;
use function preg_match;
use function set_error_handler;
use function strrpos;
use function substr;
use function trim;

use const OPENSSL_ALGO_SHA256;

final class JWT
{
    const TYPE = 'JWT';

    const ALGORITHM = [
        'name' => 'ES256',
        'opensslAlgorithm' => OPENSSL_ALGO_SHA256,
        'hashAlgorithm' => 'sha256',
    ];

    const HEADER_TYP = 'typ';
    const HEADER_ALG = 'alg';
    const HEADER_KID = 'kid';
    const HEADER_X5C = 'x5c';

    const REQUIRED_JWT_HEADERS = [
        self::HEADER_ALG,
        self::HEADER_X5C,
    ];

    private static bool $unsafeMode = false;

    /**
     * @throws JWTCreationException
     * @throws Exception
     */
    public static function createFrom(Key $key, Payload $payload): string
    {
        $header = [
            self::HEADER_TYP => self::TYPE,
            self::HEADER_ALG => self::ALGORITHM['name'],
            self::HEADER_KID => $key->getKeyId(),
        ];

        $segments[] = Helper::base64Encode(json_encode($header));
        $segments[] = Helper::base64Encode(json_encode($payload->toArray()));

        $error = null;
        $errorHandler = function (int $code, string $message) use (&$error): bool {
            if (preg_match('/^openssl_sign\(\): ([\w\s]+)$/', trim($message), $m)) {
                $error = $m[1];
            }

            return true;
        };

        $previousErrorHandler = set_error_handler($errorHandler);
        $signatureAsASN1 = '';
        $isSigned = openssl_sign(
            join('.', $segments),
            $signatureAsASN1,
            $key->getKey(),
            self::ALGORITHM['opensslAlgorithm']
        );
        set_error_handler($previousErrorHandler);

        if (!$isSigned) {
            throw new JWTCreationException('Message could not be signed', new Exception($error));
        }

        try {
            $signature = ASN1SequenceOfInteger::toHex($signatureAsASN1);
        } catch (Exception $e) {
            throw new JWTCreationException('Signature could not be encoded', $e);
        }

        $segments[] = Helper::base64Encode(hex2bin($signature));
        return join('.', $segments);
    }

    /**
     * WARNING!
     * THIS METHOD TURNS OFF VALIDATION AT ALL!
     * USE IT FOR TESTING PURPOSES ONLY!
     *
     * Turns unsafe mode on/off.
     * Returns previous state of unsafe mode.
     *
     * NOTE: Unsafe mode means that payloads will be parsed without any validation.
     *
     * @noinspection PhpUnused
     */
    public static function unsafeMode(bool $state = true): bool
    {
        $previousState = self::$unsafeMode;
        self::$unsafeMode = $state;
        return $previousState;
    }

    /**
     * Parses signed payload, checks its headers, certificates chain, signature.
     * Returns decoded payload.
     * Throws an exception if payload is malformed or verification failed.
     *
     * @return array<string, mixed>
     *
     * @throws MalformedJWTException
     */
    public static function parse(string $jwt, ?string $rootCertificate = null): array
    {
        $parts = explode('.', $jwt);
        $partsCount = count($parts);

        if ($partsCount !== 3) {
            throw new MalformedJWTException('Payload should contain 3 parts, ' . $partsCount . ' were found');
        }

        [$headersJson, $payloadJson, $signature] = array_map([Helper::class, 'base64Decode'], $parts);

        if (!$headersJson || !$payloadJson || !$signature) {
            throw new MalformedJWTException('JWT could not be decoded');
        }

        $headers = json_decode($headersJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MalformedJWTException('Headers JSON could not be decoded');
        }

        $payload = json_decode($payloadJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MalformedJWTException('Payload JSON could not be decoded');
        }

        if (self::$unsafeMode) {
            return $payload;
        }

        $missingHeaders = [];

        foreach (self::REQUIRED_JWT_HEADERS as $headerName) {
            if (!array_key_exists($headerName, $headers)) {
                $missingHeaders[] = $headerName;
            }
        }

        if ($missingHeaders) {
            throw new MalformedJWTException('Required headers are missing: [' . join(', ', $missingHeaders) . ']');
        }

        if ($headers[self::HEADER_ALG] !== self::ALGORITHM['name']) {
            throw new MalformedJWTException('Unrecognized algorithm: ' . $headers[self::HEADER_ALG]);
        }

        try {
            self::verifyX509Chain($headers[self::HEADER_X5C], $rootCertificate);
        } catch (Exception $e) {
            throw new MalformedJWTException('Certificate chain could not be verified: ' . $e->getMessage());
        }

        $signedPart = substr($jwt, 0, strrpos($jwt, '.'));

        try {
            self::verifySignature($headers, $signedPart, $signature);
        } catch (Exception $e) {
            throw new MalformedJWTException('Signature verification failed: ' . $e->getMessage());
        }

        return $payload;
    }

    /**
     * @param array<int, string> $chain
     *
     * @throws Exception
     */
    private static function verifyX509Chain(array $chain, ?string $rootCertificate = null): void
    {
        [$certificate, $intermediate, $root] = array_map([Helper::class, 'formatPEM'], $chain);

        if (openssl_x509_verify($certificate, $intermediate) !== 1) {
            throw new Exception('Certificate verification failed');
        }

        if (openssl_x509_verify($intermediate, $root) !== 1) {
            throw new Exception('Intermediate certificate verification failed');
        }

        if (
            !is_null($rootCertificate)
            && openssl_x509_verify($root, Helper::formatPEM($rootCertificate)) !== 1
        ) {
            throw new Exception('Root certificate verification failed');
        }
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws Exception
     */
    private static function verifySignature(array $headers, string $input, string $signature): void
    {
        $signatureAsASN1 = ASN1SequenceOfInteger::fromHex(bin2hex($signature));
        $publicKey = Helper::formatPEM($headers[self::HEADER_X5C][0]);

        if (openssl_verify($input, $signatureAsASN1, $publicKey, self::ALGORITHM['hashAlgorithm']) !== 1) {
            throw new Exception('Wrong signature');
        }
    }
}
