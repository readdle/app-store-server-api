# About this project

This is a library which aim is to build communication with `App Store` using `App Store Server API` and `App Store Server Notifications V2`.

At the moment this library covers:
- Sending request for test notification
- Receiving notifications
- Sending request for transactions history by originalTransactionId

# Taking a part in this project

I don't know at the moment how it will go, and I'm not sure this library will be under active (or any, tbh) development.

At the same time, it is **highly** appreciated to take part in this project for anyone interested, maybe one day we'll
cover all the functionality together, who knows :)

# Installation

Nothing special here, just use composer to install the package:

> composer install readdle/app-store-server-api

# Usage

Take a look at `sample.php` in the root directory. It contains stubs instead of real keys/IDs/etc., but if you replace
stubs with your actual credentials you will be able to communicate with App Store, request test notification, receive it
(of course, you'll need to set it up in your App Store Connect) and request transactions history for the app you have.

# Overall structure

I will try to cover more topics here later. Feel free to ask questions, submit pull requests and so on.
