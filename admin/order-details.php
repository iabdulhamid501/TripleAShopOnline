<?php
session_start();
include('includes/config.php');

// Use correct admin session variable
if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

date_default_timezone_set('Asia/Kolkata');
$currentTime = date('d-m-Y h:i:s A', time());

// Get order ID from URL
$orderid = isset($_GET['oid']) ? intval($_GET['oid']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Order Details | Triple A ShopOnline</title>
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
        .detail-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .info-row {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #F0E9E3;
            padding: 0.75rem 0;
        }
        .info-label {
            width: 180px;
            font-weight: 600;
            color: #4A4440;
        }
        .info-value {
            flex: 1;
            color: #2A2826;
        }
        .section-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 12px;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }
        .table-custom th,
        .table-custom td {
            padding: 10px 8px;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .table-custom th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
        }
        /* Prevent any hover movement */
        .table-custom tr {
            transition: none;
            transform: none;
        }
        .table-custom tr:hover {
            background-color: transparent;
            transform: none;
        }
        .table-custom td img {
            display: block;
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            transition: none;
            transform: none;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .btn-action {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-action:hover {
            background: #A85E44;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .container-fluid.px-0 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .row.g-0 {
            margin-left: 0;
            margin-right: 0;
        }
        [class*="col-"] {
            padding-left: 0;
            padding-right: 0;
        }
        @media (max-width: 768px) {
            .detail-card {
                margin: 0 12px 1.5rem;
            }
            .info-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2">
            <?php include('includes/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Order Details #<?php echo $orderid; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="all-orders.php">All Orders</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Order #<?php echo $orderid; ?></li>
                        </ol>
                    </nav>
                </div>

                <?php
                if($orderid == 0) {
                    echo '<div class="detail-card text-center"><p class="text-danger">Invalid order ID.</p><a href="all-orders.php" class="btn-action">Back to Orders</a></div>';
                } else {
                    // Check if order exists
                    $check_sql = "SELECT COUNT(*) as cnt FROM orders WHERE id = ?";
                    $check_stmt = mysqli_prepare($con, $check_sql);
                    mysqli_stmt_bind_param($check_stmt, "i", $orderid);
                    mysqli_stmt_execute($check_stmt);
                    $check_res = mysqli_stmt_get_result($check_stmt);
                    $check_row = mysqli_fetch_assoc($check_res);
                    mysqli_stmt_close($check_stmt);
                    
                    if($check_row['cnt'] == 0) {
                        echo '<div class="detail-card text-center"><p class="text-danger">Order #' . $orderid . ' does not exist.</p><a href="all-orders.php" class="btn-action">Back to Orders</a></div>';
                    } else {
                        // ========== FETCH USER ID FROM ORDER ==========
                        $user_id_stmt = mysqli_prepare($con, "SELECT userId FROM orders WHERE id = ?");
                        mysqli_stmt_bind_param($user_id_stmt, "i", $orderid);
                        mysqli_stmt_execute($user_id_stmt);
                        $user_id_res = mysqli_stmt_get_result($user_id_stmt);
                        $user_id_row = mysqli_fetch_assoc($user_id_res);
                        $user_id = $user_id_row['userId'];
                        mysqli_stmt_close($user_id_stmt);
                        
                        // ========== FETCH ADDRESS FROM ADDRESSES TABLE (MOST RECENT) ==========
                        $shippingAddr = $shippingCity = $shippingState = $shippingPincode = $shippingCountry = '';
                        $billingAddr = $billingCity = $billingState = $billingPincode = $billingCountry = '';
                        
                        $addr_stmt = mysqli_prepare($con, "SELECT shippingAddress, shippingCity, shippingState, shippingPincode, shippingCountry,
                                                                  billingAddress, billingCity, billingState, billingPincode, billingCountry
                                                           FROM addresses 
                                                           WHERE userId = ? 
                                                           ORDER BY id DESC LIMIT 1");
                        mysqli_stmt_bind_param($addr_stmt, "i", $user_id);
                        mysqli_stmt_execute($addr_stmt);
                        $addr_res = mysqli_stmt_get_result($addr_stmt);
                        if($addr_row = mysqli_fetch_assoc($addr_res)) {
                            // Use address from addresses table
                            $shippingAddr = $addr_row['shippingAddress'];
                            $shippingCity = $addr_row['shippingCity'];
                            $shippingState = $addr_row['shippingState'];
                            $shippingPincode = $addr_row['shippingPincode'];
                            $shippingCountry = $addr_row['shippingCountry'];
                            $billingAddr = $addr_row['billingAddress'];
                            $billingCity = $addr_row['billingCity'];
                            $billingState = $addr_row['billingState'];
                            $billingPincode = $addr_row['billingPincode'];
                            $billingCountry = $addr_row['billingCountry'];
                        }
                        mysqli_stmt_close($addr_stmt);
                        
                        // If no address in addresses table, fallback to users table
                        if(empty($shippingAddr) && empty($billingAddr)) {
                            $user_stmt = mysqli_prepare($con, "SELECT shippingAddress, shippingCity, shippingState, shippingPincode,
                                                                      billingAddress, billingCity, billingState, billingPincode
                                                               FROM users WHERE id = ?");
                            mysqli_stmt_bind_param($user_stmt, "i", $user_id);
                            mysqli_stmt_execute($user_stmt);
                            $user_res = mysqli_stmt_get_result($user_stmt);
                            if($user_row = mysqli_fetch_assoc($user_res)) {
                                $shippingAddr = $user_row['shippingAddress'];
                                $shippingCity = $user_row['shippingCity'];
                                $shippingState = $user_row['shippingState'];
                                $shippingPincode = $user_row['shippingPincode'];
                                $billingAddr = $user_row['billingAddress'];
                                $billingCity = $user_row['billingCity'];
                                $billingState = $user_row['billingState'];
                                $billingPincode = $user_row['billingPincode'];
                            }
                            mysqli_stmt_close($user_stmt);
                        }
                        
                        // Helper function to format address with optional country
                        function formatAddress($addr, $city, $state, $pincode, $country = '') {
                            $parts = [];
                            if(!empty($addr)) $parts[] = $addr;
                            if(!empty($city)) $parts[] = $city;
                            if(!empty($state)) $parts[] = $state;
                            if(!empty($pincode)) $parts[] = $pincode;
                            if(!empty($country)) $parts[] = $country;
                            return implode(', ', $parts);
                        }
                        
                        // Fetch order & customer info (basic info)
                        $sql_customer = "SELECT DISTINCT o.id as oid, o.orderDate, o.orderStatus, u.name as username, 
                                                u.email as useremail, u.contactno as usercontact
                                         FROM orders o
                                         JOIN users u ON o.userId = u.id
                                         WHERE o.id = ?";
                        $stmt = mysqli_prepare($con, $sql_customer);
                        mysqli_stmt_bind_param($stmt, "i", $orderid);
                        mysqli_stmt_execute($stmt);
                        $customer_res = mysqli_stmt_get_result($stmt);
                        
                        if(mysqli_num_rows($customer_res) == 0) {
                            echo '<div class="detail-card text-center"><p class="text-danger">Customer information not found for this order.</p><a href="all-orders.php" class="btn-action">Back to Orders</a></div>';
                        } else {
                            $order = mysqli_fetch_assoc($customer_res);
                            mysqli_stmt_close($stmt);
                            
                            // Fetch all products in this order
                            $sql_products = "SELECT o.quantity, o.orderStatus, p.id as pid, p.productName, p.productPrice, 
                                                    p.shippingCharge, p.productImage1
                                             FROM orders o
                                             JOIN products p ON o.productId = p.id
                                             WHERE o.id = ?";
                            $stmt_prod = mysqli_prepare($con, $sql_products);
                            mysqli_stmt_bind_param($stmt_prod, "i", $orderid);
                            mysqli_stmt_execute($stmt_prod);
                            $products_res = mysqli_stmt_get_result($stmt_prod);
                            
                            if(mysqli_num_rows($products_res) == 0) {
                                echo '<div class="detail-card text-center"><p class="text-warning">This order has no products (possible data inconsistency).</p><a href="all-orders.php" class="btn-action">Back to Orders</a></div>';
                            } else {
                ?>
                <!-- Order & Customer Information -->
                <div class="detail-card">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Order & Customer Info</h3>
                    <div class="info-row"><div class="info-label">Order ID:</div><div class="info-value"><?php echo htmlspecialchars($order['oid']); ?></div></div>
                    <div class="info-row"><div class="info-label">Order Date:</div><div class="info-value"><?php echo htmlspecialchars($order['orderDate']); ?></div></div>
                    <div class="info-row"><div class="info-label">Order Status:</div><div class="info-value"><span class="badge-status" style="background:#FFEAD2; color:#C47A5E;"><?php echo htmlspecialchars($order['orderStatus'] ?: 'Pending'); ?></span></div></div>
                    <div class="info-row"><div class="info-label">Customer Name:</div><div class="info-value"><?php echo htmlspecialchars($order['username']); ?></div></div>
                    <div class="info-row"><div class="info-label">Email / Contact:</div><div class="info-value"><?php echo htmlspecialchars($order['useremail']); ?> / <?php echo htmlspecialchars($order['usercontact']); ?></div></div>
                </div>

                <!-- Shipping & Billing Addresses (now correctly fetched) -->
                <div class="detail-card">
                    <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Addresses</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Shipping Address</strong><br>
                            <?php 
                            $shippingDisplay = formatAddress($shippingAddr, $shippingCity, $shippingState, $shippingPincode, $shippingCountry);
                            if(!empty($shippingDisplay)) {
                                echo nl2br(htmlspecialchars($shippingDisplay));
                            } else {
                                echo '<span class="text-muted">No shipping address provided</span>';
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Billing Address</strong><br>
                            <?php 
                            $billingDisplay = formatAddress($billingAddr, $billingCity, $billingState, $billingPincode, $billingCountry);
                            if(!empty($billingDisplay)) {
                                echo nl2br(htmlspecialchars($billingDisplay));
                            } else {
                                echo '<span class="text-muted">No billing address provided</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Product Details (with working images) -->
                <div class="detail-card">
                    <h3 class="section-title"><i class="fas fa-box"></i> Product Details</h3>
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Image</th><th>Product Name</th><th>Price (₦)</th><th>Qty</th><th>Shipping (₦)</th><th>Subtotal (₦)</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $grand_total = 0;
                                $total_shipping = 0;
                                $placeholder_base = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Crect width='60' height='60' fill='%23F5F0EB'/%3E%3Ctext x='50%25' y='50%25' font-size='10' fill='%23AFA49B' text-anchor='middle' dy='.3em'%3ENo img%3C/text%3E%3C/svg%3E";
                                
                                while($prod = mysqli_fetch_assoc($products_res)) {
                                    $subtotal = ($prod['quantity'] * $prod['productPrice']);
                                    $grand_total += $subtotal;
                                    $total_shipping += $prod['shippingCharge'];
                                    
                                    $image_path = "productimages/" . $prod['pid'] . "/" . $prod['productImage1'];
                                    if(empty($prod['productImage1']) || !file_exists($image_path)) {
                                        $image_src = $placeholder_base;
                                    } else {
                                        $image_src = $image_path;
                                    }
                                ?>
                                <tr>
                                    <td><img src="<?php echo $image_src; ?>" width="60" height="60" alt="Product Image" onerror="this.src='<?php echo $placeholder_base; ?>'" loading="lazy"></td>
                                    <td><?php echo htmlspecialchars($prod['productName']); ?></td>
                                    <td>₦<?php echo number_format($prod['productPrice'], 2); ?></td>
                                    <td><?php echo $prod['quantity']; ?></td>
                                    <td>₦<?php echo number_format($prod['shippingCharge'], 2); ?></td>
                                    <td>₦<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <?php } ?>
                                <tr style="font-weight: bold; background: #FCF9F5;">
                                    <td colspan="4"> </td>
                                    <td>Total Shipping:</td>
                                    <td>₦<?php echo number_format($total_shipping, 2); ?></td>
                                </tr>
                                <tr style="font-weight: bold; background: #FCF9F5;">
                                    <td colspan="4"> </td>
                                    <td>Grand Total:</td>
                                    <td>₦<?php echo number_format($grand_total + $total_shipping, 2); ?></td>
                                 </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order History -->
                <?php
                $hist_sql = "SELECT * FROM ordertrackhistory WHERE orderId = ? ORDER BY postingDate DESC";
                $hist_stmt = mysqli_prepare($con, $hist_sql);
                mysqli_stmt_bind_param($hist_stmt, "i", $orderid);
                mysqli_stmt_execute($hist_stmt);
                $hist_res = mysqli_stmt_get_result($hist_stmt);
                if(mysqli_num_rows($hist_res) > 0) {
                ?>
                <div class="detail-card">
                    <h3 class="section-title"><i class="fas fa-history"></i> Order History</h3>
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead><tr><th>Remark</th><th>Status</th><th>Date</th></thead>
                            <tbody>
                                <?php while($hrow = mysqli_fetch_assoc($hist_res)) { ?>
                                <td>
                                    <td><?php echo nl2br(htmlspecialchars($hrow['remark'])); ?></td>
                                    <td><span class="badge-status" style="background:#F5F0EB; color:#C47A5E;"><?php echo htmlspecialchars($hrow['status']); ?></span></td>
                                    <td><?php echo htmlspecialchars($hrow['postingDate']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }
                mysqli_stmt_close($hist_stmt);
                mysqli_stmt_close($stmt_prod); ?>

                <!-- Action Button -->
                <div class="text-center mb-4">
                    <a href="updateorder.php?oid=<?php echo $orderid; ?>" class="btn-action">
                        <i class="fas fa-edit"></i> Take Action / Update Order
                    </a>
                </div>

                <?php } // end products exist
                } // end customer info exists
                } // end order exists
                } // end valid orderid ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>