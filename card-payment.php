<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');
    exit();
}

$user_id = intval($_SESSION['id']);
$amount = $_SESSION['card_payment_amount'] ?? 0;
if($amount <= 0) {
    header('location: payment-method.php');
    exit();
}

// Process card payment simulation
if(isset($_POST['submit_card'])) {
    $card_number = preg_replace('/\s+/', '', $_POST['card_number']);
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    $otp = $_POST['otp'] ?? '';
    
    // Simulate OTP validation (in real world, you would send OTP to user's phone)
    if(empty($otp)) {
        // First step: show OTP input
        $show_otp = true;
        $message = "An OTP has been sent to your registered mobile number. Please enter it to complete payment.";
    } else {
        // Validate OTP (for simulation, accept any 6-digit number)
        if(preg_match('/^\d{6}$/', $otp)) {
            // Update orders
            $update_stmt = mysqli_prepare($con, "UPDATE orders SET paymentMethod = 'Debit/Credit Card', orderStatus = 'Paid via Debit/Credit Card' WHERE userId = ? AND paymentMethod IS NULL");
            mysqli_stmt_bind_param($update_stmt, "i", $user_id);
            if(mysqli_stmt_execute($update_stmt)) {
                unset($_SESSION['cart']);
                unset($_SESSION['card_payment_amount']);
                echo "<script>alert('Payment successful! Your order has been placed.'); window.location.href='order-history.php';</script>";
                exit();
            } else {
                $error = "Payment verification failed. Please try again.";
            }
            mysqli_stmt_close($update_stmt);
        } else {
            $error = "Invalid OTP. Please enter the 6-digit code sent to your phone.";
            $show_otp = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Payment | Triple A ShopOnline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FCF9F5; }
        .payment-container { max-width: 500px; margin: 50px auto; }
        .card { border-radius: 28px; border: 1px solid #EFE8E2; background: white; padding: 2rem; }
        .btn-pay { background: #C47A5E; border-radius: 40px; padding: 0.75rem; color: white; width: 100%; border: none; font-weight: 600; }
        .form-control { border-radius: 20px; border: 1px solid #E0D6CE; padding: 0.6rem 1rem; }
    </style>
</head>
<body>
<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="payment-container">
        <div class="card">
            <h3 class="mb-4">Debit / Credit Card Payment</h3>
            <div class="total-amount mb-3">Amount to Pay: ₦<?php echo number_format($amount, 2); ?></div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(isset($message)): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <form method="post">
                <?php if(!isset($show_otp)): ?>
                    <div class="mb-3">
                        <label>Card Number</label>
                        <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Expiry (MM/YY)</label>
                            <input type="text" name="expiry" class="form-control" placeholder="MM/YY" required>
                        </div>
                        <div class="col-6">
                            <label>CVV</label>
                            <input type="text" name="cvv" class="form-control" placeholder="123" required>
                        </div>
                    </div>
                    <button type="submit" name="submit_card" class="btn-pay">Verify & Pay</button>
                <?php else: ?>
                    <div class="mb-3">
                        <label>OTP (6-digit code)</label>
                        <input type="text" name="otp" class="form-control" placeholder="123456" required>
                    </div>
                    <input type="hidden" name="card_number" value="<?php echo htmlspecialchars($card_number ?? ''); ?>">
                    <input type="hidden" name="expiry" value="<?php echo htmlspecialchars($expiry ?? ''); ?>">
                    <input type="hidden" name="cvv" value="<?php echo htmlspecialchars($cvv ?? ''); ?>">
                    <button type="submit" name="submit_card" class="btn-pay">Confirm Payment</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>