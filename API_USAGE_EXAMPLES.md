# Lemon Squeezy API Usage Examples

## Overview

This document provides practical examples of how to use the Lemon Squeezy payment endpoints in your application.

## 1. Create Checkout Endpoint

**Purpose:** Initiate a Lemon Squeezy checkout session

**Endpoint:**
```
POST /lemonsqueezy/create-checkout
```

**Authentication:** Requires user to be logged in (Laravel Auth)

### Example: Quote Payment

```javascript
async function createQuoteCheckout(quoteId, tipAmount = 0) {
    try {
        const response = await fetch('/lemonsqueezy/create-checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id: quoteId,
                tip: tipAmount
            })
        });

        const data = await response.json();

        if (response.ok && data.checkoutUrl) {
            // Redirect to Lemon Squeezy checkout
            window.location.href = data.checkoutUrl;
            return true;
        } else {
            console.error('Checkout creation failed:', data.error);
            return false;
        }
    } catch (error) {
        console.error('Error creating checkout:', error);
        return false;
    }
}

// Usage
createQuoteCheckout(123, 5.00);
```

### Example: AI Document Payment

```javascript
async function createAIDocumentCheckout(docId, tipAmount = 0) {
    try {
        const response = await fetch('/lemonsqueezy/create-checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id: docId,
                type: 'ai',  // Important: specify type for AI documents
                tip: tipAmount
            })
        });

        const data = await response.json();

        if (response.ok && data.checkoutUrl) {
            window.location.href = data.checkoutUrl;
            return true;
        } else {
            console.error('Checkout creation failed:', data.error);
            return false;
        }
    } catch (error) {
        console.error('Error creating checkout:', error);
        return false;
    }
}

// Usage
createAIDocumentCheckout(456, 2.50);
```

### Response Format

**Success (200):**
```json
{
    "checkoutUrl": "https://checkout.lemonsqueezy.com/checkout/XXXXXXX"
}
```

**Error (400/500):**
```json
{
    "error": "Could not initiate Lemon Squeezy payment.",
    "details": {
        "errors": [
            {
                "status": "400",
                "title": "Invalid Request",
                "detail": "Custom price cannot exceed £50000.00"
            }
        ]
    }
}
```

## 2. Confirm Payment Endpoint

**Purpose:** Confirm payment after user completes checkout (PRIMARY METHOD)

**Endpoint:**
```
POST /lemonsqueezy/confirm-payment
```

**Authentication:** Public - called from success page (no auth required)

### Request Body

```javascript
{
    "entity_id": 123,                    // Required: Quote or AI Document ID
    "entity_type": "quote",              // Required: "quote" or "ai_document"
    "order_id": "lsq_abc123def456"      // Optional: Lemon Squeezy Order ID
}
```

### Example: Quote Confirmation

```javascript
async function confirmQuotePayment(quoteId, orderId) {
    try {
        const response = await fetch('/lemonsqueezy/confirm-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                entity_id: quoteId,
                entity_type: 'quote',
                order_id: orderId
            })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            console.log('Quote payment confirmed:', result.policy_number);
            console.log('Message:', result.message);
            return {
                success: true,
                policyNumber: result.policy_number
            };
        } else {
            console.error('Payment confirmation failed:', result.error);
            return {
                success: false,
                error: result.error
            };
        }
    } catch (error) {
        console.error('Error confirming payment:', error);
        return {
            success: false,
            error: error.message
        };
    }
}

// Usage
const paymentData = localStorage.getItem('lemonsqueezy_payment');
if (paymentData) {
    const { entity_id, entity_type } = JSON.parse(paymentData);
    confirmQuotePayment(entity_id, 'lsq_xxx');
}
```

### Example: AI Document Confirmation

```javascript
async function confirmAIDocumentPayment(docId, orderId) {
    try {
        const response = await fetch('/lemonsqueezy/confirm-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                entity_id: docId,
                entity_type: 'ai_document',
                order_id: orderId
            })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            console.log('AI Document payment confirmed:', result.doc_uuid);
            return {
                success: true,
                docUUID: result.doc_uuid
            };
        } else {
            console.error('Payment confirmation failed:', result.error);
            return {
                success: false,
                error: result.error
            };
        }
    } catch (error) {
        console.error('Error confirming payment:', error);
        return {
            success: false,
            error: error.message
        };
    }
}
```

### Response Format

**Success (200):**
```json
{
    "success": true,
    "message": "Quote payment confirmed",
    "policy_number": "POL-2024-001234"
}
```

**Already Processed (200):**
```json
{
    "success": true,
    "message": "Payment already processed",
    "policy_number": "POL-2024-001234"
}
```

**Not Found (404):**
```json
{
    "error": "Entity not found"
}
```

**Validation Error (422):**
```json
{
    "error": "Validation failed",
    "errors": {
        "entity_id": ["The entity id field is required."],
        "entity_type": ["The entity type field must be one of: quote, ai_document."]
    }
}
```

## 3. Webhook Endpoint

**Purpose:** Receive and process payment events from Lemon Squeezy (FALLBACK METHOD)

**Endpoint:**
```
POST /lemonsqueezy/webhook
```

**Authentication:** Webhook signature verification (HMAC-SHA256)

### Request Format

Lemon Squeezy sends (example):
```json
{
    "meta": {
        "event_name": "order_created",
        "custom_data": null,
        "webhook_id": "wh_abc123"
    },
    "data": {
        "type": "orders",
        "id": "lsq_abc123def456",
        "attributes": {
            "user_email": "customer@example.com",
            "created_at": "2024-01-24T10:30:00Z",
            ...
        }
    }
}
```

### Response Format

**Success (200):**
```json
{
    "received": true,
    "message": "Quote payment processed",
    "quote_id": 123
}
```

**Already Processed (200):**
```json
{
    "received": true,
    "message": "Payment already processed"
}
```

**Invalid Signature (401):**
```json
{
    "error": "Invalid signature"
}
```

### How the Webhook Works

See [LEMON_SQUEEZY_IMPLEMENTATION.md](LEMON_SQUEEZY_IMPLEMENTATION.md) for detailed explanation.

## Integration Examples

### Complete Checkout Flow

```html
<!-- HTML -->
<button onclick="initiatePayment()">Pay with Lemon Squeezy</button>

<script>
    const quoteId = 123;
    const tipAmount = 0;

    async function initiatePayment() {
        console.log('Initiating payment for quote:', quoteId);
        
        try {
            // Step 1: Create checkout
            const response = await fetch('/lemonsqueezy/create-checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: quoteId,
                    tip: tipAmount
                })
            });

            const data = await response.json();

            if (response.ok && data.checkoutUrl) {
                // Step 2: localStorage is automatically handled by checkout blade
                console.log('Redirecting to Lemon Squeezy checkout...');
                window.location.href = data.checkoutUrl;
            } else {
                alert('Error: ' + (data.error || 'Could not initiate payment'));
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }
</script>
```

### Success Page Integration

The success page automatically:
1. Retrieves entity ID from localStorage
2. Calls `/lemonsqueezy/confirm-payment`
3. Displays confirmation
4. Clears localStorage

No additional integration needed - it's handled by `lemonsqueezy-success.blade.php`

### Manual Payment Confirmation (Advanced)

If you need to manually confirm a payment outside the success page:

```php
<?php
// In a custom controller or artisan command

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class ManualPaymentController extends Controller
{
    /**
     * Manually confirm a quote payment
     * Usage: POST /manual-confirm-payment
     */
    public function confirmManually(Request $request)
    {
        $validated = $request->validate([
            'quote_id' => 'required|integer|exists:quotes,id',
            'order_id' => 'required|string'
        ]);

        $quote = Quote::find($validated['quote_id']);

        // Check if already paid
        if ($quote->paymentStatus === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Quote already paid',
                'policy_number' => $quote->policy_number
            ]);
        }

        // Update payment
        $quote->update([
            'paymentStatus' => 'paid',
            'status' => 'completed',
            'paymentMethod' => 'lemonsqueezy',
            'paymentIntentId' => $validated['order_id'],
            'paymentDate' => now()
        ]);

        // Send confirmation
        dispatch(new \App\Jobs\SendQuoteConfirmationEmail($quote->id));

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed manually',
            'policy_number' => $quote->policy_number
        ]);
    }
}
```

### Checking Payment Status

```php
<?php
// Check if a quote has been paid

use App\Models\Quote;

$quote = Quote::find(123);

if ($quote->paymentStatus === 'paid') {
    echo "Quote has been paid via " . $quote->paymentMethod;
    echo " on " . $quote->paymentDate;
} else {
    echo "Quote is still pending payment";
}
```

### Query Payment History

```php
<?php
// Find all payments made via Lemon Squeezy

use App\Models\Quote;

$lemonSqueezyPayments = Quote::where('paymentMethod', 'lemonsqueezy')
    ->where('paymentStatus', 'paid')
    ->orderBy('paymentDate', 'desc')
    ->get();

foreach ($lemonSqueezyPayments as $payment) {
    echo "Quote: " . $payment->policy_number . "\n";
    echo "Amount: £" . number_format($payment->cpw, 2) . "\n";
    echo "Paid: " . $payment->paymentDate->format('Y-m-d H:i:s') . "\n";
    echo "Order ID: " . $payment->paymentIntentId . "\n\n";
}
```

## Error Handling Examples

### Handle Network Errors

```javascript
async function robustConfirmPayment(entity_id, entity_type) {
    const maxRetries = 3;
    let attempt = 0;

    while (attempt < maxRetries) {
        try {
            const response = await fetch('/lemonsqueezy/confirm-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    entity_id: entity_id,
                    entity_type: entity_type
                }),
                timeout: 5000  // 5 second timeout
            });

            const result = await response.json();

            if (response.ok && result.success) {
                return { success: true, data: result };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            attempt++;
            console.warn(`Attempt ${attempt} failed:`, error.message);
            
            if (attempt < maxRetries) {
                // Wait before retrying (exponential backoff)
                await new Promise(r => setTimeout(r, 1000 * attempt));
            }
        }
    }

    return { success: false, error: 'Max retries exceeded' };
}
```

### Timeout Handling

```javascript
async function confirmPaymentWithTimeout(entity_id, entity_type, timeoutMs = 30000) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeoutMs);

    try {
        const response = await fetch('/lemonsqueezy/confirm-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                entity_id: entity_id,
                entity_type: entity_type
            }),
            signal: controller.signal
        });

        clearTimeout(timeoutId);
        return await response.json();
    } catch (error) {
        clearTimeout(timeoutId);
        
        if (error.name === 'AbortError') {
            console.warn('Payment confirmation timed out after', timeoutMs, 'ms');
            console.warn('Webhook will process as fallback');
            return {
                success: false,
                error: 'Confirmation timeout',
                message: 'Webhook will process payment in background'
            };
        }
        
        throw error;
    }
}
```

## Testing

### Test Checkout Creation

```bash
curl -X POST https://yourdomain.com/lemonsqueezy/create-checkout \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -H "Cookie: LARAVEL_SESSION=YOUR_SESSION" \
  -d '{
    "id": 123,
    "tip": 5.00
  }'
```

### Test Payment Confirmation

```bash
curl -X POST https://yourdomain.com/lemonsqueezy/confirm-payment \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{
    "entity_id": 123,
    "entity_type": "quote",
    "order_id": "lsq_abc123"
  }'
```

### Test Webhook

```bash
curl -X POST https://yourdomain.com/lemonsqueezy/webhook \
  -H "Content-Type: application/json" \
  -H "x-signature: VALID_SIGNATURE" \
  -d '{
    "meta": {"event_name": "order_created"},
    "data": {
      "id": "lsq_test123",
      "attributes": {
        "user_email": "test@example.com",
        "created_at": "2024-01-24T10:30:00Z"
      }
    }
  }'
```

For more details, see:
- [LEMON_SQUEEZY_IMPLEMENTATION.md](LEMON_SQUEEZY_IMPLEMENTATION.md) - Full architecture
- [WEBHOOK_SETUP_GUIDE.md](WEBHOOK_SETUP_GUIDE.md) - Setup and troubleshooting
