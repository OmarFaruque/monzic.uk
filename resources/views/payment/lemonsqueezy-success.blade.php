@extends('templates.page')

@push('meta')
    <title>Payment Successful - {{ config('app.name') }}</title>
@endpush

@push('css')
    <style>
        .success-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            color: white;
        }

        .success-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .success-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .policy-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            text-align: left;
        }

        .policy-info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .policy-info-item:last-child {
            border-bottom: none;
        }

        .policy-info-label {
            font-weight: 600;
            color: #333;
        }

        .policy-info-value {
            color: #666;
            word-break: break-word;
        }

        .success-actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary, .btn-secondary {
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .btn-primary {
            background: var(--gtheme-color);
            color: white;
        }

        .btn-primary:hover {
            filter: brightness(0.95);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #333;
        }

        .btn-secondary:hover {
            background: #dee2e6;
        }

        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--gtheme-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .processing {
            color: #666;
            font-size: 16px;
            margin: 30px 0;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            display: none;
        }

        .success-banner {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="success-container">
            <div class="success-icon">✓</div>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-message">
                Thank you for your payment. We're processing your order and will send you a confirmation email shortly.
            </p>

            <div class="success-banner" id="successBanner">
                <strong>Payment Confirmed!</strong> Your order has been successfully processed and updated.
            </div>

            <div class="processing" id="processingMsg">
                <span class="loading-spinner"></span>
                Processing your payment confirmation...
            </div>

            <div class="error-message" id="errorMessage"></div>

            <div class="policy-info" id="policyInfo" style="display: none;">
                <div class="policy-info-item">
                    <span class="policy-info-label">Policy Number:</span>
                    <span class="policy-info-value" id="policyNumber">-</span>
                </div>
                <div class="policy-info-item">
                    <span class="policy-info-label">Payment Method:</span>
                    <span class="policy-info-value">Lemon Squeezy</span>
                </div>
                <div class="policy-info-item">
                    <span class="policy-info-label">Confirmation Date:</span>
                    <span class="policy-info-value" id="confirmDate">-</span>
                </div>
            </div>

            <div class="success-actions" id="actions" style="display: none;">
                <button class="btn-primary" onclick="goToMyAccount()">View My Orders</button>
                <button class="btn-secondary" onclick="goHome()">Return Home</button>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const CONFIRMATION_TIMEOUT = 30000; // 30 seconds
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const BASE_URL = window.location.origin;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializePaymentConfirmation();
        });

        /**
         * Main initialization function
         */
        function initializePaymentConfirmation() {
            const storedData = retrievePaymentData();

            if (!storedData) {
                showError('No payment information found. Please contact support if you believe this is an error.');
                showActions();
                return;
            }

            // Confirm payment with backend
            confirmPaymentWithBackend(storedData);

            // Set timeout for fallback
            setTimeout(() => {
                if (document.getElementById('processingMsg').style.display !== 'none') {
                    console.warn('Payment confirmation timeout - webhook may process later');
                    showMessage('Your payment is being processed. Confirmation emails will be sent shortly.');
                    showActions();
                }
            }, CONFIRMATION_TIMEOUT);
        }

        /**
         * Retrieve payment data from localStorage
         */
        function retrievePaymentData() {
            try {
                const data = localStorage.getItem('lemonsqueezy_payment');
                if (data) {
                    const parsed = JSON.parse(data);
                    console.log('Retrieved payment data from localStorage:', {
                        entity_id: parsed.entity_id,
                        entity_type: parsed.entity_type
                    });
                    return parsed;
                }
            } catch (e) {
                console.error('Error parsing localStorage payment data:', e);
            }
            return null;
        }

        /**
         * Confirm payment with backend API
         */
        async function confirmPaymentWithBackend(paymentData) {
            try {
                console.log('Confirming payment with backend for entity:', {
                    entity_id: paymentData.entity_id,
                    entity_type: paymentData.entity_type
                });

                const response = await fetch('/lemonsqueezy/confirm-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        entity_id: paymentData.entity_id,
                        entity_type: paymentData.entity_type
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Payment confirmed successfully
                    showSuccessConfirmation(result, paymentData.entity_type);
                    clearPaymentData();
                } else {
                    // Confirmation failed but payment may have been processed
                    showWarning(result.message || 'Payment confirmation returned an unexpected response.');
                    clearPaymentData();
                }

                showActions();
                hideProcessing();

            } catch (error) {
                console.error('Payment confirmation error:', error);
                
                // Network error - webhook may process later
                showWarning('Unable to confirm payment status immediately. Our system will process this within a few minutes.');
                showActions();
                hideProcessing();
            }
        }

        /**
         * Show success confirmation with policy details
         */
        function showSuccessConfirmation(result, entityType) {
            const banner = document.getElementById('successBanner');
            const policyInfo = document.getElementById('policyInfo');

            banner.style.display = 'block';

            if (entityType === 'quote' && result.policy_number) {
                document.getElementById('policyNumber').textContent = result.policy_number;
                document.getElementById('confirmDate').textContent = new Date().toLocaleDateString();
                policyInfo.style.display = 'block';
                console.log('Quote payment confirmed:', result.policy_number);
            } else if (entityType === 'ai_document' && result.doc_uuid) {
                document.getElementById('policyNumber').textContent = 'Document ID: ' + result.doc_uuid.substring(0, 8) + '...';
                document.getElementById('confirmDate').textContent = new Date().toLocaleDateString();
                policyInfo.style.display = 'block';
                console.log('AI Document payment confirmed:', result.doc_uuid);
            }
        }

        /**
         * Show error message
         */
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        /**
         * Show warning message
         */
        function showWarning(message) {
            const banner = document.getElementById('successBanner');
            banner.style.backgroundColor = '#fff3cd';
            banner.style.color = '#856404';
            banner.style.borderColor = '#ffeaa7';
            banner.innerHTML = '<strong>Processing...</strong> ' + message;
            banner.style.display = 'block';
        }

        /**
         * Show message
         */
        function showMessage(message) {
            const banner = document.getElementById('successBanner');
            banner.style.backgroundColor = '#d4edda';
            banner.style.color = '#155724';
            banner.style.borderColor = '#c3e6cb';
            banner.innerHTML = '<strong>Payment Processing</strong> ' + message;
            banner.style.display = 'block';
        }

        /**
         * Hide processing message
         */
        function hideProcessing() {
            document.getElementById('processingMsg').style.display = 'none';
        }

        /**
         * Show action buttons
         */
        function showActions() {
            document.getElementById('actions').style.display = 'flex';
        }

        /**
         * Clear payment data from localStorage
         */
        function clearPaymentData() {
            try {
                localStorage.removeItem('lemonsqueezy_payment');
                console.log('Payment data cleared from localStorage');
            } catch (e) {
                console.error('Error clearing localStorage:', e);
            }
        }

        /**
         * Navigate to my account page
         */
        function goToMyAccount() {
            window.location.href = '/my-account';
        }

        /**
         * Navigate to home page
         */
        function goHome() {
            window.location.href = '/';
        }
    </script>
@endsection
