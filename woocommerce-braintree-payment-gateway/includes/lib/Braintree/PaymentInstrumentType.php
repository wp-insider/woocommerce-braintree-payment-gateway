<?php

namespace Braintree;

/**
 * PaymentInstrumentType module
 *
 * Contains constants for all payment methods that are possible to integrate with Braintree
 */
class PaymentInstrumentType
{
    const APPLE_PAY_CARD            = 'apple_pay_card';
    const CREDIT_CARD               = 'credit_card';
    const GOOGLE_PAY_CARD           = 'android_pay_card';
    const LOCAL_PAYMENT             = 'local_payment';
    const META_CHECKOUT_CARD        = 'meta_checkout_card';
    const META_CHECKOUT_TOKEN       = 'meta_checkout_token';
    const PAYPAL_ACCOUNT            = 'paypal_account';
    const PAYPAL_HERE               = 'paypal_here';
    const SAMSUNG_PAY_CARD          = 'samsung_pay_card';
    const SEPA_DIRECT_DEBIT_ACCOUNT = 'sepa_debit_account';
    const US_BANK_ACCOUNT           = 'us_bank_account';
    const VENMO_ACCOUNT             = 'venmo_account';
    const VISA_CHECKOUT_CARD        = 'visa_checkout_card';
}
