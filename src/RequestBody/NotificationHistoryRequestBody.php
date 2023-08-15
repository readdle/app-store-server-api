<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

use Readdle\AppStoreServerAPI\ResponseBodyV2;

final class NotificationHistoryRequestBody extends AbstractRequestBody
{
    /**
     * The start date of the timespan for the requested App Store Server Notification history records.
     * The startDate needs to precede the endDate.
     * Choose a startDate that's within the past 180 days from the current date.
     */
    protected int $startDate;

    /**
     * The end date of the timespan for the requested App Store Server Notification history records.
     * Choose an endDate that's later than the startDate.
     * If you choose an endDate in the future, the endpoint automatically uses the current date as the endDate.
     */
    protected int $endDate;

    /**
     * A notification type.
     * Provide this field to limit the notification history records to those with this one notification type.
     *
     * NOTE: Include either the transactionId or the notificationType in your query, but not both.
     *
     * @see ResponseBodyV2::NOTIFICATION_TYPE__*
     */
    protected ?string $notificationType = null;

    /**
     * A notification subtype.
     * Provide this field to limit the notification history records to those with this one notification subtype.
     * If you specify a notificationSubtype, you need to also specify its related notificationType.
     *
     * @see ResponseBodyV2::SUBTYPE__*
     */
    protected ?string $notificationSubtype = null;

    /**
     * A Boolean value you set to true to request only the notifications that haven’t reached your server successfully.
     * The response also includes notifications that the App Store server is currently retrying to send to your server.
     */
    protected bool $onlyFailures = false;

    /**
     * The transaction identifier, which may be an original transaction identifier,
     * of any transaction belonging to the customer.
     * Provide this field to limit the notification history request to this one customer.
     *
     * NOTE: Include either the transactionId or the notificationType in your query, but not both.
     */
    protected ?string $transactionId = null;

    public function __construct(array $params = [])
    {
        if (empty($params['startDate'])) {
            $params['startDate'] = (time() - 60 * 60 * 24 * 180) * 1000; // - half a year by default
        }

        if (empty($params['endDate'])) {
            $params['endDate'] = time() * 1000;
        }

        parent::__construct($params);
    }
}
