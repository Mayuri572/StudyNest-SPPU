# Create a safe template file instead
cat > config/paypal.php << 'EOF'
<?php
// Copy this file to paypal.php and fill in your credentials
// NEVER commit real credentials to GitHub

define('PAYPAL_CLIENT_ID', 'YOUR_SANDBOX_CLIENT_ID_HERE');
define('PAYPAL_SECRET',    'YOUR_SANDBOX_SECRET_HERE');
define('PAYPAL_MODE',      'sandbox');
define('PAYPAL_API_URL',   'https://api-m.sandbox.paypal.com');
define('PAYPAL_RETURN_URL','http://localhost/studynest/user/payment.php?status=success');
define('PAYPAL_CANCEL_URL','http://localhost/studynest/user/payment.php?status=cancel');
?>