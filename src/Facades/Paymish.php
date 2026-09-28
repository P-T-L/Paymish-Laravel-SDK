<?php

namespace Bhekor\Paymish\Facades;

use Illuminate\Support\Facades\Facade;
use Bhekor\Paymish\Api\AuthApi;
use Bhekor\Paymish\Api\DisputeApi;
use Bhekor\Paymish\Api\TransactionApi;
use Bhekor\Paymish\Api\TransferApi;
use Bhekor\Paymish\Api\RecipientApi;
use Bhekor\Paymish\Api\SubscriptionApi;
use Bhekor\Paymish\Api\PlanApi;
use Bhekor\Paymish\Api\PaymentPageApi;
use Bhekor\Paymish\Api\ProductApi;
use Bhekor\Paymish\Api\CustomerApi;
use Bhekor\Paymish\Api\BusinessApi;
use Bhekor\Paymish\Api\SplitPageApi;
use Bhekor\Paymish\Api\SubaccountApi;

/**
 * @method static AuthApi auth()
 * @method static DisputeApi dispute()
 * @method static TransactionApi transaction()
 * @method static TransferApi transfer()
 * @method static RecipientApi recipient()
 * @method static SubscriptionApi subscription()
 * @method static PlanApi plan()
 * @method static PaymentPageApi paymentPage()
 * @method static ProductApi product()
 * @method static CustomerApi customer()
 * @method static BusinessApi business()
 * @method static SplitPageApi splitPage()
 * @method static SubaccountApi subaccount()
 */
class Paymish extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'paymish';
    }
}
