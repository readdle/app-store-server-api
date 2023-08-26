<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

final class ConsumptionRequestBody extends AbstractRequestBody
{
    /**
     * Account age is undeclared.
     */
    const ACCOUNT_TENURE__UNDECLARED = 0;

    /**
     * Account age is between 0–3 days.
     */
    const ACCOUNT_TENURE__3 = 1;

    /**
     * Account age is between 3–10 days.
     */
    const ACCOUNT_TENURE__10 = 2;

    /**
     * Account age is between 10–30 days.
     */
    const ACCOUNT_TENURE__30 = 3;

    /**
     * Account age is between 30–90 days.
     */
    const ACCOUNT_TENURE__90 = 4;

    /**
     * Account age is between 90–180 days.
     */
    const ACCOUNT_TENURE__180 = 5;

    /**
     * Account age is between 180–365 days.
     */
    const ACCOUNT_TENURE__365 = 6;

    /**
     * Account age is over 365 days.
     */
    const ACCOUNT_TENURE__OVER_365 = 7;

    /**
     * The consumption status is undeclared.
     */
    const CONSUMPTION_STATUS__UNDECLARED = 0;

    /**
     * The in-app purchase is not consumed.
     */
    const CONSUMPTION_STATUS__NOT_CONSUMED = 1;

    /**
     * The in-app purchase is partially consumed.
     */
    const CONSUMPTION_STATUS__PARTIALLY_CONSUMED = 2;

    /**
     * The in-app purchase is fully consumed.
     */
    const CONSUMPTION_STATUS__FULLY_CONSUMED = 3;

    /**
     * The app delivered the consumable in-app purchase, and it’s working properly.
     */
    const DELIVERY_STATUS__DELIVERED = 0;

    /**
     * The app didn't deliver the consumable in-app purchase due to a quality issue.
     */
    const DELIVERY_STATUS__QUALITY_ISSUE = 1;

    /**
     * The app delivered the wrong item.
     */
    const DELIVERY_STATUS__WRONG_ITEM = 2;

    /**
     * The app didn't deliver the consumable in-app purchase due to a server outage.
     */
    const DELIVERY_STATUS__SERVER_OUTAGE = 3;

    /**
     * The app didn't deliver the consumable in-app purchase due to an in-game currency change.
     */
    const DELIVERY_STATUS__CURRENCY_CHANGE = 4;

    /**
     * The app didn't deliver the consumable in-app purchase for other reasons.
     */
    const DELIVERY_STATUS__OTHER = 5;

    /**
     * Lifetime purchase amount is undeclared.
     */
    const LIFETIME_DOLLARS_PURCHASED__UNDECLARED = 0;

    /**
     * Lifetime purchase amount is 0 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__0 = 1;

    /**
     * Lifetime purchase amount is between 0.01–49.99 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__50 = 2;

    /**
     * Lifetime purchase amount is between 50–99.99 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__100 = 3;

    /**
     * Lifetime purchase amount is between 100–499.99 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__500 = 4;

    /**
     * Lifetime purchase amount is between 500–999.99 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__1000 = 5;

    /**
     * Lifetime purchase amount is between 1000–1999.99 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__2000 = 6;

    /**
     * Lifetime purchase amount is over 2000 USD.
     */
    const LIFETIME_DOLLARS_PURCHASED__OVER_2000 = 7;

    /**
     * Lifetime refund amount is undeclared.
     */
    const LIFETIME_DOLLARS_REFUNDED__UNDECLARED = 0;

    /**
     * Lifetime refund amount is 0 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__0 = 1;

    /**
     * Lifetime refund amount is between 0.01–49.99 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__50 = 2;

    /**
     * Lifetime refund amount is between 50–99.99 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__100 = 3;

    /**
     * Lifetime refund amount is between 100–499.99 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__500 = 4;

    /**
     * Lifetime refund amount is between 500–999.99 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__1000 = 5;

    /**
     * Lifetime refund amount is between 1000–1999.99 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__2000 = 6;

    /**
     * Lifetime refund amount is over 2000 USD.
     */
    const LIFETIME_DOLLARS_REFUNDED__OVER_2000 = 7;

    /**
     * Undeclared.
     */
    const PLATFORM__UNDECLARED = 0;

    /**
     * An Apple platform.
     */
    const PLATFORM__APPLE = 1;

    /**
     * Non-Apple platform.
     */
    const PLATFORM__NON_APPLE = 2;

    /**
     * The engagement time is undeclared.
     */
    const PLAY_TIME__UNDECLARED = 0;

    /**
     * The engagement time is between 0–5 minutes.
     */
    const PLAY_TIME__5_MINUTES = 1;

    /**
     * The engagement time is between 5–60 minutes.
     */
    const PLAY_TIME__1_HOUR = 2;

    /**
     * The engagement time is between 1–6 hours.
     */
    const PLAY_TIME__6_HOURS = 3;

    /**
     * The engagement time is between 6–24 hours.
     */
    const PLAY_TIME__1_DAY = 4;

    /**
     * The engagement time is between 1–4 days.
     */
    const PLAY_TIME__4_DAYS = 5;

    /**
     * The engagement time is between 4–16 days.
     */
    const PLAY_TIME__16_DAYS = 6;

    /**
     * The engagement time is over 16 days.
     */
    const PLAY_TIME__OVER_16_DAYS = 7;

    /**
     * Account status is undeclared.
     */
    const USER_STATUS__UNDECLARED = 0;

    /**
     * The customer’s account is active.
     */
    const USER_STATUS__ACTIVE = 1;

    /**
     * The customer’s account is suspended.
     */
    const USER_STATUS__SUSPENDED = 2;

    /**
     * The customer’s account is terminated.
     */
    const USER_STATUS__TERMINATED = 3;

    /**
     * The customer’s account has limited access.
     */
    const USER_STATUS__LIMITED_ACCESS = 4;

    /**
     * The age of the customer’s account.
     */
    protected int $accountTenure;

    /**
     * The UUID of the in-app user account that completed the consumable in-app purchase transaction.
     */
    protected string $appAccountToken;

    /**
     * A value that indicates the extent to which the customer consumed the in-app purchase.
     */
    protected int $consumptionStatus;

    /**
     * A Boolean value of true or false that indicates whether the customer consented to provide consumption data.
     */
    protected bool $customerConsented;

    /**
     * A value that indicates whether the app successfully delivered an in-app purchase that works properly.
     */
    protected int $deliveryStatus;

    /**
     * A value that indicates the total amount, in USD, of in-app purchases the customer has made in your app, across all platforms.
     */
    protected int $lifetimeDollarsPurchased;

    /**
     * A value that indicates the total amount, in USD, of refunds the customer has received, in your app, across all platforms.
     */
    protected int $lifetimeDollarsRefunded;

    /**
     * A value that indicates the platform on which the customer consumed the in-app purchase.
     */
    protected int $platform;

    /**
     * A value that indicates the amount of time that the customer used the app.
     */
    protected int $playTime;

    /**
     * A Boolean value of true or false that indicates whether you provided, prior to its purchase, a free sample or trial of the content, or information about its functionality.
     */
    protected bool $sampleContentProvided;

    /**
     * The status of the customer’s account.
     */
    protected int $userStatus;

    protected array $requiredFields = ['*'];
}
