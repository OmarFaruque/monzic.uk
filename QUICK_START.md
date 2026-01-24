# Quick Start Guide - Lemon Squeezy Implementation

## 5-Minute Setup

### 1️⃣ Get Your Webhook Secret (1 minute)

1. Log in to **Lemon Squeezy dashboard**
2. Go to **Settings → Webhooks**
3. Click **Add webhook**
4. **URL:** `https://yourdomain.com/lemonsqueezy/webhook`
5. **Event:** Select `order_created`
6. Click **Save**
7. **Copy** the Signing Secret

### 2️⃣ Save Webhook Secret (1 minute)

**Option A: Using Database Tool**
```sql
INSERT INTO settings (param, value, created_at, updated_at) 
VALUES ('lemonsqueezy_webhook_secret', 'YOUR_SECRET_HERE', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  value = 'YOUR_SECRET_HERE',
  updated_at = NOW();
```

**Option B: Using Laravel Tinker**
```bash
php artisan tinker
>>> Setting::updateOrCreate(
    ['param' => 'lemonsqueezy_webhook_secret'],
    ['value' => 'YOUR_SECRET_HERE']
);
>>> exit
```

### 3️⃣ Verify Setup (1 minute)

```bash
# Check webhook route exists
php artisan route:list | grep lemonsqueezy

# Watch for webhook events
tail -f storage/logs/laravel.log | grep -i webhook
```

### 4️⃣ Test Payment (2 minutes)

1. Go to your checkout page
2. Select **Lemon Squeezy** payment
3. Click **Complete Payment**
4. Use test card: **4111 1111 1111 1111** (any future date)
5. Verify:
   - ✓ Success page displays
   - ✓ Policy number shows
   - ✓ Database updated (paymentStatus = 'paid')
   - ✓ Confirmation email received

---

## What Just Happened?

```
1. Payment Stored
   └─ Entity ID saved in localStorage
   
2. Payment Completed
   └─ Redirected to /payment/success
   
3. Confirmed Immediately (Primary Method)
   └─ Success page calls /confirm-payment API
   └─ Exact Quote/AiDocument found by ID
   └─ Status updated to 'paid'
   └─ Email sent
   └─ Policy number displayed
   
4. Webhook as Backup (Secondary Method)
   └─ Arrives 2-5 seconds later
   └─ Verifies signature
   └─ Checks if already paid
   └─ Skips (already processed)
```

---

## Configuration Checklist

- [ ] Webhook secret added to database
- [ ] Webhook URL set in Lemon Squeezy
- [ ] Webhook event set to `order_created`
- [ ] Test payment completed successfully
- [ ] Success page displays policy number
- [ ] Confirmation email received
- [ ] Database shows paymentStatus = 'paid'
- [ ] Logs show webhook processing

---

## Common Tasks

### View Recent Payments
```bash
# In database tool
SELECT id, email, policy_number, paymentStatus, paymentDate 
FROM quotes 
WHERE paymentMethod = 'lemonsqueezy' 
ORDER BY paymentDate DESC 
LIMIT 10;
```

### Watch Webhook Events
```bash
tail -f storage/logs/laravel.log | grep "Lemon Squeezy\|webhook"
```

### Test Webhook Manually
```bash
# Send test event from Lemon Squeezy dashboard:
# Settings → Webhooks → [Your Webhook] → Send Test Event
# Or use your webhook testing tool
```

### Debug Payment Issue
```bash
# Check if entity exists
php artisan tinker
>>> Quote::find(123)
>>> exit

# Check payment settings
>>> Setting::all()
```

---

## Troubleshooting

### "Webhook Secret Not Configured"
```sql
-- Verify it's in database
SELECT * FROM settings WHERE param = 'lemonsqueezy_webhook_secret';

-- If missing, add it
INSERT INTO settings (param, value, created_at, updated_at) 
VALUES ('lemonsqueezy_webhook_secret', 'YOUR_SECRET', NOW(), NOW());
```

### "Invalid Signature"
- Verify webhook secret matches Lemon Squeezy dashboard
- Copy the secret exactly (no extra spaces)
- Re-add if Lemon Squeezy regenerated it

### Success Page Shows Error
- Check if localStorage is enabled in browser
- Try a different browser
- Check browser console (F12) for errors

### Emails Not Sending
```bash
# Start queue worker
php artisan queue:work

# Watch for job logs
tail -f storage/logs/laravel.log | grep -i "SendQuote\|SendAiDoc"
```

---

## Files Created

✅ **app/Http/Controllers/LemonSqueezyController.php**
- `confirmPayment()` - Primary confirmation
- `webhook()` - Secondary fallback
- `paymentSuccess()` - Success page display

✅ **routes/web.php**
- `POST /lemonsqueezy/confirm-payment`
- `POST /lemonsqueezy/webhook`
- `GET /payment/success`

✅ **resources/views/payment/lemonsqueezy-success.blade.php**
- Beautiful success page with localStorage handling

✅ **Documentation Files**
- `LEMON_SQUEEZY_IMPLEMENTATION.md` - Full architecture
- `WEBHOOK_SETUP_GUIDE.md` - Detailed setup
- `API_USAGE_EXAMPLES.md` - Code examples
- `FLOW_DIAGRAMS.md` - Visual diagrams

---

## How It Works (Simple Version)

### What the Customer Sees

```
Checkout Page
    ↓ (Click "Complete Payment")
Lemon Squeezy Checkout
    ↓ (Customer enters card)
Success Page
    ↓ (Shows "Payment Successful")
Policy Number Displayed
```

### What Happens Behind the Scenes

```
1. BEFORE redirecting to Lemon Squeezy:
   └─ Store Quote ID in localStorage
   
2. AFTER customer completes payment:
   └─ Success page retrieves Quote ID from localStorage
   └─ API confirms payment with exact ID (no guessing)
   └─ Database updated: status = 'paid'
   └─ Email sent
   
3. FALLBACK (if success page fails):
   └─ Webhook receives event 2-5 seconds later
   └─ Searches for Quote by email (if needed)
   └─ Confirms payment again
   └─ Emails already sent, skips duplicate
```

---

## Key Security Features

✅ **Exact ID Matching** - No relying on email alone  
✅ **HMAC Verification** - Webhook requests verified with secret  
✅ **Duplicate Prevention** - Status checks prevent double-processing  
✅ **Database Transactions** - All-or-nothing updates  
✅ **CSRF Protection** - All endpoints protected  

---

## Performance Notes

- **Success Confirmation:** ~1 second (instant feedback)
- **Webhook Processing:** ~5-10 seconds (fallback only)
- **Email Sending:** Async (doesn't block response)
- **Database Updates:** Atomic transactions

---

## Next Steps

1. ✅ **Complete Setup** - Add webhook secret to database
2. ✅ **Test Payment** - Complete full payment flow
3. ✅ **Monitor Logs** - Watch for any errors
4. ✅ **Read Details** - Check full documentation if needed
5. ✅ **Go Live** - Deploy with confidence

---

## Getting Help

- **Setup Questions?** → See `WEBHOOK_SETUP_GUIDE.md`
- **Technical Details?** → See `LEMON_SQUEEZY_IMPLEMENTATION.md`
- **Code Examples?** → See `API_USAGE_EXAMPLES.md`
- **Visual Explanation?** → See `FLOW_DIAGRAMS.md`
- **Implementation Summary?** → See `README_IMPLEMENTATION.md`

---

## Quick Commands

```bash
# Test Laravel routes
php artisan route:list | grep lemonsqueezy

# Check configuration
php artisan tinker
>>> Setting::where('param', 'like', 'lemonsqueezy%')->get()

# View recent webhooks in logs
grep -i "lemon squeezy webhook" storage/logs/laravel.log | tail -20

# Start queue for emails
php artisan queue:work

# Test artisan command
php artisan config:cache
```

---

## That's It! 🎉

Your Lemon Squeezy payment system is now set up with:
- ✅ **Primary** secure success page confirmation
- ✅ **Secondary** webhook fallback
- ✅ **Duplicate** prevention
- ✅ **Professional** UX
- ✅ **Enterprise** security

You're ready to accept payments!
