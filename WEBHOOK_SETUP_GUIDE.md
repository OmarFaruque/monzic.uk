# Lemon Squeezy Webhook Setup Guide

## Quick Setup Checklist

### Step 1: Configure Database Settings

Run these SQL commands or use your database management tool:

```sql
-- Add or update Lemon Squeezy webhook secret
INSERT INTO settings (param, value, created_at, updated_at) 
VALUES ('lemonsqueezy_webhook_secret', 'YOUR_WEBHOOK_SECRET_HERE', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  value = 'YOUR_WEBHOOK_SECRET_HERE',
  updated_at = NOW();
```

**Where to get `YOUR_WEBHOOK_SECRET_HERE`:**
1. Log in to your Lemon Squeezy account
2. Go to Settings → Webhooks
3. Create a new webhook with URL: `https://yourdomain.com/lemonsqueezy/webhook`
4. Select `order_created` event
5. Copy the **Signing Secret** value

### Step 2: Set Webhook URL in Lemon Squeezy Dashboard

1. **Login** to Lemon Squeezy
2. **Navigate** to Settings → Webhooks
3. **Create New Webhook** or edit existing one
4. **Set URL** to: `https://yourdomain.com/lemonsqueezy/webhook`
   - For development with ngrok: `https://abc123.ngrok.io/lemonsqueezy/webhook`
5. **Select Events**: `order_created`
6. **Save**
7. **Copy** the Signing Secret to your database

### Step 3: Verify Configuration

Test if webhook route is accessible:

```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "x-signature: test" \
  -d '{}' \
  https://yourdomain.com/lemonsqueezy/webhook
```

Expected response: `{"error":"Invalid signature"}` (not 404)

### Step 4: Test End-to-End

#### Option A: Manual Testing with Test Event

1. In Lemon Squeezy Webhooks dashboard, click "Send Test Event"
2. Check your application logs for webhook processing
3. Verify response in Lemon Squeezy dashboard shows success

#### Option B: Complete Purchase Test

1. Go to checkout page
2. Complete test payment (Lemon Squeezy provides test card numbers)
3. Verify success page loads and shows confirmation
4. Check database for updated Quote/AiDocument status
5. Verify confirmation email sent

#### Option C: Monitor Logs

```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Search for webhook logs
grep -i "lemon\|webhook" storage/logs/laravel.log | tail -50
```

## Webhook Payload Structure

When Lemon Squeezy sends an `order_created` event:

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
      "store_id": 123456,
      "customer_id": 789012,
      "identifier": "45678",
      "order_number": 1,
      "user_name": "John Doe",
      "user_email": "john@example.com",
      "currency": "GBP",
      "currency_rate": "1.0",
      "subtotal": "9999",
      "discount_total": "0",
      "tax": "0",
      "total": "9999",
      "subtotal_usd": "12499",
      "discount_total_usd": "0",
      "tax_usd": "0",
      "total_usd": "12499",
      "tax_name": null,
      "tax_rate": null,
      "status": "completed",
      "status_formatted": "Completed",
      "refunded": false,
      "refunded_at": null,
      "created_at": "2024-01-24T10:30:00Z",
      "updated_at": "2024-01-24T10:30:00Z"
    }
  }
}
```

## Signature Verification Process

Lemon Squeezy signs each webhook with an HMAC-SHA256 signature:

```
1. Lemon Squeezy computes: HMAC-SHA256(request_body, webhook_secret)
2. Sends as header: x-signature: <hex_encoded_digest>
3. We verify by computing same hash and comparing with x-signature
4. Using hash_equals() for timing-safe comparison
```

Our implementation:
```php
$hmac = hash_hmac('sha256', $rawBody, $webhookSecret, true);
$computedSignature = bin2hex($hmac);

if (!hash_equals($computedSignature, $signature)) {
    // Invalid signature - reject webhook
    return response()->json(['error' => 'Invalid signature'], 401);
}
```

## Development Testing with ngrok

### If using ngrok for local development:

```bash
# 1. Start ngrok tunnel
ngrok http 8000

# 2. Note the HTTPS URL provided, e.g., https://abc123.ngrok.io

# 3. Set webhook URL in Lemon Squeezy:
#    https://abc123.ngrok.io/lemonsqueezy/webhook

# 4. Set webhook secret in local .env or database

# 5. Watch ngrok logs (in separate terminal)
ngrok logs

# 6. Watch Laravel logs
tail -f storage/logs/laravel.log
```

### ngrok Limitations:
- Webhook secret changes each session (regenerate in Lemon Squeezy)
- Inspect traffic at `http://localhost:4040`
- Session expires after 2 hours (pro plans get more)

## Common Issues & Solutions

### Issue: "Webhook secret not configured"

**Error Log:**
```
Lemon Squeezy webhook secret not configured
```

**Solution:**
```sql
SELECT * FROM settings WHERE param = 'lemonsqueezy_webhook_secret';
```

If not found, add it:
```sql
INSERT INTO settings (param, value, created_at, updated_at) 
VALUES ('lemonsqueezy_webhook_secret', 'YOUR_SECRET', NOW(), NOW());
```

### Issue: "Invalid signature"

**Possible Causes:**
1. Webhook secret doesn't match Lemon Squeezy
2. Raw request body modified (JSON formatting issue)
3. Signature header malformed

**Solution:**
1. Verify webhook secret in Lemon Squeezy dashboard
2. Copy exact secret to database
3. Test with Lemon Squeezy's "Send Test Event" button

### Issue: "No matching record found"

**Occurs when:**
- Quote/AiDocument created outside 10-minute window
- Customer email changed between creation and payment
- Entity already deleted

**Solution:**
- Success page callback handles this (no email matching needed)
- Webhook is just a fallback
- For old unpaid quotes, use success page with localStorage

### Issue: Webhook fires but doesn't update payment

**Check:**
1. Quote/AiDocument exists with matching email
2. Creation time within 10 minutes of order time
3. Payment status is `null` or `'pending'` (not already `'paid'`)
4. Logs show what was matched

**Debug:**
```php
// In webhook method, add temporary logging
Log::info('Webhook searching for quote', [
    'email' => $userEmail,
    'min_time' => $minTime->format('Y-m-d H:i:s'),
    'max_time' => $maxTime->format('Y-m-d H:i:s'),
]);

// View quotes in that window
$quotes = Quote::where('email', $userEmail)
    ->whereBetween('created_at', [$minTime, $maxTime])
    ->get();

Log::info('Found quotes', ['count' => count($quotes), 'quotes' => $quotes->toArray()]);
```

### Issue: Emails not sending after webhook

**Check:**
1. Is queue worker running?
   ```bash
   ps aux | grep queue
   ```
2. If not, run:
   ```bash
   php artisan queue:work
   ```

3. Check jobs table for failures:
   ```sql
   SELECT * FROM failed_jobs ORDER BY failed_at DESC LIMIT 5;
   ```

4. Check logs:
   ```bash
   grep -i "SendQuoteConfirmation\|SendAiDocumentConfirmation" storage/logs/laravel.log
   ```

## Monitoring Webhooks

### Laravel Logs Location
```
storage/logs/laravel.log
```

### Search for Webhook Events
```bash
# Show all webhook events
grep "Lemon Squeezy webhook" storage/logs/laravel.log

# Show only webhook errors
grep -i "webhook.*error\|error.*webhook" storage/logs/laravel.log

# Show webhook with timestamps
grep "Lemon Squeezy webhook" storage/logs/laravel.log | tail -20
```

### Database Query for Payments

```sql
-- Find recently paid quotes
SELECT id, email, policy_number, paymentStatus, paymentMethod, paymentDate 
FROM quotes 
WHERE paymentMethod = 'lemonsqueezy' 
ORDER BY paymentDate DESC 
LIMIT 10;

-- Find recently paid AI documents
SELECT id, email, status, paymentIntentId 
FROM ai_documents 
WHERE paymentIntentId IS NOT NULL 
ORDER BY updated_at DESC 
LIMIT 10;

-- Find unpaid quotes (webhook might not have found)
SELECT id, email, policy_number, paymentStatus, created_at 
FROM quotes 
WHERE paymentStatus IS NULL OR paymentStatus = 'pending'
ORDER BY created_at DESC 
LIMIT 10;
```

## Webhook Retry Logic

**Lemon Squeezy behavior:**
- If webhook returns status 200: Marked as delivered
- If webhook returns status >= 400: Retried up to 5 times
- Retry intervals: Immediate, 1 min, 5 min, 30 min, 2 hours

**Our implementation:**
- Always returns 200 if webhook secret is correct
- Logs all events for audit trail
- Transactional updates prevent data corruption
- Idempotent (safe to process multiple times)

## Production Checklist

- [ ] Webhook secret set in production database
- [ ] Webhook URL set in Lemon Squeezy production account
- [ ] HTTPS certificate valid (webhooks must use HTTPS)
- [ ] Firewall allows incoming POST from Lemon Squeezy IPs
- [ ] Mail driver configured for production
- [ ] Queue worker running for async email jobs
- [ ] Log rotation configured for high-volume logging
- [ ] Error alerts configured (for failed payments)
- [ ] Backup of webhook logs (audit trail)
- [ ] Test webhook with real customer before going live

## Contact & Support

For Lemon Squeezy webhook issues:
- https://docs.lemonsqueezy.com/webhooks

For application debugging:
- Review: [LEMON_SQUEEZY_IMPLEMENTATION.md](LEMON_SQUEEZY_IMPLEMENTATION.md)
- Check logs: `tail -f storage/logs/laravel.log`
- Run: `php artisan tinker` for database queries
