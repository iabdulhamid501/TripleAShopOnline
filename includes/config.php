<?php
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'shopping');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Paystack API Keys (replace with your live/test keys)
define('PAYSTACK_PUBLIC_KEY', 'pk_test_957006c55c97d329aacf1105fc181a43925b85ea');
define('PAYSTACK_SECRET_KEY', 'sk_test_ef5940edd5eb6f148112b18578685e50faf0e975');
?>
