<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');
    exit();
}

$user_id = intval($_SESSION['id']);
$reference = $_GET['reference'] ?? $_POST['reference'] ?? '';

if (empty($reference)) {
    header('location: my-cart.php');
    exit();
}

// Verify transaction with Paystack
$paystack_secret_key = defined('PAYSTACK_SECRET_KEY') ? PAYSTACK_SECRET_KEY : 'sk_test_ef5940edd5eb6f148112b18578685e50faf0e975';
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $paystack_secret_key",
        "Content-Type: application/json"
    ]
]);
$response = curl_exec($curl);
curl_close($curl);

$result = json_decode($response, true);

if ($result['status'] && $result['data']['status'] == 'success') {
    // Payment verified – update all pending orders for this user
    // Set paymentMethod = 'Paystack' AND orderStatus = 'Processing'
    $update_stmt = mysqli_prepare($con, "UPDATE orders SET paymentMethod = 'Paystack', orderStatus = 'Processing' WHERE userId = ? AND paymentMethod IS NULL");
    mysqli_stmt_bind_param($update_stmt, "i", $user_id);
    if (mysqli_stmt_execute($update_stmt)) {
        unset($_SESSION['cart']);
        unset($_SESSION['paystack_ref']);
        echo "<script>alert('Payment successful! Your order has been placed.'); window.location.href='order-history.php';</script>";
        exit();
    } else {
        echo "<script>alert('Payment verified but order update failed. Please contact support.'); window.location.href='my-cart.php';</script>";
    }
    mysqli_stmt_close($update_stmt);
} else {
    // Payment failed or cancelled
    echo "<script>alert('Payment was not successful. Please try again.'); window.location.href='payment-method.php';</script>";
    exit();
}
?>