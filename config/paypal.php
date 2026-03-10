<?php
// config/paypal.php
// PayPal Sandbox Credentials

define('PAYPAL_CLIENT_ID', 'AXYHAmRaBlAM0a_BGnOGA9wOi9lASQaoWGUqd4fYJttuMfAyxO65IiTdxaNcTz3g6oxXx65PnHVBsGa5');
define('PAYPAL_SECRET',    'EACkOfcR1dFuYEIi7FG0FlX5f5smq6JBsmZnklnz6UFEkBr6lPEVf6PpDjOTQ-I9NCX-2rAwSAu1QWAW');
define('PAYPAL_MODE',      'sandbox');

// API base URL
define('PAYPAL_API_URL', 'https://api-m.sandbox.paypal.com');

// Return URLs after PayPal payment
define('PAYPAL_RETURN_URL', 'http://localhost/studynest/user/payment.php?status=success');
define('PAYPAL_CANCEL_URL', 'http://localhost/studynest/user/payment.php?status=cancel');
?>