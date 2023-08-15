<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

final class SendAttemptItem implements JsonSerializable
{
    /**
     * The App Store server received a success response when it sent the notification to your server.
     */
    const SEND_ATTEMPT_RESULT__SUCCESS = 'SUCCESS';

    /**
     * The App Store server detected a continual redirect. Check your server's redirects for a circular redirect loop.
     */
    const SEND_ATTEMPT_RESULT__CIRCULAR_REDIRECT = 'CIRCULAR_REDIRECT';

    /**
     * The App Store server received an invalid response from your server.
     */
    const SEND_ATTEMPT_RESULT__INVALID_RESPONSE = 'INVALID_RESPONSE';

    /**
     * The App Store server didn't receive a valid HTTP response from your server.
     */
    const SEND_ATTEMPT_RESULT__NO_RESPONSE = 'NO_RESPONSE';

    /**
     * Another error occurred that prevented your server from receiving the notification.
     */
    const SEND_ATTEMPT_RESULT__OTHER = 'OTHER';

    /**
     * The App Store server's connection to your server was closed while send was in progress.
     */
    const SEND_ATTEMPT_RESULT__PREMATURE_CLOSE = 'PREMATURE_CLOSE';

    /**
     * A network error caused the notification attempt to fail.
     */
    const SEND_ATTEMPT_RESULT__SOCKET_ISSUE = 'SOCKET_ISSUE';

    /**
     * The App Store server didn't get a response from your server and timed out.
     * Check that your server isn't processing messages in line.
     */
    const SEND_ATTEMPT_RESULT__TIMED_OUT = 'TIMED_OUT';

    /**
     * The App Store server couldn't establish a TLS session or validate your certificate.
     * Check that your server has a valid certificate and supports Transport Layer Security (TLS) protocol 1.2 or later.
     */
    const SEND_ATTEMPT_RESULT__TLS_ISSUE = 'TLS_ISSUE';

    /**
     * The App Store server didn't receive an HTTP 200 response from your server.
     */
    const SEND_ATTEMPT_RESULT__UNSUCCESSFUL_HTTP_RESPONSE_CODE = 'UNSUCCESSFUL_HTTP_RESPONSE_CODE';

    /**
     * The App Store server doesn't support the supplied charset.
     */
    const SEND_ATTEMPT_RESULT__UNSUPPORTED_CHARSET = 'UNSUPPORTED_CHARSET';

    /**
     * The date the App Store server attempts to send the notification.
     */
    private int $attemptDate;

    /**
     * The success or error information the App Store server records when
     * it attempts to send an App Store server notification to your server.
     */
    private string $sendAttemptResult;

    private function __construct(int $attemptDate, string $sendAttemptResult)
    {
        $this->attemptDate = $attemptDate;
        $this->sendAttemptResult = $sendAttemptResult;
    }

    /**
     * @param array<string, int|string> $rawSendAttempt
     */
    public static function createFromRawSendAttempt(array $rawSendAttempt): self
    {
        return new self($rawSendAttempt['attemptDate'], $rawSendAttempt['sendAttemptResult']);
    }

    /**
     * Returns the date the App Store server attempts to send the notification.
     */
    public function getAttemptDate(): int
    {
        return $this->attemptDate;
    }

    /**
     * Returns the success or error information the App Store server records when
     * it attempts to send an App Store server notification to your server.
     *
     * @return self::SEND_ATTEMPT_RESULT__*
     */
    public function getSendAttemptResult(): string
    {
        return $this->sendAttemptResult;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
