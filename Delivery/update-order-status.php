<?php
session_start();
if(!isset($_SESSION['delivery_id'])) { 
    header('location: index.php'); 
    exit(); 
}

// Include database config
if(file_exists('includes/config.php')) {
    include('includes/config.php');
} else {
    die('Configuration file not found.');
}

if(!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$order_id = intval($_GET['id']);
$delivery_id = intval($_SESSION['delivery_id']);

// Verify order belongs to this delivery and fetch order details
$check_stmt = mysqli_prepare($con, "SELECT o.id, o.orderStatus, o.quantity, o.productId, p.productPrice, p.shippingCharge,
                                           u.name as customer_name, u.email, u.contactno
                                    FROM orders o
                                    JOIN users u ON o.userId = u.id
                                    JOIN products p ON p.id = o.productId
                                    WHERE o.id = ? AND o.delivery_id = ?");
mysqli_stmt_bind_param($check_stmt, "ii", $order_id, $delivery_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);
if(mysqli_num_rows($result) == 0) { 
    header('location: dashboard.php'); 
    exit(); 
}
$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($check_stmt);

$total_amount = ($order['quantity'] * $order['productPrice']) + $order['shippingCharge'];

if(isset($_POST['update'])) {
    $status = $_POST['status'];
    $update_stmt = mysqli_prepare($con, "UPDATE orders SET orderStatus = ? WHERE id = ? AND delivery_id = ?");
    mysqli_stmt_bind_param($update_stmt, "sii", $status, $order_id, $delivery_id);
    if(mysqli_stmt_execute($update_stmt)) {
        echo "<script>alert('Order status updated successfully'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Update failed. Please try again.');</script>";
    }
    mysqli_stmt_close($update_stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Update Order Status | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
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
        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-content-wrapper {
            display: flex;
            flex: 1;
        }
        .main-content {
            flex: 1;
            padding: 20px 24px;
            background: #FCF9F5;
        }
        .form-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .info-row {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #F0E9E3;
            padding: 0.75rem 0;
        }
        .info-label {
            width: 140px;
            font-weight: 600;
            color: #4A4440;
        }
        .info-value {
            flex: 1;
            color: #2A2826;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 0.5rem;
        }
        .form-select {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
        }
        .btn-primary {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-primary:hover {
            background: #A85E44;
        }
        .btn-secondary {
            background: #E0D6CE;
            color: #2A2826;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #CEC3B9;
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
            .main-content {
                padding: 16px;
            }
            .info-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <?php include('includes/sidebar.php'); ?>
        
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Update Order Status</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Order #<?php echo $order_id; ?></li>
                    </ol>
                </nav>
            </div>

            <div class="form-card">
                <h3 class="mb-4" style="font-family: 'Instrument Serif', serif;">Order #<?php echo $order_id; ?> Details</h3>
                
                <div class="info-row">
                    <div class="info-label">Customer:</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['customer_name']); ?> (<?php echo htmlspecialchars($order['email']); ?>)</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Total Amount:</div>
                    <div class="info-value">₦<?php echo number_format($total_amount, 2); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Current Status:</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['orderStatus'] ?: 'Pending'); ?></div>
                </div>
                
                <hr>
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Out For Delivery" <?php echo ($order['orderStatus'] == 'Out For Delivery') ? 'selected' : ''; ?>>Out For Delivery</option>
                            <option value="Delivered" <?php echo ($order['orderStatus'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                        </select>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="submit" name="update" class="btn-primary">Update Status <i class="fas fa-save ms-2"></i></button>
                        <a href="dashboard.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>