<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;
use Readdle\AppStoreServerAPI\Util\JWT;

use function array_key_exists;
use function json_decode;
use function json_last_error;

final class ResponseBodyV2 implements JsonSerializable
{
    /**
     * A notification type that indicates that the customer initiated a refund request for a consumable in-app purchase,
     * and the App Store is requesting that you provide consumption data.
     */
    const NOTIFICATION_TYPE__CONSUMPTION_REQUEST = 'CONSUMPTION_REQUEST';

    /**
     * A notification type that, along with its subtype, indicates that the user made a change to their subscription
     *  plan. If the subtype is UPGRADE, the user upgraded their subscription, or cross-graded to a subscription with
     *  the same duration. The upgrade goes into effect immediately, starting a new billing period, and the user receives
     *  a prorated refund for the unused portion of the previous period. If the subtype is DOWNGRADE, the user downgraded
     *  their subscription or cross-graded to a subscription with a different duration. Downgrades take effect at the
     *  next renewal date and don’t affect the currently active plan.
     *
     *  If the subtype is empty, the user changed their renewal preference back to the current subscription, effectively
     *  canceling a downgrade.
     */
    const NOTIFICATION_TYPE__DID_CHANGE_RENEWAL_PREF = 'DID_CHANGE_RENEWAL_PREF';

    /**
     * A notification type that, along with its subtype, indicates that the user made a change to the subscription
     * renewal status. If the subtype is AUTO_RENEW_ENABLED, the user re-enabled subscription auto-renewal.
     * If the subtype is AUTO_RENEW_DISABLED, the user disabled subscription auto-renewal, or the App Store disabled
     * subscription auto-renewal after the user requested a refund.
     */
    const NOTIFICATION_TYPE__DID_CHANGE_RENEWAL_STATUS = 'DID_CHANGE_RENEWAL_STATUS';

    /**
     * A notification type that, along with its subtype, indicates that the subscription failed to renew due to a
     * billing issue. The subscription enters the billing retry period. If the subtype is GRACE_PERIOD, continue to
     * provide service through the grace period. If the subtype is empty, the subscription isn't in a grace period,
     * and you can stop providing the subscription service.
     *
     * Inform the user that there may be an issue with their billing information. The App Store continues to retry
     * billing for 60 days, or until the user resolves their billing issue or cancels their subscription, whichever
     * comes first.
     */
    const NOTIFICATION_TYPE__DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';

    /**
     * A notification type that, along with its subtype, indicates that the subscription successfully renewed. If the
     * subtype is BILLING_RECOVERY, the expired subscription that previously failed to renew has successfully renewed.
     * If the subtype is empty, the active subscription has successfully auto-renewed for a new transaction period.
     * Provide the customer with access to the subscription’s content or service.
     */
    const NOTIFICATION_TYPE__DID_RENEW = 'DID_RENEW';

    /**
     * A notification type that, along with its subtype, indicates that a subscription expired. If the subtype is
     * VOLUNTARY, the subscription expired after the user disabled subscription renewal. If the subtype is
     * BILLING_RETRY, the subscription expired because the billing retry period ended without a successful billing
     * transaction. If the subtype is PRICE_INCREASE, the subscription expired because the user didn't consent to a
     * price increase that requires user consent. If the subtype is PRODUCT_NOT_FOR_SALE, the subscription expired
     * because the product wasn't available for purchase at the time the subscription attempted to renew.
     *
     * A notification without a subtype indicates that the subscription expired for some other reason.
     */
    const NOTIFICATION_TYPE__EXPIRED = 'EXPIRED';

    /**
     * A notification type that indicates that the billing grace period has ended without renewing the subscription, so
     * you can turn off access to the service or content. Inform the user that there may be an issue with their billing
     * information. The App Store continues to retry billing for 60 days, or until the user resolves their billing issue
     * or cancels their subscription, whichever comes first.
     */
    const NOTIFICATION_TYPE__GRACE_PERIOD_EXPIRED = 'GRACE_PERIOD_EXPIRED';

    /**
     * A notification type that, along with its subtype, indicates that the user redeemed a promotional offer or offer
     * code.
     *
     * If the subtype is INITIAL_BUY, the user redeemed the offer for a first-time purchase. If the subtype is
     * RESUBSCRIBE, the user redeemed an offer to resubscribe to an inactive subscription. If the subtype is UPGRADE,
     * the user redeemed an offer to upgrade their active subscription, which goes into effect immediately. If the
     * subtype is DOWNGRADE, the user redeemed an offer to downgrade their active subscription, which goes into effect
     * at the next renewal date. If the user redeemed an offer for their active subscription, you receive an
     * OFFER_REDEEMED notification type without a subtype.
     */
    const NOTIFICATION_TYPE__OFFER_REDEEMED = 'OFFER_REDEEMED';

    /**
     * A notification type that, along with its subtype, indicates that the system has informed the user of an
     * auto-renewable subscription price increase.
     *
     * If the price increase requires user consent, the subtype is PENDING if the user hasn't responded to the price
     * increase, or ACCEPTED if the user has consented to the price increase.
     *
     * If the price increase doesn't require user consent, the subtype is ACCEPTED.
     */
    const NOTIFICATION_TYPE__PRICE_INCREASE = 'PRICE_INCREASE';

    /**
     * A notification type that indicates that the App Store successfully refunded a transaction for a consumable in-app
     * purchase, a non-consumable in-app purchase, an auto-renewable subscription, or a non-renewing subscription.
     *
     * The revocationDate contains the timestamp of the refunded transaction. The originalTransactionId and productId
     * identify the original transaction and product. The revocationReason contains the reason.
     *
     * To request a list of all refunded transactions for a user, see `AppStoreServerApi::getRefundHistory()`.
     */
    const NOTIFICATION_TYPE__REFUND = 'REFUND';

    /**
     * A notification type that indicates the App Store declined a refund request initiated by the app developer using
     * any of the following methods: beginRefundRequest(for:in:), beginRefundRequest(in:), beginRefundRequest(for:in:),
     * beginRefundRequest(in:), and refundRequestSheet(for:isPresented:onDismiss:).
     */
    const NOTIFICATION_TYPE__REFUND_DECLINED = 'REFUND_DECLINED';

    /**
     * A notification type that indicates the App Store reversed a previously granted refund due to a dispute
     * that the customer raised. If your app revoked content or services as a result of the related refund,
     * it needs to reinstate them.
     *
     * This notification type can apply to any in-app purchase type: consumable, non-consumable,
     * non-renewing subscription, and auto-renewable subscription. For auto-renewable subscriptions, the renewal date
     * remains unchanged when the App Store reverses a refund.
     */
    const NOTIFICATION_TYPE__REFUND_REVERSED = 'REFUND_REVERSED';

    /**
     * A notification type that indicates the App Store extended the subscription renewal date for a specific
     * subscription. You request subscription-renewal-date extensions by calling Extend a Subscription Renewal Date or
     * Extend Subscription Renewal Dates for All Active Subscribers in the App Store Server API.
     */
    const NOTIFICATION_TYPE__RENEWAL_EXTENDED = 'RENEWAL_EXTENDED';

    /**
     * A notification type that, along with its subtype, indicates that the App Store is attempting to extend the
     * subscription renewal date that you request by calling Extend Subscription Renewal Dates for All Active
     * Subscribers.
     *
     * If the subtype is SUMMARY, the App Store completed extending the renewal date for all eligible subscribers. See
     * `ResponseBodyV2::SUBTYPE_SUMMARY` for details. If the subtype is FAILURE, the renewal date extension didn't
     * succeed for a specific subscription. See the `ResponseBodyV2::getAppMetadata()` data for details.
     */
    const NOTIFICATION_TYPE__RENEWAL_EXTENSION = 'RENEWAL_EXTENSION';

    /**
     * A notification type that indicates that an in-app purchase the user was entitled to through Family Sharing is no
     * longer available through sharing. The App Store sends this notification when a purchaser disabled Family Sharing
     * for a product, the purchaser (or family member) left the family group, or the purchaser asked for and received a
     * refund. Your app also receives a paymentQueue(_:didRevokeEntitlementsForProductIdentifiers:) call. Family Sharing
     * applies to non-consumable in-app purchases and auto-renewable subscriptions.
     */
    const NOTIFICATION_TYPE__REVOKE = 'REVOKE';

    /**
     * A notification type that, along with its subtype, indicates that the user subscribed to a product. If the subtype
     * is INITIAL_BUY, the user either purchased or received access through Family Sharing to the subscription for the
     * first time. If the subtype is RESUBSCRIBE, the user resubscribed or received access through Family Sharing to the
     * same subscription or to another subscription within the same subscription group.
     */
    const NOTIFICATION_TYPE__SUBSCRIBED = 'SUBSCRIBED';

    /**
     * A notification type that the App Store server sends when you request it by calling the Request a Test
     * Notification endpoint. Call that endpoint to test whether your server is receiving notifications. You receive
     * this notification only at your request. For troubleshooting information, see
     * `AppStoreServerAPI::getTestNotificationStatus`.
     */
    const NOTIFICATION_TYPE__TEST = 'TEST';

    /**
     * Applies to the PRICE_INCREASE notificationType. A notification with this subtype indicates that the user accepted
     * the subscription price increase.
     */
    const SUBTYPE__ACCEPTED = 'ACCEPTED';

    /**
     * Applies to the DID_CHANGE_RENEWAL_STATUS notificationType. A notification with this subtype indicates that the
     * user disabled subscription auto-renewal, or the App Store disabled subscription auto-renewal after the user
     * requested a refund.
     */
    const SUBTYPE__AUTO_RENEW_DISABLED = 'AUTO_RENEW_DISABLED';

    /**
     * Applies to the DID_CHANGE_RENEWAL_STATUS notificationType. A notification with this subtype indicates that the
     * user enabled subscription auto-renewal.
     */
    const SUBTYPE__AUTO_RENEW_ENABLED = 'AUTO_RENEW_ENABLED';

    /**
     * Applies to the DID_RENEW notificationType. A notification with this subtype indicates that the expired
     * subscription that previously failed to renew has successfully renewed.
     */
    const SUBTYPE__BILLING_RECOVERY = 'BILLING_RECOVERY';

    /**
     * Applies to the EXPIRED notificationType. A notification with this subtype indicates that the subscription expired
     * because the subscription failed to renew before the billing retry period ended.
     */
    const SUBTYPE__BILLING_RETRY = 'BILLING_RETRY';

    /**
     * Applies to the DID_CHANGE_RENEWAL_PREF notificationType. A notification with this subtype indicates that the user
     * downgraded their subscription or cross-graded to a subscription with a different duration. Downgrades take effect
     * at the next renewal date.
     */
    const SUBTYPE__DOWNGRADE = 'DOWNGRADE';

    /**
     * Applies to the RENEWAL_EXTENSION notificationType. A notification with this subtype indicates that the
     * subscription-renewal-date extension failed for an individual subscription. For details, see
     * `ResponseBodyV2::getAppMetadata()`. For information on the request, see Extend Subscription Renewal Dates for
     * All Active Subscribers.
     */
    const SUBTYPE__FAILURE = 'FAILURE';

    /**
     * Applies to the DID_FAIL_TO_RENEW notificationType. A notification with this subtype indicates that the
     * subscription failed to renew due to a billing issue. Continue to provide access to the subscription during the
     * grace period.
     */
    const SUBTYPE__GRACE_PERIOD = 'GRACE_PERIOD';

    /**
     * Applies to the SUBSCRIBED notificationType. A notification with this subtype indicates that the user purchased
     * the subscription for the first time or that the user received access to the subscription through Family Sharing
     * for the first time.
     */
    const SUBTYPE__INITIAL_BUY = 'INITIAL_BUY';

    /**
     * Applies to the PRICE_INCREASE notificationType. A notification with this subtype indicates that the system
     * informed the user of the subscription price increase, but the user hasn't accepted it.
     */
    const SUBTYPE__PENDING = 'PENDING';

    /**
     * Applies to the EXPIRED notificationType. A notification with this subtype indicates that the subscription expired
     * because the user didn't consent to a price increase.
     */
    const SUBTYPE__PRICE_INCREASE = 'PRICE_INCREASE';

    /**
     * Applies to the EXPIRED notificationType. A notification with this subtype indicates that the subscription expired
     * because the product wasn't available for purchase at the time the subscription attempted to renew.
     */
    const SUBTYPE__PRODUCT_NOT_FOR_SALE = 'PRODUCT_NOT_FOR_SALE';

    /**
     * Applies to the SUBSCRIBED notificationType. A notification with this subtype indicates that the user resubscribed
     * or received access through Family Sharing to the same subscription or to another subscription within the same
     * subscription group.
     */
    const SUBTYPE__RESUBSCRIBE = 'RESUBSCRIBE';

    /**
     * Applies to the RENEWAL_EXTENSION notificationType. A notification with this subtype indicates that the App Store
     * server completed your request to extend the subscription renewal date for all eligible subscribers. For the
     * summary details, see `ResponseBodyV2::getSummary()`. For information on the request, see Extend Subscription
     * Renewal Dates for All Active Subscribers.
     */
    const SUBTYPE__SUMMARY = 'SUMMARY';

    /**
     * Applies to the DID_CHANGE_RENEWAL_PREF notificationType. A notification with this subtype indicates that the user
     * upgraded their subscription or cross-graded to a subscription with the same duration. Upgrades take effect
     * immediately.
     */
    const SUBTYPE__UPGRADE = 'UPGRADE';

    /**
     * Applies to the EXPIRED notificationType. A notification with this subtype indicates that the subscription expired
     * after the user disabled subscription auto-renewal.
     */
    const SUBTYPE__VOLUNTARY = 'VOLUNTARY';

    /**
     * Describes the in-app purchase event that led to this notification.
     */
    private string $notificationType;

    /**
     * Additional information that identifies the notification event.
     * The subtype field is present only for specific version 2 notifications.
     */
    private ?string $subtype = null;

    /**
     * A unique identifier for the notification.
     */
    private string $notificationUUID;

    /**
     * The object that contains the app metadata and signed renewal and transaction information.
     */
    private AppMetadata $appMetadata;

    /**
     * A string that indicates the App Store Server Notification version number.
     */
    private string $version;

    /**
     * The UNIX time, in milliseconds, that the App Store signed the JSON Web Signature data.
     */
    private int $signedDate;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    public static function createFromRawNotification(string $rawNotification, ?string $rootCertificate = null): self
    {
        $notification = json_decode($rawNotification, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($notification)) {
            throw new AppStoreServerNotificationException('Notification is not a valid JSON array');
        }

        if (!array_key_exists('signedPayload', $notification)) {
            throw new AppStoreServerNotificationException('Notification does not contain "signedPayload" property');
        }

        try {
            $payload = JWT::parse($notification['signedPayload'], $rootCertificate);
        } catch (MalformedJWTException $e) {
            throw new AppStoreServerNotificationException('Malformed JWT: ' . $e->getMessage());
        }

        if (!empty($payload['data']['signedRenewalInfo'])) {
            try {
                $rawRenewalInfo = JWT::parse($payload['data']['signedRenewalInfo'], $rootCertificate);
            } catch (MalformedJWTException $e) {
                throw new AppStoreServerNotificationException('Malformed RenewalInfo JWT: ' . $e->getMessage());
            }

            $payload['data']['renewalInfo'] = RenewalInfo::createFromRawRenewalInfo($rawRenewalInfo);
            unset($payload['data']['signedRenewalInfo']);
        }

        if (!empty($payload['data']['signedTransactionInfo'])) {
            try {
                $rawTransactionInfo = JWT::parse($payload['data']['signedTransactionInfo'], $rootCertificate);
            } catch (MalformedJWTException $e) {
                throw new AppStoreServerNotificationException('Malformed TransactionInfo JWT: ' . $e->getMessage());
            }

            $payload['data']['transactionInfo'] = TransactionInfo::createFromRawTransactionInfo($rawTransactionInfo);
            unset($payload['data']['signedTransactionInfo']);
        }

        $responseBodyV2 = new self();
        $responseBodyV2->appMetadata = AppMetadata::createFromRawData($payload['data']);

        $typeCaster = (new ArrayTypeCaseGenerator())($payload, [
            'int' => ['signedDate'],
            'string' => ['notificationType', 'subtype', 'notificationUUID', 'version'],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $responseBodyV2->$prop = $value;
        }

        return $responseBodyV2;
    }

    /**
     * Returns description of the in-app purchase event that led to this notification.
     *
     * @return self::NOTIFICATION_TYPE__*
     */
    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    /**
     * Returns additional information that identifies the notification event.
     * The subtype field is present only for specific version 2 notifications.
     *
     * @return null|self::SUBTYPE__*
     */
    public function getSubtype(): ?string
    {
        return $this->subtype;
    }

    /**
     * Returns a unique identifier for the notification.
     */
    public function getNotificationUUID(): string
    {
        return $this->notificationUUID;
    }

    /**
     * Returns the object that contains the app metadata and signed renewal and transaction information.
     */
    public function getAppMetadata(): AppMetadata
    {
        return $this->appMetadata;
    }

    /**
     * Returns a string that indicates the App Store Server Notification version number.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the App Store signed the JSON Web Signature data.
     */
    public function getSignedDate(): int
    {
        return $this->signedDate;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
