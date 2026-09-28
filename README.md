# Laravel Paymish

A comprehensive Laravel package for seamless integration with the Paymish payment gateway API. Supports transactions, transfers, subscriptions, products, customers, and more for Nigerian payment processing.

## Installation

Install the package via Composer:

```bash
composer require bhekor/laravel-paymish
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Bhekor\Paymish\PaymishServiceProvider" --tag="paymish-config"
```

## Configuration

Add your Paymish credentials to your `.env` file:

```env
PAYMISH_API_KEY=your-api-key
PAYMISH_BASE_URL=https://api.paymish.com
PAYMISH_LIVE=false
PAYMISH_TIMEOUT=30
PAYMISH_CURRENCY=NGN
```

## Usage

### Authentication

First, generate an authentication token:

```php
use Bhekor\Paymish\Facades\Paymish;

$response = Paymish::auth()->generateToken([
    'email' => 'your-email@example.com',
    'password' => 'your-password',
]);

// The token is automatically set on the client for subsequent requests
```

### Transactions

Initialize a transaction:

```php
$response = Paymish::transaction()->initialize([
    'email' => 'customer@email.com',
    'amount' => '10000',
    'currency' => 'NGN',
    'channels' => ['card', 'ussd', 'nqr', 'transfer'],
    'callback_url' => 'https://your-site.com/callback',
]);
```

Initialize with Dynamic Virtual Account:

```php
$response = Paymish::transaction()->initializeDva([
    'email' => 'customer@email.com',
    'amount' => '10000',
    'reference' => 'unique-reference',
]);
```

Process transactions:

```php
// Card transaction
$response = Paymish::transaction()->withCard([
    'customerId' => 5,
    'amount' => 100,
    'cardNumber' => 5123450161003312,
    'expiryYear' => 2024,
    'expiryMonth' => 12,
    'cvv' => 123,
]);

// USSD transaction
$response = Paymish::transaction()->withUssd([
    'customerId' => 8,
    'amount' => 100,
]);

// QR/NQR transaction
$response = Paymish::transaction()->withQr([
    'customerId' => 165,
    'amount' => 152,
    'mch_no' => 'M0000005463',
    'sub_mch_no' => 'S0000007263',
]);

// Bank transaction
$response = Paymish::transaction()->withBank([
    'customerId' => 8,
    'amount' => 100,
    'AccountNumber' => '1780004070',
    'DestinationInstitutionCode' => '999998',
]);

// NDD (NIBSS Direct Debit) transaction
$response = Paymish::transaction()->withNdd([
    'customerId' => 165,
    'amount' => 152,
    'ndd' => [
        'productId' => 1,
        'billerId' => 1,
        'accountNumber' => '2222222222',
        'bankCode' => '058',
        // ... other NDD fields
    ],
]);
```

List transactions:

```php
$response = Paymish::transaction()->list([
    'status' => 'completed',
    'page' => 1,
    'limit' => 20,
]);
```

Refund a transaction:

```php
$response = Paymish::transaction()->refund([
    'transactionRef' => 'TXN_waTjNBitbamwNnLuavU1xd',
    'amount' => 100,
]);
```

### Transfers

Get list of banks:

```php
$response = Paymish::transfer()->getAllBanks();
```

Create a single transfer:

```php
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
```

Get transfer list:

```php
$response = Paymish::transfer()->getList([
    'status' => 'Successful',
    'page' => 1,
    'limit' => 10,
]);
```

Get balance:

```php
$response = Paymish::transfer()->getBalance();
```

### Recipients

List recipients:

```php
$response = Paymish::recipient()->list([
    'limit' => 10,
    'page' => 1,
]);
```

Get recipient details:

```php
$response = Paymish::recipient()->getDetails('RCP_9SEoJGbj9joXqecsntLNYE');
```

Delete recipient:

```php
$response = Paymish::recipient()->delete('RCP_9SEoJGbj9joXqecsntLNYE');
```

### Subscriptions

Create subscription:

```php
$response = Paymish::subscription()->create([
    'planId' => 49,
    'planName' => 'Premium Plan',
    'customerId' => 'CUST789',
    'amount' => 29.99,
    'nextPaymentDate' => '2024-10-01',
    'startDate' => '2024-09-01',
    'subscriptionCode' => 'SUB_ABC123',
    'paymentCount' => 12,
    'interval' => 'monthly',
    'status' => 'active',
]);
```

List subscriptions:

```php
$response = Paymish::subscription()->list([
    'status' => 'active',
    'page' => 1,
    'limit' => 10,
]);
```

### Plans

Create plan:

```php
$response = Paymish::plan()->create([
    'planName' => 'Premium Plan',
    'planAmount' => '50.00',
    'interval' => 'Monthly',
    'planDescription' => 'Premium subscription plan',
]);
```

List plans:

```php
$response = Paymish::plan()->list([
    'status' => 'Active',
    'interval' => 'Monthly',
    'page' => 1,
    'limit' => 10,
]);
```

### Payment Pages

Create payment page:

```php
$response = Paymish::paymentPage()->create([
    'pageType' => 'Subscription',
    'pageName' => 'My Payment Page',
    'pageDescription' => 'This is a sample payment page',
    'planId' => 456,
]);
```

List payment pages:

```php
$response = Paymish::paymentPage()->list([
    'status' => 'Published',
    'page' => 1,
    'limit' => 10,
]);
```

### Products

Create product:

```php
$response = Paymish::product()->create([
    'productName' => 'Sample Product',
    'productDescription' => 'This is a sample product',
    'productPrice' => '100.00',
    'quantityType' => 'Limited',
    'quantityStock' => 100,
]);
```

Upload product media:

```php
$response = Paymish::product()->uploadMedia('123', '/path/to/image.jpg');
```

Add digital download files:

```php
$response = Paymish::product()->addDigitalDownloadFile('123', '/path/to/file.pdf');
```

Add discount code:

```php
$response = Paymish::product()->addDiscountCode([
    'productId' => '123',
    'code' => 'DISCOUNT20',
    'discountPercentage' => 20,
]);
```

### Customers

Create customer:

```php
$response = Paymish::customer()->create([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone_number' => '1234567890',
]);
```

List customers:

```php
$response = Paymish::customer()->list([
    'limit' => 10,
    'page' => 1,
    'search' => 'John',
]);
```

### Business

Change business mode:

```php
$response = Paymish::business()->changeMode(true); // Set to live mode
```

Get business details:

```php
$response = Paymish::business()->getDetails();
```

### Split Pages

Create split page:

```php
$response = Paymish::splitPage()->create([
    'name' => 'Revenue Split',
    'splitType' => 'percentage',
]);
```

Add subaccount to split page:

```php
$response = Paymish::splitPage()->addSubaccount(1, [
    'shere' => 50,
    'sub_account_id' => 'ACCT_ommrl743pgmbi8r',
]);
```

### Subaccounts

Create subaccount:

```php
$response = Paymish::subaccount()->create([
    'business_name' => 'My Business',
    'account_number' => '1234567890',
    'percentage_charge' => 10,
    'currency' => 'NGN',
    'bank' => 999004,
    'account_name' => 'Account Name',
]);
```

List subaccounts:

```php
$response = Paymish::subaccount()->list([
    'page' => 1,
    'limit' => 10,
]);
```

### Disputes

Create dispute:

```php
$response = Paymish::dispute()->create([
    'image' => '/path/to/evidence.jpg',
    'transactionRef' => 'TXN_4HfVJ6nkANC57iPiVXtYLS',
    'disputeReason' => 'Product not received',
    'disputeDescription' => 'Detailed description of the dispute',
]);
```

List disputes:

```php
$response = Paymish::dispute()->list([
    'limit' => 10,
    'page' => 1,
]);
```

## Response Handling

All API methods return an `ApiResponse` object:

```php
$response = Paymish::transaction()->initialize([...]);

// Check if request was successful
if ($response->successful()) {
    // Get all data
    $data = $response->all();

    // Get specific value
    $transactionId = $response->get('transactionId');

    // Get message
    $message = $response->message();

    // Check if key exists
    if ($response->has('data')) {
        // Access data as array
        $transactionData = $response->data();
    }
}
```

## Error Handling

The package throws specific exceptions for different error scenarios:

```php
use Bhekor\Paymish\Exceptions\AuthenticationException;
use Bhekor\Paymish\Exceptions\ValidationException;
use Bhekor\Paymish\Exceptions\PaymishException;

try {
    $response = Paymish::transaction()->initialize([...]);
} catch (AuthenticationException $e) {
    // Handle authentication errors
    echo $e->getMessage();
} catch (ValidationException $e) {
    // Handle validation errors
    $errors = $e->getErrors();
} catch (PaymishException $e) {
    // Handle general API errors
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

Contributions are welcome! Please feel free to submit a Pull Request.

## Credits

- [Ibidapo Adeolu (bhekor)](https://github.com/bhekor)

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.
