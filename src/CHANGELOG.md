### [3.0.0] 2023-08-18

***BREAKING CHANGES:***

- Main classes renamed:
  - `APIClient` -> `AppStoreServerAPI`
  - `APIClientInterface` -> `AppStoreServerAPIInterface`
  - `Notification\ResponseBodyV2` -> `ResponseBodyV2`
  - `JWT` -> `Util\JWT`
  - `Request\GetTransactionHistory` -> `Request\GetTransactionHistoryRequest`
  - `Request\RequestTestNotification` -> `Request\RequestTestNotificationRequest`
  - `Request\GetTransactionHistoryQueryParams` -> `RequestQueryParams\GetTransactionHistoryQueryParams`
- Environment consts moved out from all classes to the separate class `Environment`
- `getTransactionHistory()` method signature changed: it no longer expects for QueryParams instance as a second arguments, now it expects array instead
- `AppStoreServerAPI` (previously `APIClient`) constructor signature changed:
  - `$environment` argument type changed from int to string
  - `$keyId` and `$key` arguments swapped

**IMPROVEMENTS:**

- PHP 7.4 support out of the box ;)
- A lot of new endpoints (see [README](https://github.com/readdle/app-store-server-api/blob/master/README.md))
- Examples for all implemented endpoints (and notification listener)
