# Lemon Squeezy Payment Implementation - Two-Layer Security Architecture

## Overview

This document outlines the dual-layer security approach for handling Lemon Squeezy payments in the Laravel application. The implementation combines:

1. **Primary Layer (Success Page Callback)** - Synchronous, direct payment confirmation
2. **Secondary Layer (Webhook Handler)** - Asynchronous fallback verification

## Architecture

### Layer 1: Success Page Callback (Primary - Synchronous)

The success page is the **primary and most secure** method of confirming payments.

**Flow:**
```
1. User at checkout → Store ENTITY_ID in localStorage
   ↓
2. Redirect to Lemon Squeezy checkout
   ↓
3. Customer completes payment → Redirected to /payment/success
   ↓
4. Success page retrieves ENTITY_ID from localStorage
   ↓
5. POST to /lemonsqueezy/confirm-payment with exact entity ID
   ↓
6. Backend updates Quote/AiDocument with status='paid'
   ↓
7. Send confirmation emails
```

**Why This is Secure:**
- No email/timeframe guessing - uses exact stored ID
- Synchronous confirmation - immediate feedback
- Prevents duplicate processing with status checks
- localStorage persists across redirect
- Works regardless of order arrival time at webhook

**Implementation Files:**
- [checkout_lemonsqueezy.blade.php](../resources/views/checkout_lemonsqueezy.blade.php#L762-L782) - Stores ID in localStorage before redirect
- [lemonsqueezy-success.blade.php](../resources/views/payment/lemonsqueezy-success.blade.php) - Retrieves ID and confirms payment
- [LemonSqueezyController.php](../app/Http/Controllers/LemonSqueezyController.php#L105) - `confirmPayment()` method

### Layer 2: Webhook Handler (Secondary - Asynchronous)

The webhook acts as a **safety net** for edge cases.

**Flow:**
```
1. Lemon Squeezy sends order_created event
   ↓
2. Verify HMAC-SHA256 signature with webhook secret
   ↓
3. Extract user_email and timestamp
   ↓
4. Search for Quote/AiDocument by email within 10-minute window
   ↓
5. Check if paymentStatus is NOT 'paid' (conditional update)
   ↓
6. If status is 'pending' or NULL → Update to 'paid'
   ↓
7. If status is 'paid' → Skip (prevent duplicates)
   ↓
8. Send confirmation emails (if not already sent)
```

**Why This Exists:**
- Catches payments if success page callback fails/never fires
- Network failures on success page
- User closes browser before callback completes
- Acts as an idempotent safety mechanism

**When Webhook Takes Over:**
- Success callback times out (30 seconds)
- localStorage is unavailable
- Customer navigates directly to success page without localStorage
- Browser doesn't support localStorage

**Implementation Files:**
- [LemonSqueezyController.php](../app/Http/Controllers/LemonSqueezyController.php#L129) - `webhook()` method
- [web.php routes](../../routes/web.php#L316) - Webhook route configuration

## Configuration

### Required Settings in Database

Store these in the `settings` table:

```
param: 'lemonsqueezy_api_key'
param: 'lemonsqueezy_store_id'
param: 'lemonsqueezy_variant_id'
param: 'lemonsqueezy_webhook_secret'
```

The webhook secret should be set in your Lemon Squeezy dashboard:
- Settings → Webhooks → Add webhook
- URL: `{your-domain}/lemonsqueezy/webhook`
- Event: `order_created`
- Copy the signing secret to your database

## Payment Status Flow

### Quote Payment Statuses

```
NULL/pending (initial)
    ↓
    ├─ Via Success Callback → 'paid' ✓ (Preferred)
    │
    └─ Via Webhook → 'paid' ✓ (Fallback)
        ↓
    'completed' (final status, email sent)
```

### AI Document Payment Statuses

```
NULL (initial)
    ↓
    ├─ Via Success Callback → 'paid' ✓ (Preferred)
    │
    └─ Via Webhook → 'paid' ✓ (Fallback)
```

## Duplicate Prevention

Both methods include duplicate prevention:

**Success Callback:**
```php
if ($quote->paymentStatus === 'paid') {
    return response()->json(['success' => true, 'message' => 'Payment already processed']);
}
```

**Webhook:**
```php
if ($matchingQuote->paymentStatus === 'paid') {
    Log::info('Quote already paid, skipping webhook processing');
    return response()->json(['received' => true, 'message' => 'Payment already processed']);
}
```

## Error Scenarios & Handling

| Scenario | Primary Success | Secondary Webhook | Result |
|----------|----------------|------------------|--------|
| Normal flow | ✓ Succeeds | Skips (already paid) | ✓ Quote paid |
| Success page timeout | ✗ Fails | ✓ Processes | ✓ Quote paid |
| No localStorage | ✗ Error shown | ✓ Processes | ✓ Quote paid |
| Both fail | ✗ Error message | ✗ No record found | ✗ Manual intervention |

## API Endpoints

### 1. Create Checkout
```
POST /lemonsqueezy/create-checkout
Content-Type: application/json

{
    "id": 123,                          // Quote or AI Document ID
    "type": "ai" (optional),            // Only if AI Document
    "tip": 10.50 (optional)             // Tip amount
}

Response:
{
    "checkoutUrl": "https://checkout.lemonsqueezy.com/..."
}
```

### 2. Confirm Payment (Success Page)
```
POST /lemonsqueezy/confirm-payment
Content-Type: application/json

{
    "entity_id": 123,
    "entity_type": "quote|ai_document",
    "order_id": "lsq_abc123def456"      // Optional - Lemon Squeezy order ID
}

Response:
{
    "success": true,
    "message": "Quote payment confirmed",
    "policy_number": "POL-123456"       // For quotes only
}
```

### 3. Webhook (Lemon Squeezy)
```
POST /lemonsqueezy/webhook
Headers:
    x-signature: <HMAC-SHA256>

Payload:
{
    "meta": {
        "event_name": "order_created",
        ...
    },
    "data": {
        "id": "lsq_abc123",
        "attributes": {
            "user_email": "customer@example.com",
            "created_at": "2024-01-24T10:30:00Z"
        }
    }
}
```

## Key Features

### 1. HMAC-SHA256 Signature Verification
Ensures webhook requests are genuinely from Lemon Squeezy:
```php
$hmac = hash_hmac('sha256', $rawBody, $webhookSecret, true);
$computedSignature = bin2hex($hmac);

if (!hash_equals($computedSignature, $signature)) {
    return response()->json(['error' => 'Invalid signature'], 401);
}
```

### 2. Time Window Matching (10 minutes)
For webhook fallback, matches orders within 10 minutes of creation:
```php
$timeWindow = 10 * 60; // seconds
$minTime = (clone $createdAt)->modify("-{$timeWindow} seconds");
$maxTime = (clone $createdAt)->modify("+{$timeWindow} seconds");
```

### 3. Most Recent Match
If multiple records exist in timeframe, uses most recent:
```php
$matchingQuote = Quote::where(...)
    ->orderBy('created_at', 'desc')
    ->first();  // Gets the last one (most recent)
```

### 4. Asynchronous Email Sending
Jobs are dispatched to avoid blocking response:
```php
dispatch(new \App\Jobs\SendQuoteConfirmationEmail($quote->id));
dispatch(new \App\Jobs\SendAiDocumentConfirmationEmail($doc->id));
```

### 5. Database Transactions
Ensures data consistency:
```php
DB::beginTransaction();
try {
    // Update operations
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
}
```

## localStorage Schema

When redirecting to Lemon Squeezy, the following data is stored:

```javascript
{
    entity_id: 123,                    // Quote or AI Document ID
    entity_type: "quote|ai_document",  // Type of entity
    timestamp: 1705998600000           // When stored (milliseconds)
}
```

**Cleanup:** localStorage is cleared after success page confirmation.

## Success Page UX

The success page provides:
- ✓ Loading spinner while processing (30-second timeout)
- ✓ Success confirmation with policy number
- ✓ Links to "View My Orders" and "Return Home"
- ✓ Error handling with clear messages
- ✓ Fallback to webhook if success callback fails

## Logging

Both endpoints log important events for debugging:

```php
Log::info('Quote payment confirmed via success callback', [
    'quote_id' => $quote->id,
    'policy_number' => $quote->policy_number,
    'payment_intent_id' => $orderId
]);

Log::warning('Lemon Squeezy webhook: No matching quote or document', [
    'email' => $userEmail,
    'time_window' => "..."
]);
```

## Testing

### Test Success Flow
1. Go to checkout page
2. Select Lemon Squeezy payment
3. Check browser localStorage: `lemonsqueezy_payment`
4. Complete Lemon Squeezy payment
5. Verify payment status updated immediately on success page

### Test Webhook Flow
1. Use webhook testing tool (e.g., ngrok)
2. Send test `order_created` event
3. Verify webhook signature verification passes
4. Check logs for payment processing
5. Verify Quote/AiDocument status updated

### Test Duplicate Prevention
1. Manually call `confirmPayment` endpoint twice with same ID
2. Verify second call returns "already processed"
3. Verify email not sent twice

## Troubleshooting

### Webhook Secret Not Found
**Issue:** 500 error on webhook
**Solution:** Verify `lemonsqueezy_webhook_secret` setting is configured
```sql
SELECT * FROM settings WHERE param = 'lemonsqueezy_webhook_secret';
```

### Signature Verification Failed
**Issue:** Webhook returns 401
**Solution:** 
- Verify webhook secret matches Lemon Squeezy dashboard
- Check raw request body hasn't been modified
- Ensure hash_equals is used (timing-safe comparison)

### Email Not Sending
**Issue:** Payment confirmed but email not received
**Solution:** Check if jobs are processed:
```bash
php artisan queue:work  # If using queued jobs
php artisan log:tail    # Check logs
```

### Success Page Shows Error
**Issue:** "No payment information found"
**Solution:** 
- Check if localStorage is enabled in browser
- Verify browser allows localStorage for domain
- Check browser console for storage errors

## Security Considerations

1. **CSRF Protection:** All endpoints protected with CSRF token
2. **Signature Verification:** Webhook requests verified with HMAC-SHA256
3. **Timing-Safe Comparison:** Uses `hash_equals()` to prevent timing attacks
4. **Database Transactions:** Ensures atomic operations
5. **Status Checks:** Prevents duplicate processing
6. **Conditional Updates:** Only updates if in correct state

## Future Enhancements

1. **Payment Expiration:** Clean up old unpaid orders
2. **Retry Logic:** Automatic retry for failed email sending
3. **Webhook Dead Letter Queue:** Store failed webhook events for replay
4. **Payment Metrics:** Track success/failure rates
5. **Admin Notifications:** Alert admin of webhook failures
