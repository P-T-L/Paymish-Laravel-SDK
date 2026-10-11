<?php

namespace Bhekor\Paymish;

/**
 * Optional webhook signature helper.
 *
 * Paymish OpenAPI does not document a webhook HMAC scheme. When a secret is
 * configured, Utilify can require HMAC-SHA512 hex of the raw body (Paystack-style).
 */
class PaymishWebhook
{
    public static function valid(string $rawBody, ?string $signatureHeader, ?string $secret = null, ?string $headerName = null): bool
    {
        $secret = $secret ?? (function_exists('config') ? (string) config('paymish.webhook_secret', '') : '');
        if ($secret === '') {
            // No secret configured — signature check is a no-op (caller must re-verify via API).
            return true;
        }

        if (! is_string($signatureHeader) || trim($signatureHeader) === '') {
            return false;
        }

        $computed = strtoupper(hash_hmac('sha512', $rawBody, $secret));

        return hash_equals($computed, strtoupper(trim($signatureHeader)));
    }
}
