<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Exception;
use Readdle\AppStoreServerAPI\Exception\InvalidArgumentException;
use Readdle\AppStoreServerAPI\Exception\JWTCreationException;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\ECSignature;
use Readdle\AppStoreServerAPI\Util\Helper;
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
    private const ALGORITHM = [
        'name' => 'ES256',
        'opensslAlgorithm' => OPENSSL_ALGO_SHA256,
        'hashAlgorithm' => 'sha256',
        'signaturePartLength' => 64,
    ];

    private const HEADER_TYP = 'typ';
    private const HEADER_ALG = 'alg';
    private const HEADER_KID = 'kid';
    private const HEADER_X5C = 'x5c';

    const REQUIRED_JWS_HEADERS = [
        self::HEADER_ALG,
        self::HEADER_X5C,
    ];

    /**
     * @throws JWTCreationException
     */
    public static function createFrom(Key $key, Payload $payload): string
    {
        $header = [
            self::HEADER_TYP => 'JWT',
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
        $signature = ''; // will be filled by the next call
        $signed = openssl_sign(join('.', $segments), $signature, $key->getPrivateKey(), self::ALGORITHM['opensslAlgorithm']);
        set_error_handler($previousErrorHandler);

        if (!$signed) {
            throw new JWTCreationException('Message could not be signed: ' . $error);
        }

        try {
            $segments[] = Helper::base64Encode(ECSignature::fromAsn1($signature, self::ALGORITHM['signaturePartLength']));
        } catch (InvalidArgumentException $e) {
            throw new JWTCreationException('Signature could not be encoded: ' . $e->getMessage());
        }

        return join('.', $segments);
    }

    /**
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

        $missingHeaders = [];

        foreach (self::REQUIRED_JWS_HEADERS as $headerName) {
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
     * @throws Exception
     */
    private static function verifyX509Chain(array $chain, ?string $rootCertificate = null): void
    {
        [$certificate, $intermediate, $root] = array_map([Helper::class, 'checkAndFormatPEM'], $chain);

        if (openssl_x509_verify($certificate, $intermediate) !== 1) {
            throw new Exception('Certificate verification failed');
        }

        if (openssl_x509_verify($intermediate, $root) !== 1) {
            throw new Exception('Intermediate certificate verification failed');
        }

        if (
            !is_null($rootCertificate)
            && openssl_x509_verify($root, Helper::checkAndFormatPEM($rootCertificate)) !== 1
        ) {
            throw new Exception('Root certificate verification failed');
        }
    }

    /**
     * @throws Exception
     */
    private static function verifySignature(array $headers, string $input, string $signature): void
    {
        try {
            $der = ECSignature::toAsn1($signature, self::ALGORITHM['signaturePartLength']);
        } catch (InvalidArgumentException $e) {
            throw new Exception('Signature conversion error: ' . $e->getMessage());
        }

        $pem = Helper::checkAndFormatPEM($headers[self::HEADER_X5C][0]);

        if (openssl_verify($input, $der, $pem, self::ALGORITHM['hashAlgorithm']) !== 1) {
            throw new Exception('Wrong signature');
        }
    }
}
