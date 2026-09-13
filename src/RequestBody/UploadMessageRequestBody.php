<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

final class UploadMessageRequestBody extends AbstractRequestBody
{
    /**
     * Position the header text above the message body. This is the default value.
     */
    const HEADER_POSITION__ABOVE_BODY = 'ABOVE_BODY';

    /**
     * Position the header text above the image.
     */
    const HEADER_POSITION__ABOVE_IMAGE = 'ABOVE_IMAGE';

    /**
     * The header text of the retention message that the system displays to customers.
     * Maximum Length: 66
     */
    protected string $header;

    /**
     * The body text of the retention message that the system displays to customers.
     * Maximum Length: 144
     */
    protected string $body;

    /**
     * The optional image identifier and its alternative text to appear as part of a text-based message with an image.
     *
     * Structure: ['imageIdentifier' => string, 'altText' => string]
     *
     * @var array<string, string>
     */
    protected array $image = [];

    /**
     * An optional array of bullet points.
     *
     * Each item structure: ['imageIdentifier' => string, 'altText' => string, 'text' => string]
     *
     * @var array<array<string, string>>
     */
    protected array $bulletPoints = [];

    /**
     * The position of the header text, which defaults to placing the header text above the body.
     */
    protected string $headerPosition = self::HEADER_POSITION__ABOVE_BODY;

    protected array $requiredFields = ['header', 'body'];
}
