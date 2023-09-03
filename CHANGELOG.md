### [3.2.0] 2023-09-03

**IMPROVEMENTS:**

- New fields implemented
  - RenewalInfo: renewalDate
  - TransactionInfo: storefront, storefrontId, transactionReason

### [3.1.1] 2023-09-03

**BUGFIX:**

- ResponseBodyV2: createFromRawNotification() fix, now it checks incoming notification to be not only a valid JSON, but also to be an array

### [3.1.0] 2023-08-26

**BUGFIX:**
 
- ASN1SequenceOfInteger: math fixes
- StatusResponse: `data` array initialization with `[]`

**IMPROVEMENTS:**

- HTTPRequest: PUT method added; HTTP method and URL added to HTTPRequestFailed exception message
- JWT: additional information in exception message

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
