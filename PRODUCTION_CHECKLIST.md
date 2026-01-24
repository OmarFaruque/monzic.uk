# Production Deployment Checklist

## Pre-Deployment (Development Environment)

- [ ] **Test Success Page Flow**
  - [ ] Complete payment test with test card
  - [ ] Verify success page displays
  - [ ] Confirm policy number shown correctly
  - [ ] Verify email sent
  - [ ] Check localStorage cleared after confirmation

- [ ] **Test Webhook Processing**
  - [ ] Send test event from Lemon Squeezy
  - [ ] Verify webhook received and processed
  - [ ] Check signature verification passes
  - [ ] Verify database updated
  - [ ] Check logs show webhook processing

- [ ] **Test Duplicate Prevention**
  - [ ] Complete payment once
  - [ ] Manually call confirm endpoint twice
  - [ ] Verify "already processed" response on second call
  - [ ] Verify email only sent once

- [ ] **Test Error Handling**
  - [ ] Try confirm with invalid entity ID
  - [ ] Try confirm with wrong entity type
  - [ ] Verify error messages are helpful
  - [ ] Check logs show errors

- [ ] **Test Timeout Handling**
  - [ ] Simulate slow API response (30+ seconds)
  - [ ] Verify success page shows timeout message
  - [ ] Verify webhook still processes as fallback
  - [ ] Confirm payment eventually processed

- [ ] **Code Quality Checks**
  - [ ] Run Laravel PHPUnit tests
  - [ ] Check for PHP syntax errors
  - [ ] Verify no debug statements left in code
  - [ ] Review log formatting
  - [ ] Check database transaction handling

---

## Staging Environment

- [ ] **Deploy to Staging**
  - [ ] Push code to staging branch
  - [ ] Run migrations (if any database changes)
  - [ ] Clear caches: `php artisan cache:clear`
  - [ ] Verify all routes exist: `php artisan route:list`

- [ ] **Configure Staging Secrets**
  - [ ] Add webhook secret to staging database
  - [ ] Set up test Lemon Squeezy account (if separate)
  - [ ] Configure webhook URL for staging domain
  - [ ] Test signature verification with staging secret

- [ ] **Verify Configuration**
  ```bash
  php artisan config:show --only=app
  php artisan tinker
  >>> Setting::where('param', 'like', 'lemonsqueezy%')->get()
  ```

- [ ] **Test Full Payment Flow**
  - [ ] Complete test payment
  - [ ] Verify success page loads on staging domain
  - [ ] Confirm payment status updated
  - [ ] Check emails (use Mailtrap or similar)

- [ ] **Test Webhook on Staging**
  - [ ] Update webhook URL in Lemon Squeezy to staging domain
  - [ ] Send test event from Lemon Squeezy
  - [ ] Verify webhook received on staging
  - [ ] Check logs and database updates

- [ ] **Performance Testing**
  - [ ] Load test the confirm endpoint
  - [ ] Verify response time < 1 second
  - [ ] Check database load
  - [ ] Monitor memory usage

- [ ] **Security Audit**
  - [ ] Verify CSRF tokens working
  - [ ] Check HTTPS enabled
  - [ ] Verify signature verification strict
  - [ ] Check no sensitive data in logs

---

## Pre-Production Switch

- [ ] **Backup Production Data**
  - [ ] Database backup taken
  - [ ] Code backup/tag in git
  - [ ] Configuration backed up
  - [ ] Logs archived

- [ ] **Final Code Review**
  - [ ] Review all controller methods
  - [ ] Review all new routes
  - [ ] Review success page HTML/JS
  - [ ] Check database transaction rollback paths
  - [ ] Verify error handling comprehensive

- [ ] **Database Preparation**
  - [ ] Backup production database
  - [ ] Run any migrations needed
  - [ ] Verify queries optimized
  - [ ] Check indexes on relevant columns

- [ ] **Update Production Settings**
  - [ ] Get production webhook secret from Lemon Squeezy
  - [ ] Add to production database:
    ```sql
    INSERT INTO settings (param, value, created_at, updated_at) 
    VALUES ('lemonsqueezy_webhook_secret', 'PROD_SECRET', NOW(), NOW())
    ON DUPLICATE KEY UPDATE value = 'PROD_SECRET';
    ```
  - [ ] Verify all other Lemon Squeezy settings present
  - [ ] Test settings retrieval in production environment

---

## Production Deployment

- [ ] **Deploy Code**
  - [ ] Pull latest code to production
  - [ ] Run composer install/update
  - [ ] Clear all caches: `php artisan cache:clear`
  - [ ] Clear config cache: `php artisan config:cache`
  - [ ] Clear route cache: `php artisan route:cache`

- [ ] **Verify Deployment**
  ```bash
  # Test routes available
  php artisan route:list | grep lemonsqueezy
  
  # Test configuration
  php artisan tinker
  >>> Setting::where('param', 'lemonsqueezy_webhook_secret')->first()
  
  # Check if success page loads
  curl -I https://yourdomain.com/payment/success
  ```

- [ ] **Configure Production Webhook**
  - [ ] Log in to **Lemon Squeezy Production Account**
  - [ ] Go to **Settings → Webhooks**
  - [ ] **Add/Update Webhook:**
    - URL: `https://yourdomain.com/lemonsqueezy/webhook`
    - Event: `order_created`
    - Copy Signing Secret
  - [ ] Add secret to production database
  - [ ] Test webhook with "Send Test Event"

- [ ] **Test Production Payment**
  - [ ] Complete test payment with test card
  - [ ] Verify success page displays
  - [ ] Check email received
  - [ ] Verify database updated
  - [ ] Check logs for successful webhook processing

- [ ] **Verify HTTPS & Security**
  - [ ] HTTPS certificate valid (not self-signed)
  - [ ] No mixed content warnings
  - [ ] HSTS headers configured
  - [ ] Security headers present

- [ ] **Set Up Monitoring**
  - [ ] Email alerts for failed jobs
  - [ ] Error reporting configured (Sentry, etc.)
  - [ ] Log aggregation set up (if applicable)
  - [ ] Database monitoring enabled

---

## Post-Deployment

- [ ] **Monitor First 24 Hours**
  - [ ] Watch logs for errors: `tail -f storage/logs/laravel.log`
  - [ ] Monitor webhook processing
  - [ ] Check customer emails being sent
  - [ ] Monitor error rates
  - [ ] Check queue processing

- [ ] **Database Monitoring**
  - [ ] Monitor query performance
  - [ ] Check no deadlocks occurring
  - [ ] Verify transaction rollbacks working correctly
  - [ ] Monitor disk space for logs

- [ ] **Email Monitoring**
  - [ ] Verify confirmation emails sending
  - [ ] Check email deliverability
  - [ ] Monitor for bounce rates
  - [ ] Check email templates rendering correctly

- [ ] **Process Real Payments**
  - [ ] Accept first few payments manually if desired
  - [ ] Monitor each payment through full flow
  - [ ] Verify database updates immediately
  - [ ] Confirm customers receive emails
  - [ ] Check success page displays correct info

- [ ] **Queue Processing**
  - [ ] Ensure queue worker running: `ps aux | grep queue`
  - [ ] Monitor queue length
  - [ ] Check failed_jobs table for errors
  - [ ] Set up queue monitor/supervisor

- [ ] **Incident Response**
  - [ ] Have runbook for common issues
  - [ ] Know how to manually confirm payment
  - [ ] Have emergency contact for Lemon Squeezy
  - [ ] Have backup payment method ready

---

## Ongoing Maintenance

### Daily
- [ ] **Monitor Logs**
  ```bash
  grep -i "error\|exception" storage/logs/laravel.log | tail -20
  ```
- [ ] **Check Payment Processing**
  ```sql
  SELECT COUNT(*) FROM quotes WHERE paymentStatus = 'paid' AND paymentDate > DATE_SUB(NOW(), INTERVAL 1 DAY);
  ```
- [ ] **Verify Queue Health**
  ```bash
  php artisan queue:failed
  ```

### Weekly
- [ ] **Review Logs for Trends**
  ```bash
  grep "Lemon Squeezy" storage/logs/laravel.log | tail -100
  ```
- [ ] **Check Error Rates**
  - [ ] Failed payments
  - [ ] Failed email sends
  - [ ] Webhook signature failures
- [ ] **Database Maintenance**
  ```sql
  -- Verify no orphaned payments
  SELECT COUNT(*) FROM quotes WHERE paymentStatus = 'paid' AND paymentIntentId IS NULL;
  ```

### Monthly
- [ ] **Webhook Event Review**
  - [ ] Check webhook delivery logs in Lemon Squeezy
  - [ ] Verify 100% delivery rate
  - [ ] No excessive retries
- [ ] **Payment Statistics**
  - [ ] Total payments processed
  - [ ] Success rate
  - [ ] Average processing time
  - [ ] Failed payment analysis
- [ ] **Database Cleanup**
  - [ ] Archive old logs
  - [ ] Remove old test payments (if any)
  - [ ] Verify backup integrity

### Quarterly
- [ ] **Performance Review**
  - [ ] Response time analysis
  - [ ] Database query optimization
  - [ ] Log file rotation working
  - [ ] Storage space usage
- [ ] **Security Audit**
  - [ ] Review webhook secret rotation policy
  - [ ] Check HTTPS certificate expiry
  - [ ] Verify no credential leaks in logs
  - [ ] Review access logs
- [ ] **Update Documentation**
  - [ ] Update runbooks with new learnings
  - [ ] Document any issues encountered
  - [ ] Update troubleshooting guide

---

## Emergency Procedures

### Payment Not Processing
1. Check if customer sees success page
2. Check database if order updated
3. Check logs for errors
4. If webhook failed:
   ```bash
   grep "Lemon Squeezy webhook" storage/logs/laravel.log
   ```
5. Manually confirm if needed using Tinker

### Email Not Received
1. Check if queue worker running: `ps aux | grep queue`
2. Check failed_jobs table
3. Verify MAIL_* configuration in .env
4. Check email service provider status
5. Manually resend using job class

### Webhook Signature Failing
1. Verify webhook secret matches database
2. Check if Lemon Squeezy regenerated secret
3. Get new secret from Lemon Squeezy
4. Update database
5. Resend test event

### Database Lock/Deadlock
1. Check active connections
2. Kill long-running transactions if needed
3. Check transaction isolation level
4. Review slow query log
5. Consider adding indexes

---

## Rollback Plan

### If Issues Discovered After Deployment

1. **Immediate Actions**
   - Stop accepting new payments
   - Notify customers
   - Revert webhook to previous version
   - Check if fix can be hotfixed

2. **Revert Code** (if needed)
   - Revert to previous Git tag
   - Clear all caches
   - Test rollback in staging first
   - Deploy with care

3. **Communication**
   - Inform support team of issue
   - Prepare customer communication
   - Set up status page
   - Monitor closely during recovery

---

## Sign-Off

- [ ] **Development Lead** - Code reviewed and tested
- [ ] **DevOps/Infrastructure** - Server configured and ready
- [ ] **QA** - All tests passing
- [ ] **Product/Project Manager** - All requirements met
- [ ] **Support Team** - Trained on new system

**Deployment Date:** _______________  
**Deployed By:** _______________  
**Sign-Off Date:** _______________  

---

## Success Criteria

✅ All payments processed successfully  
✅ Success pages load and display correctly  
✅ Confirmation emails received by customers  
✅ Database updated immediately after payment  
✅ Webhook processing as fallback  
✅ No duplicate emails sent  
✅ No errors in logs  
✅ Less than 1 second confirmation time  
✅ 100% webhook delivery rate  
✅ Zero payment loss  

**Go-Live Approval:** _________________ (Signature/Date)
