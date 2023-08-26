<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

use Exception;

final class MassExtendRenewalDateRequestBody extends AbstractRequestBody
{
    /**
     * Undeclared; no information provided.
     */
    const EXTEND_REASON_CODE__UNDECLARED = 0;

    /**
     * The renewal-date extension is for customer satisfaction.
     */
    const EXTEND_REASON_CODE__CUSTOMER_SATISFACTION = 1;

    /**
     * The renewal-date extension is for other reasons.
     */
    const EXTEND_REASON_CODE__OTHER_REASONS = 2;

    /**
     * The renewal-date extension is due to a service issue or outage.
     */
    const EXTEND_REASON_CODE__SERVICE_ISSUE_OR_OUTAGE = 3;


    /**
     * The number of days to extend the subscription renewal date.
     * Maximum Value: 90
     */
    protected int $extendByDays;

    /**
     * The reason code for the subscription date extension.
     */
    protected int $extendReasonCode;

    /**
     * The product identifier of the auto-renewable subscription that you’re requesting the renewal-date extension for.
     */
    protected string $productId;

    /**
     * A string that contains a value you provide to uniquely identify this renewal-date extension request.
     * Maximum Length: 128
     */
    protected string $requestIdentifier;

    /**
     * A list of storefront country codes you provide to limit the storefronts that are eligible to receive the
     * subscription-renewal-date extension. Omit this list to request the subscription-renewal-date extension in
     * all storefronts.
     *
     * Use the ISO 3166-1 Alpha-3 country code representation.
     *
     * @var array<string>
     */
    protected array $storefrontCountryCodes = [];

    protected array $requiredFields = ['extendByDays', 'extendReasonCode', 'productId', 'requestIdentifier'];

    /**
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        if ($this->extendByDays < 1 || $this->extendByDays > 90) {
            throw new Exception('"extendByDays" should be numeric value in range from 1 to 90');
        }

        if (strlen($this->requestIdentifier) > 128) {
            throw new Exception('"requestIdentifier" should be string value with length from 1 to 128');
        }
    }
}
