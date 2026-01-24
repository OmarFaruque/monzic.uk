# Lemon Squeezy Payment Flow Diagrams

## Complete Payment Flow (Both Layers)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         CUSTOMER JOURNEY                                     │
└─────────────────────────────────────────────────────────────────────────────┘

                              CHECKOUT PAGE
                                   │
                                   ▼
                    ┌──────────────────────────┐
                    │ Generate Checkout URL    │
                    │ POST /lemonsqueezy/      │
                    │     create-checkout      │
                    └────────────┬─────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │   Store in localStorage │
                    │  {entity_id: 123,       │
                    │   entity_type: 'quote'} │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼──────────────────┐
                    │  Redirect to Lemon Squeezy    │
                    │  (Customer completes payment) │
                    └────────────┬──────────────────┘
                                 │
                    ┌────────────▼──────────────────┐
                    │  Lemon Squeezy redirects to  │
                    │  /payment/success            │
                    └────────────┬──────────────────┘
                                 │
     ┌───────────────────────────┴────────────────────────────┐
     │                                                        │
     ▼                                                        ▼
PRIMARY LAYER                                      SECONDARY LAYER
(SYNCHRONOUS)                                      (ASYNCHRONOUS)

┌─────────────────────┐                      ┌────────────────────────┐
│   SUCCESS PAGE      │                      │   LEMON SQUEEZY        │
│   /payment/success  │                      │   Sends webhook event  │
│                     │                      │   order_created        │
│ 1. Load page        │                      └────────────┬───────────┘
│ 2. Retrieve entity  │                                   │
│    ID from          │                      ┌────────────▼───────────┐
│    localStorage     │                      │  POST /lemonsqueezy/   │
│ 3. POST             │                      │  webhook               │
│    /confirm-        │                      │                        │
│    payment with ID  │                      │  Header:               │
│ 4. Backend:         │                      │  x-signature: ...      │
│    - Find exact     │                      └────────────┬───────────┘
│      Quote/Doc      │                                   │
│    - Check status   │◄──────────────────────────────────┤ FALLBACK
│    - Update to      │                                   │ IF PRIMARY
│      'paid'         │                                   │ FAILS
│    - Send email     │                      ┌────────────▼───────────┐
│ 5. Display          │                      │  Verify HMAC-SHA256    │
│    confirmation     │                      │  Signature             │
│ 6. Clear            │                      └────────────┬───────────┘
│    localStorage     │                                   │
└─────────────────────┘                      ┌────────────▼───────────┐
                                             │  Extract user_email    │
                                             │  and timestamp          │
                                             └────────────┬───────────┘
                                                          │
                                             ┌────────────▼───────────┐
                                             │  Search for Quote/Doc  │
                                             │  by email within       │
                                             │  10-minute window      │
                                             └────────────┬───────────┘
                                                          │
                                             ┌────────────▼───────────┐
                                             │  Check if NOT already  │
                                             │  'paid'                │
                                             └────────────┬───────────┘
                                                          │
                                             ┌────────────▼───────────┐
                                             │  Update to 'paid'      │
                                             │  Send email            │
                                             │  Return 200            │
                                             └────────────────────────┘
```

## Decision Tree

```
┌──────────────────────────────────────────────┐
│  Payment Successfully Completed              │
│  Customer redirected to /payment/success     │
└────────────────────┬─────────────────────────┘
                     │
        ┌────────────▼─────────────┐
        │ Check localStorage for   │
        │ entity_id and type       │
        └────┬────────────┬────────┘
             │            │
        YES  │            │  NO
             │            │
        ┌────▼─┐      ┌───▼──────────────┐
        │      │      │  Show Error      │
        ▼      │      │  "No payment     │
        │      │      │   information    │
        │      │      │   found"         │
        │      │      │                  │
        │      │      │  Show Fallback   │
        │      │      │  message         │
        │      │      │  (Webhook will   │
        │      │      │   process later) │
        │      │      └──────────────────┘
        │      │
        │      └────────────┐
        │                   │
        ▼                   ▼
        │         ┌──────────────────────┐
        │         │  POST               │
        │         │  /confirm-payment   │
        │         │  with entity_id     │
        │         └──────┬───────────────┘
        │                │
        └────────┬───────┤
                 │       │
        ┌────────▼───┐   │
        │ Response   │   │
        │ Timeout?   │   │
        │ (30 sec)   │   │
        └─┬────────┬─┘   │
          │ YES    │ NO  │
          │        │     │
        ┌─▼─┐   ┌─▼─────▼───────┐
        │   │   │ API Response   │
        │   │   │ Received       │
        │   │   └─┬──────────┬───┘
        │   │     │          │
        │   │  SUCCESS    ERROR
        │   │     │          │
        │   │  ┌──▼────────┐ │
        │   │  │ Display   │ │
        │   │  │ Confirm.  │ │
        │   │  │ Policy    │ │
        │   │  │ Number    │ │
        │   │  └───────────┘ │
        │   │                │
        │   │           ┌────▼─────────┐
        │   │           │ Show Error   │
        │   │           │ Message      │
        │   │           │ (Webhook     │
        │   │           │  will try)   │
        │   │           └──────────────┘
        │   │
        │   └─────────────────┐
        │                     │
        │   ┌─────────────────▼──────┐
        │   │ Show Action Buttons    │
        │   │ - View My Orders       │
        │   │ - Return Home          │
        │   └────────────────────────┘
        │
        └──────────┐
                   │
        ┌──────────▼──────────────────┐
        │ Clear localStorage          │
        │ Remove payment data         │
        └─────────────────────────────┘
```

## State Transition Diagram

```
                    Quote/AiDocument States
                            
┌──────────────────────────────────────────────────────────────┐
│                     PAYMENT LIFECYCLE                         │
└──────────────────────────────────────────────────────────────┘

        ┌─────────────────┐
        │  NULL / PENDING │  ← Initial state
        │  Payment Status │
        └────────┬────────┘
                 │
         ┌───────┴─────────┐
         │                 │
    PRIMARY          SECONDARY
    LAYER            LAYER
         │                 │
    ┌────▼─────────┐   ┌───▼──────────┐
    │ Success      │   │ Webhook      │
    │ Page         │   │ Callback     │
    │ /confirm-    │   │              │
    │ payment      │   │ Email +      │
    │              │   │ Time match   │
    │ POST with    │   │              │
    │ exact ID     │   │ Search DB    │
    │              │   │ by email     │
    └────┬─────────┘   └───┬──────────┘
         │                 │
    ┌────▼─────────────────▼────────┐
    │  Check if status NOT 'paid'    │  ← Duplicate check
    │  (Duplicate Prevention)        │
    └────┬─────────────┬────────────┘
         │ NOT paid    │ Already paid
         │             │
    ┌────▼─────┐   ┌───▼──────┐
    │ Update   │   │  Skip    │
    │ to PAID  │   │  Return  │
    │          │   │  200 OK  │
    └────┬─────┘   └──────────┘
         │
    ┌────▼──────────────┐
    │  Send Emails      │
    │  (async job)      │
    └────┬──────────────┘
         │
    ┌────▼──────────────┐
    │  PAID ✓           │  ← Final state
    │  (Completed)      │
    └───────────────────┘
```

## Timeline Comparison

### Success Page (Primary - Fast)
```
t=0s    Customer completes payment at Lemon Squeezy
t=0.1s  Redirected to /payment/success
t=0.2s  Page loads, retrieves entity_id from localStorage
t=0.5s  POST /confirm-payment sent to server
t=0.6s  Server finds exact Quote by ID (immediate)
t=0.7s  Database updated: status = 'paid'
t=0.8s  Email job dispatched
t=0.9s  Response returned to frontend
t=1.0s  Success page displays policy number
        ← TOTAL: ~1 second (instant feedback)

Later...
t=+5s   Webhook arrives from Lemon Squeezy
t=+6s   Webhook checks status, finds already 'paid', skips
        ← Fallback unused (primary succeeded)
```

### Webhook (Secondary - Slower)
```
t=0s    Customer completes payment at Lemon Squeezy
t=0.1s  Redirected to /payment/success
t=0.2s  Page loads, tries /confirm-payment
t=0.3s  Network timeout (connection fails)
        ← Page shows error, tells user to wait

t=+2s   Webhook arrives from Lemon Squeezy
t=+3s   Webhook signature verified (HMAC-SHA256)
t=+4s   Search for Quote by email within 10-min window
t=+5s   Found! Check status: null (not yet 'paid')
t=+6s   Database updated: status = 'paid'
t=+7s   Email job dispatched
t=+8s   Response returned: 200 OK
        ← TOTAL: ~8 seconds (fallback catches it)
        
Customer may refresh page and see updated status
```

## Architecture Layers

```
┌─────────────────────────────────────────────────────────────┐
│                      CLIENT (Browser)                        │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Checkout Page - store entity ID in localStorage        │ │
│  └────────────────────────────────────────────────────────┘ │
│                          │                                   │
│  ┌────────────────────────▼────────────────────────────────┐ │
│  │ Success Page - retrieve from localStorage               │ │
│  │ POST /confirm-payment with exact ID                     │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                           │
        ┌──────────────────┴──────────────────┐
        │                                     │
        ▼ (HTTPS)                             ▼ (HTTPS)
┌──────────────────┐              ┌──────────────────────────┐
│  Laravel App     │              │  External Event Source   │
│  (API Server)    │              │  (Lemon Squeezy)         │
│                  │              │                          │
│ POST /confirm-   │◄─ Primary ─  │ order_created event     │
│ payment          │              │                          │
│                  │              │                          │
│ POST /webhook    │◄─ Secondary ─│ (with HMAC signature)   │
│                  │              │                          │
└────────┬─────────┘              └──────────────────────────┘
         │
    ┌────▼──────────────────┐
    │   Database Layer       │
    │                        │
    │ ┌──────────────────┐   │
    │ │ Quotes Table     │   │
    │ │ - id             │   │
    │ │ - email          │   │
    │ │ - policy_number  │   │
    │ │ - paymentStatus  │   │
    │ │ - paymentDate    │   │
    │ │ - paymentMethod  │   │
    │ │ - paymentIntentId│   │
    │ └──────────────────┘   │
    │                        │
    │ ┌──────────────────┐   │
    │ │ AiDocuments      │   │
    │ │ - id             │   │
    │ │ - email          │   │
    │ │ - status         │   │
    │ │ - paymentIntentId│   │
    │ └──────────────────┘   │
    └────────────────────────┘
         │
    ┌────▼──────────────────┐
    │   Email Queue          │
    │   (Async Jobs)         │
    │                        │
    │ SendQuoteConfirm...    │
    │ SendAiDocumentConfirm..│
    └────────────────────────┘
```

## Error Handling Flow

```
┌──────────────────────────┐
│  Primary Confirmation    │
│  (Success Page)          │
└────────┬─────────────────┘
         │
         ▼
    ┌────────────┐
    │ Success    │ ─→ ✓ Done, show policy number
    └────────────┘
    
    ┌────────────┐
    │ Timeout    │ ─→ Show warning: "Webhook will process"
    └────────────┘
                      ↓ (Fallback to Webhook)
                  ┌────────────┐
                  │ Success    │ ─→ ✓ Done
                  └────────────┘
                  
                  ┌────────────┐
                  │ Not Found  │ ─→ Show error: "Contact support"
                  └────────────┘

    ┌────────────┐
    │ Not Found  │ ─→ Show error: "No entity found"
    │ (No entity)│      (Will try webhook)
    └────────────┘
                      ↓ (Fallback to Webhook)
                  ┌────────────┐
                  │ Success    │ ─→ ✓ Done
                  └────────────┘
                  
                  ┌────────────┐
                  │ Not Found  │ ─→ Manual intervention needed
                  └────────────┘

    ┌────────────┐
    │ Validation │ ─→ Show error: "Invalid request"
    │ Error      │      (Check browser console)
    └────────────┘

    ┌────────────┐
    │ Network    │ ─→ Retry with exponential backoff
    │ Error      │      If fails: Webhook fallback
    └────────────┘
```

---

These diagrams show:
1. **Complete payment flow** - How both layers work together
2. **Decision tree** - What happens at each step
3. **State transitions** - How payment status changes
4. **Timeline comparison** - Speed of primary vs secondary
5. **Architecture layers** - System components
6. **Error handling** - What happens when things go wrong

For more details, see the implementation guides in the root directory.
