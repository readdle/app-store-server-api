### [3.8.1] 2024-07-08

**BUGFIX:**

- Default TTL for payload introduced and set to 5 min. Previous value of 1 hour (which is the maximum) seems to be the cause of failed responses in some cases.
- Makefile introduced just to have a shortcut 'make shell' for running Docker container using PHP image and having project directory mounted 

### [3.8.0] 2024-04-08

**IMPROVEMENTS:**

- Nullable properties now are NOT converted to empty int/bool/float/string in AppMetadata, RenewalInfo, ResponseBodyV2, TransactionInfo, kudos to @dbrkv for pointing this out
- ArrayTypeCastGenerator moved to the separate class
- PHPUnit tests introduced (just the first one for ArrayTypeCastGenerator atm)
- Examples reworked a bit (RenewalInfo/TransactionInfo printing moved to the separate helper function)

### [3.7.0] 2024-03-28

**IMPROVEMENTS:**

- Now the response content of the HTTP response is available in `HTTPRequestFailed` exception using `getResponseText()` method, kudos to @soxft for pointing this out 

### [3.6.3] 2024-03-25

**BUGFIX:**

- Handle empty response headers in case if HTTP request to the API fails (and it fails regularly, kudos to Apple)

### [3.6.2] 2024-01-25

**BUGFIX:**

- If the certificate string already has a prefix, there is no need to add it

### [3.6.1] 2023-12-19

**BUGFIX:**

- Treat "202 Accepted" as successful response (App Store returns it on "Send consumption information" request), kudos to @teanooki for pointing this out

### [3.6.0] 2023-12-11

**IMPROVEMENTS:**

New fields implemented
- `TransactionInfo`: `price`, `currency`, and `offerDiscountType` from [App Store Server API version 1.10](https://developer.apple.com/documentation/appstoreserverapi/app_store_server_api_changelog#4307459)

### [3.5.2] 2023-10-10

**BUGFIX:**

- Logic issue in PageableResponse, after fixing syntax issue in `3.5.1`

### [3.5.1] 2023-10-05

**BUGFIX:**

- Syntax issue in PageableResponse for PHP 7.4, kudos to @JamieSTV

### [3.5.0] 2023-09-21

**IMPROVEMENTS:**

Missing endpoints added:
- Send Consumption Information
- Extend a Subscription Renewal Date
- Extend Subscription Renewal Dates for All Active Subscribers
- Get Status of Subscription Renewal Date Extensions

### [3.4.1] 2023-09-17

**BUGFIX:**

- `TransactionInfo`: `storefront`, `storefrontId`, and `transactionReason` are now nullable and null by default, in order to be compatible with old notifications
- `RenewalInfo`: `renewalDate` is now null by default, in order to be compatible with old notifications
- `Response\NotificationHistoryResponse`: `paginationToken` presence in response is now optional

### [3.4.0] 2023-09-16

**IMPROVEMENTS:**

- New `notificationType`/`subtype` in `ResponseBodyV2`

### [3.3.2] 2023-09-16

**BUGFIX:**

- ASN1SequenceOfInteger: multiple `00` bytes in the beginning of integer numbers handled when parsing HEX signature representation

### [3.3.1] 2023-09-07

**BUGFIX:**

- `AppMetadata`: `bundleId`, `bundleVersion`, `renewalInfo`, `transactionInfo` and `status` now are `NULL` by default (to prevent `Typed property ... must not be accessed before initialization` error)

### [3.3.0] 2023-09-06

**IMPROVEMENTS:**

- New field implemented
  - `AppMetadata`: `status`

### [3.2.0] 2023-09-03

**IMPROVEMENTS:**

- New fields implemented
  - `RenewalInfo`: `renewalDate`
  - `TransactionInfo`: `storefront`, `storefrontId`, `transactionReason`

### [3.1.1] 2023-09-03

**BUGFIX:**

- `ResponseBodyV2`: `createFromRawNotification()` fix, now it checks incoming notification to be not only a valid JSON, but also to be an array

### [3.1.0] 2023-08-26

**BUGFIX:**
 
- `ASN1SequenceOfInteger`: math fixes
- `StatusResponse`: `data` array initialization with `[]`

**IMPROVEMENTS:**

- `HTTPRequest`: PUT method added; HTTP method and URL added to `HTTPRequestFailed` exception message
- `JWT`: additional information in exception message

### [3.0.1] 2023-08-23

**BUGFIX:**

- Math bug fixed in `ASN1SequenceOfInteger`. In rare cases signature was calculated in a wrong way which led to `Wrong signature` exception in `JWT::verifySignature`

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
