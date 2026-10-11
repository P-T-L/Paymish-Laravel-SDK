# Laravel Paymish

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bhekor/laravel-paymish.svg?style=flat-square)](https://packagist.org/packages/bhekor/laravel-paymish)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bhekor/laravel-paymish/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bhekor/laravel-paymish/actions?query=workflow%3ATests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bhekor/laravel-paymish.svg?style=flat-square)](https://packagist.org/packages/bhekor/laravel-paymish)
[![License](https://img.shields.io/packagist/l/bhekor/laravel-paymish.svg?style=flat-square)](LICENSE.md)

Laravel package for the [Paymish](https://paymish.com) payment gateway. Covers transactions, transfers, virtual accounts, subscriptions, products, customers, and more.

Official API docs: https://api.paymish.com/api/user-service/external/v1/docs/

## Requirements

- PHP 8.2+
- Laravel 10, 11, or 12
- Guzzle 7+

## Installation

```bash
composer require bhekor/laravel-paymish
```

Laravel auto-discovers the service provider and `Paymish` facade. Publish the config when you need to customize defaults:

```bash
php artisan vendor:publish --tag="paymish-config"
```

## Configuration

Add credentials to `.env`. Prefer OpenAPI keys when available; email/password also works:

```env
PAYMISH_PUBLIC_KEY=your-public-key
PAYMISH_SECRET_KEY=your-secret-key
# OR
PAYMISH_EMAIL=your-email@example.com
PAYMISH_PASSWORD=your-password

PAYMISH_BASE_URL=https://api.paymish.com
PAYMISH_LIVE=false
PAYMISH_TIMEOUT=30
PAYMISH_CURRENCY=NGN
PAYMISH_TOKEN_CACHE_SECONDS=3500
PAYMISH_WEBHOOK_SECRET=
PAYMISH_WEBHOOK_SIGNATURE_HEADER=x-paymish-signature
```

Amounts for initialize / DVA / transfers are in **NGN major units** (send `1100` for ₦1,100). OpenAPI may mention subunits; live Paymish treats the value as naira.

## Security

- Never hardcode live card numbers, CVVs, or BVNs in application code.
- Prefer `initialize()` + Paymish hosted checkout over raw `withCard()` when possible (PCI scope).
- Examples below use **sandbox/test placeholders only**.
- Always re-verify payments server-side (`transaction()->verify()`) and on webhooks — do not trust client redirects alone.

## Quick start

```php
use Bhekor\Paymish\Facades\Paymish;

// Initialize (hosted / redirect flow)
$response = Paymish::transaction()->initialize([
    'email' => 'customer@email.com',
    'amount' => '10000',
    'currency' => 'NGN',
    'channels' => ['card', 'ussd', 'nqr', 'transfer'],
    'callback_url' => 'https://your-site.com/callback',
]);

// Verify after callback or webhook
$response = Paymish::transaction()->verify('transaction-reference');
// data.status: completed | failed | pending | reversed
```

The client auto-authenticates from config before API calls (cached bearer JWT).

## Usage

```php
use Bhekor\Paymish\Facades\Paymish;
```

### Authentication

Usually automatic. To generate a token manually:

```php
$response = Paymish::auth()->generateToken([
    'public_key' => '...',
    'secret_key' => '...',
]);
// or: ['email' => '...', 'password' => '...']
```

### Transactions

```php
// Initialize with Dynamic Virtual Account
$response = Paymish::transaction()->initializeDva([
    'email' => 'customer@email.com',
    'amount' => '10000',
    'reference' => 'unique-reference',
]);

// Name enquiry (bank account)
$response = Paymish::transaction()->verifyAccount([
    'bankCode' => '058',
    'accountNumber' => '0123456789',
]);

// List / refund
$response = Paymish::transaction()->list(['status' => 'completed', 'page' => 1, 'limit' => 20]);
$response = Paymish::transaction()->refund([
    'transactionRef' => 'TXN_...',
    'amount' => 100,
]);
```

Channel helpers (test/sandbox data only):

```php
Paymish::transaction()->withCard([/* customerId, amount, cardNumber, expiryYear, expiryMonth, cvv */]);
Paymish::transaction()->withUssd(['customerId' => 8, 'amount' => 100]);
Paymish::transaction()->withQr([/* customerId, amount, mch_no, sub_mch_no */]);
Paymish::transaction()->withBank([/* customerId, amount, AccountNumber, DestinationInstitutionCode */]);
Paymish::transaction()->withNdd([/* customerId, amount, ndd => [...] */]);

Paymish::transaction()->verifyUssd(['ussdTransactionId' => '...', 'isLive' => false]);
Paymish::transaction()->verifyBank([/* DestinationInstitutionCode, AccountNumber, isLive */]);
Paymish::transaction()->getBanks(false);
```

### Dedicated virtual accounts

```php
$response = Paymish::dedicatedVirtualAccount()->create([
    'customerId' => 'cust_...',
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'customer@email.com',
    'phoneNumber' => '08012345678',
    'bvn' => '22222222226', // test placeholder
    'dob' => '1990-01-15',
    'address' => '123 Main Street, Lagos',
    'gender' => 'Male',
]);
```

### Virtual accounts

```php
$response = Paymish::virtualAccount()->create([/* ... */]);
$response = Paymish::virtualAccount()->list(['page' => 1, 'limit' => 20]);
$response = Paymish::virtualAccount()->get($id);
$response = Paymish::virtualAccount()->update($id, [/* ... */]);
```

### Transfers

```php
$response = Paymish::transfer()->getAllBanks();

$response = Paymish::transfer()->resolveAccount([
    'bankCode' => '058',
    'accountNumber' => '0123456789',
]);

$response = Paymish::transfer()->createSingle([
    'amount' => 1000,
    'bankCode' => '999998',
    'bankName' => 'Zenith Bank',
    'accountNumber' => '0051762787',
    'accountName' => 'Test Account',
    'emailAddress' => 'email@example.com',
    'transferNote' => 'Payment for invoice #1234',
    'transferReference' => 'INV1234TRANS5678',
]);

$response = Paymish::transfer()->getList(['status' => 'Successful', 'page' => 1, 'limit' => 10]);
$response = Paymish::transfer()->getBalance();
$response = Paymish::transfer()->getDetails('TRF_...');
$response = Paymish::transfer()->verifyTransfer('TRF_...'); // prefers transfer code
$response = Paymish::transfer()->submitBulk([/* bulk payload */]);
```

### Recipients

```php
$response = Paymish::recipient()->list(['limit' => 10, 'page' => 1]);
$response = Paymish::recipient()->getDetails('RCP_...');
$response = Paymish::recipient()->delete('RCP_...');
$response = Paymish::recipient()->blacklist(['recipientId' => '...', 'isBlackListed' => true]);
```

### Subscriptions & plans

```php
$response = Paymish::plan()->create([
    'planName' => 'Premium Plan',
    'planAmount' => '50.00',
    'interval' => 'Monthly',
    'planDescription' => 'Premium subscription plan',
]);
$response = Paymish::plan()->list(['status' => 'Active', 'page' => 1, 'limit' => 10]);
$response = Paymish::plan()->getDetails(1);
$response = Paymish::plan()->updateDetails([/* ... */]);
$response = Paymish::plan()->delete(['id' => 1]);

$response = Paymish::subscription()->create([
    'planId' => 49,
    'customerId' => 'CUST789',
    'amount' => 29.99,
    'interval' => 'monthly',
    'status' => 'active',
]);
$response = Paymish::subscription()->list(['status' => 'active', 'page' => 1, 'limit' => 10]);
$response = Paymish::subscription()->get(1);
$response = Paymish::subscription()->update(1, [/* ... */]);
$response = Paymish::subscription()->delete(1);
```

### Payment pages

```php
$response = Paymish::paymentPage()->create([
    'pageType' => 'Subscription',
    'pageName' => 'My Payment Page',
    'pageDescription' => 'Sample payment page',
    'planId' => 456,
]);
$response = Paymish::paymentPage()->list(['status' => 'Published', 'page' => 1, 'limit' => 10]);
$response = Paymish::paymentPage()->getDetails(1);
$response = Paymish::paymentPage()->checkCustomLink(['customLink' => 'my-page']);
$response = Paymish::paymentPage()->addProduct([/* pageId, productId, ... */]);
$response = Paymish::paymentPage()->getProducts(1);
```

### Products

```php
$response = Paymish::product()->create([
    'productName' => 'Sample Product',
    'productDescription' => 'This is a sample product',
    'productPrice' => '100.00',
    'quantityType' => 'Limited',
    'quantityStock' => 100,
]);
$response = Paymish::product()->list(['page' => 1, 'limit' => 10]);
$response = Paymish::product()->getDetails('123');
$response = Paymish::product()->uploadMedia('123', '/path/to/image.jpg');
$response = Paymish::product()->addDigitalDownloadFile('123', '/path/to/file.pdf');
$response = Paymish::product()->addDiscountCode([
    'productId' => '123',
    'code' => 'DISCOUNT20',
    'discountPercentage' => 20,
]);
```

Additional product helpers (media, inventory, delivery fees, discount codes) follow the same facade pattern — see [OpenAPI docs](https://api.paymish.com/api/user-service/external/v1/docs/) for full payloads.

### Customers

```php
$response = Paymish::customer()->create([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone_number' => '08012345678',
]);
$response = Paymish::customer()->list(['limit' => 10, 'page' => 1, 'search' => 'John']);
$response = Paymish::customer()->getById(1);
$response = Paymish::customer()->update([/* ... */]);
$response = Paymish::customer()->blacklist(['id' => 1, 'isBlackListed' => true]);
```

### Business

```php
$response = Paymish::business()->changeMode(true); // live mode
$response = Paymish::business()->getDetails();
$response = Paymish::business()->list();
$response = Paymish::business()->create([
    'countryId' => 1,
    'businessName' => 'My Business',
    'businessCategory' => 'Retail',
    'businessDescription' => 'Description',
]);
```

### Split pages & subaccounts

```php
$response = Paymish::splitPage()->create([
    'name' => 'Revenue Split',
    'splitType' => 'percentage',
]);
// Paymish API field name is `shere` (not `share`)
$response = Paymish::splitPage()->addSubaccount(1, [
    'shere' => 50,
    'sub_account_id' => 'ACCT_...',
]);

$response = Paymish::subaccount()->create([
    'business_name' => 'My Business',
    'account_number' => '1234567890',
    'percentage_charge' => 10,
    'currency' => 'NGN',
    'bank' => 999004,
    'account_name' => 'Account Name',
]);
$response = Paymish::subaccount()->list(['page' => 1, 'limit' => 10]);
$response = Paymish::subaccount()->get(1);
```

### Disputes

```php
$response = Paymish::dispute()->create([
    'image' => '/path/to/evidence.jpg',
    'transactionRef' => 'TXN_...',
    'disputeReason' => 'Product not received',
    'disputeDescription' => 'Detailed description of the dispute',
]);
$response = Paymish::dispute()->list(['limit' => 10, 'page' => 1]);
$response = Paymish::dispute()->getDetails('TXN_...');
$response = Paymish::dispute()->update([/* ... */]);
```

## Webhooks

Paymish OpenAPI does not document a webhook HMAC scheme. This package includes an optional Paystack-style helper (HMAC-SHA512 hex of the raw body).

If `PAYMISH_WEBHOOK_SECRET` is empty, `PaymishWebhook::valid()` returns `true` (no-op) — you **must** still verify the payment via the API.

```php
use Bhekor\Paymish\Facades\Paymish;
use Bhekor\Paymish\PaymishWebhook;
use Illuminate\Http\Request;

public function handle(Request $request)
{
    $rawBody = $request->getContent();
    $signature = $request->header(config('paymish.webhook_signature_header'));

    if (! PaymishWebhook::valid($rawBody, $signature)) {
        abort(401, 'Invalid webhook signature');
    }

    $payload = $request->json()->all();
    $reference = $payload['data']['reference'] ?? $payload['reference'] ?? null;

    if ($reference) {
        $verified = Paymish::transaction()->verify($reference);
        // fulfill order when $verified->successful() and status is completed
    }

    return response()->json(['received' => true]);
}
```

## Response handling

All API methods return an `ApiResponse`:

```php
$response = Paymish::transaction()->initialize([/* ... */]);

if ($response->successful()) {
    $data = $response->all();
    $transactionId = $response->get('transactionId');
    $nested = $response->get('data.status'); // dot notation supported
    $message = $response->message();
    $status = $response->getStatusCode();

    if ($response->has('data')) {
        $transactionData = $response->data();
    }

    $array = $response->toArray();
}
```

## Error handling

```php
use Bhekor\Paymish\Exceptions\AuthenticationException;
use Bhekor\Paymish\Exceptions\ValidationException;
use Bhekor\Paymish\Exceptions\PaymishException;

try {
    $response = Paymish::transaction()->initialize([/* ... */]);
} catch (AuthenticationException $e) {
    // 401 / missing credentials
} catch (ValidationException $e) {
    $errors = $e->getErrors();
} catch (PaymishException $e) {
    // 404, 5xx, and other API errors
    echo $e->getMessage();
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.

## Contributing

Contributions are welcome — open a pull request or [issue](https://github.com/bhekor/laravel-paymish/issues).

## Credits

- [Ibidapo Adeolu (bhekor)](https://github.com/bhekor)

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.
