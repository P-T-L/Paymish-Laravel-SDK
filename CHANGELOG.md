# Changelog

All notable changes to `laravel-paymish` will be documented in this file.

## [1.0.0] - 2026-10-11

### Added
- Initial stable release of Laravel Paymish package
- Laravel 10–12 support; email/password and public/secret key auth with auto-token
- Transaction verify, dedicated virtual accounts, virtual-accounts CRUD
- Full API coverage for Paymish payment gateway
- Authentication API (token generation)
- Dispute API (create, list, update disputes)
- Transaction API (initialize, process payments via card/USSD/NQR/bank/NDD, verify, refund)
- Transfer API (single/bulk transfers, bank list, balance, transfer details)
- Recipient API (CRUD operations for transfer recipients)
- Subscription API (CRUD operations for subscriptions)
- Plan API (CRUD operations for payment plans)
- Payment Page API (CRUD operations for payment pages)
- Product API (full product management including media, inventory, delivery fees, discount codes)
- Customer API (CRUD operations for customers)
- Business API (business management)
- Split Page API (split payment page management)
- Subaccount API (subaccount management)
- Laravel facade support
- Configuration file with environment-based settings
- Comprehensive error handling with custom exceptions
- Response objects for type-safe data handling
- File upload support for multipart requests
- Pagination support for list endpoints
- GitHub Actions CI matrix (PHP 8.2–8.4, Laravel 10–12)

### Fixed
- Safe endpoint loading outside a bootstrapped Laravel app
- Dot-notation support on `ApiResponse::get()`
- PHP 8.5 `ArrayAccess` return type compatibility
