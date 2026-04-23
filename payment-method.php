<?php 
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');
    exit();
}

$user_id = intval($_SESSION['id']);

// Ensure internet_banking_payments table exists
$create_table = "CREATE TABLE IF NOT EXISTS `internet_banking_payments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `order_id` int(11) NOT NULL,
    `account_number` varchar(50) NOT NULL,
    `reference` varchar(100) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `status` enum('pending','paid') DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($con, $create_table);

// Fetch user email
if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    $email_stmt = mysqli_prepare($con, "SELECT email FROM users WHERE id = ?");
    mysqli_stmt_bind_param($email_stmt, "i", $user_id);
    mysqli_stmt_execute($email_stmt);
    $email_res = mysqli_stmt_get_result($email_stmt);
    if($email_row = mysqli_fetch_assoc($email_res)) {
        $_SESSION['email'] = $email_row['email'];
    }
    mysqli_stmt_close($email_stmt);
}

// Calculate total amount of pending orders (paymentMethod IS NULL)
$total_stmt = mysqli_prepare($con, "SELECT SUM(o.quantity * p.productPrice) + SUM(p.shippingCharge) as total 
                                    FROM orders o 
                                    JOIN products p ON o.productId = p.id 
                                    WHERE o.userId = ? AND o.paymentMethod IS NULL");
mysqli_stmt_bind_param($total_stmt, "i", $user_id);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_row = mysqli_fetch_assoc($total_result);
$total_amount = $total_row['total'] ?? 0;
mysqli_stmt_close($total_stmt);

// Paystack configuration
$paystack_public_key = defined('PAYSTACK_PUBLIC_KEY') ? PAYSTACK_PUBLIC_KEY : 'pk_test_957006c55c97d329aacf1105fc181a43925b85ea';
$paystack_secret_key = defined('PAYSTACK_SECRET_KEY') ? PAYSTACK_SECRET_KEY : 'sk_test_ef5940edd5eb6f148112b18578685e50faf0e975';

if ($total_amount <= 0) {
    echo "<script>alert('Your cart is empty. Please add products first.'); window.location.href='my-cart.php';</script>";
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $paymethod = $_POST['paymethod'];
    
    if ($paymethod == 'Paystack') {
        // Paystack integration (unchanged, but will also set orderStatus later in callback)
        $reference = 'TRX_' . time() . '_' . $user_id . '_' . rand(1000, 9999);
        $callback_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/tripleashoponline/paystack-callback.php";
        
        $_SESSION['paystack_ref'] = $reference;
        $_SESSION['paystack_amount'] = $total_amount;
        
        $paystack_url = "https://api.paystack.co/transaction/initialize";
        $fields = [
            'email' => $_SESSION['email'] ?? 'customer@example.com',
            'amount' => $total_amount * 100,
            'reference' => $reference,
            'callback_url' => $callback_url
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paystack_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $paystack_secret_key",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        if (isset($result['data']['authorization_url'])) {
            header('Location: ' . $result['data']['authorization_url']);
            exit();
        } else {
            echo "<script>alert('Paystack initialization failed. Please try again.'); window.location.href='payment-method.php';</script>";
            exit();
        }
    } 
    elseif ($paymethod == 'Internet Banking') {
        // Generate unique account number and reference
        $reference = 'INB_' . time() . '_' . $user_id . '_' . rand(1000, 9999);
        $account_number = 'ACC' . time() . rand(1000, 9999);
        
        // Get the order IDs that are pending (all rows for this user with NULL paymentMethod)
        $order_ids = [];
        $order_query = mysqli_prepare($con, "SELECT id FROM orders WHERE userId = ? AND paymentMethod IS NULL");
        mysqli_stmt_bind_param($order_query, "i", $user_id);
        mysqli_stmt_execute($order_query);
        $order_res = mysqli_stmt_get_result($order_query);
        while($ord = mysqli_fetch_assoc($order_res)) {
            $order_ids[] = $ord['id'];
        }
        mysqli_stmt_close($order_query);
        
        if(empty($order_ids)) {
            echo "<script>alert('No pending orders found.'); window.location.href='my-cart.php';</script>";
            exit();
        }
        
        // Update all pending orders with Internet Banking payment method and status
        $update_stmt = mysqli_prepare($con, "UPDATE orders SET paymentMethod = 'Internet Banking', orderStatus = 'Awaiting Transfer' WHERE userId = ? AND paymentMethod IS NULL");
        mysqli_stmt_bind_param($update_stmt, "i", $user_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
        
        // For each order, insert a record into internet_banking_payments (using the first order_id as representative)
        $first_order_id = $order_ids[0];
        $insert_stmt = mysqli_prepare($con, "INSERT INTO internet_banking_payments (user_id, order_id, account_number, reference, amount, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        mysqli_stmt_bind_param($insert_stmt, "iissd", $user_id, $first_order_id, $account_number, $reference, $total_amount);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
        
        // Clear cart
        unset($_SESSION['cart']);
        
        // Display account number to user
        echo "<script>
            alert('Please transfer ₦" . number_format($total_amount, 2) . " to the following account number:\\n\\nAccount Number: $account_number\\nBank: Triple A Bank\\nReference: $reference\\n\\nYour order will be processed once payment is confirmed.');
            window.location.href='order-history.php';
        </script>";
        exit();
    } 
    elseif ($paymethod == 'Debit / Credit card') {
        // Redirect to card payment simulation page
        $_SESSION['card_payment_amount'] = $total_amount;
        $_SESSION['card_payment_user_id'] = $user_id;
        header('location: card-payment.php');
        exit();
    }
    else {
        // COD, Internet Banking (already handled), etc.
        $update_stmt = mysqli_prepare($con, "UPDATE orders SET paymentMethod = ? WHERE userId = ? AND paymentMethod IS NULL");
        mysqli_stmt_bind_param($update_stmt, "si", $paymethod, $user_id);
        if (mysqli_stmt_execute($update_stmt)) {
            unset($_SESSION['cart']);
            echo "<script>alert('Order placed successfully!'); window.location.href='order-history.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error placing order. Please try again.');</script>";
        }
        mysqli_stmt_close($update_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Payment Method | Triple A ShopOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .payment-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 600px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
        }
        .payment-card h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #C47A5E;
            padding-left: 1rem;
        }
        .total-amount {
            background: #F5F0EB;
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
        }
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #E0D6CE;
            border-radius: 40px;
            transition: 0.2s;
            cursor: pointer;
        }
        .radio-option:hover {
            border-color: #C47A5E;
            background: #FCF9F5;
        }
        .radio-option input[type="radio"] {
            accent-color: #C47A5E;
            width: 1.2rem;
            height: 1.2rem;
            margin: 0;
        }
        .radio-option label {
            flex: 1;
            font-weight: 500;
            margin: 0;
            cursor: pointer;
            color: #4A4440;
        }
        .btn-submit {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: 0.2s;
        }
        .btn-submit:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .payment-card {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb" class="mt-4">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="my-cart.php">My Cart</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payment Method</li>
                </ol>
            </nav>

            <div class="payment-card">
                <h2><i class="fas fa-credit-card"></i> Choose Payment Method</h2>
                <div class="total-amount">
                    Total Amount to Pay: <strong>₦<?php echo number_format($total_amount, 2); ?></strong>
                </div>
                <form name="payment" method="post">
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="paymethod" value="COD" id="cod" checked>
                            <label for="cod">Cash on Delivery (COD)</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="paymethod" value="Internet Banking" id="internetBanking">
                            <label for="internetBanking">Internet Banking</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="paymethod" value="Debit / Credit card" id="card">
                            <label for="card">Debit / Credit Card</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="paymethod" value="Paystack" id="paystack">
                            <label for="paystack"><i class="fab fa-paystack"></i> Paystack (Card, Bank Transfer, USSD)</label>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn-submit">Place Order <i class="fas fa-arrow-right ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>
    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.radio-option').forEach(option => {
        option.addEventListener('click', function(e) {
            const radio = this.querySelector('input[type="radio"]');
            if(radio && e.target !== radio) {
                radio.checked = true;
            }
        });
    });
</script>
</body>
</html>