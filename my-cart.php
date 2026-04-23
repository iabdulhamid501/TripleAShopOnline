<?php 
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect if user not logged in for actions that need it
if(!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0){
    // Allow viewing cart, but checkout will require login
    $logged_in = false;
} else {
    $logged_in = true;
    $user_id = intval($_SESSION['id']);
}

// ========== UPDATE CART QUANTITIES ==========
if(isset($_POST['submit']) && isset($_POST['quantity'])){
    foreach($_POST['quantity'] as $key => $val){
        $key = intval($key);
        $val = intval($val);
        if($val <= 0){
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key]['quantity'] = $val;
        }
    }
    echo "<script>alert('Your Cart has been Updated');</script>";
}

// ========== REMOVE SELECTED PRODUCTS ==========
if(isset($_POST['remove_code']) && !empty($_POST['remove_code'])){
    foreach($_POST['remove_code'] as $remove_id){
        $remove_id = intval($remove_id);
        unset($_SESSION['cart'][$remove_id]);
    }
    echo "<script>alert('Selected items have been removed');</script>";
}

// ========== UPDATE BILLING ADDRESS ==========
if(isset($_POST['update']) && $logged_in){
    $baddress = trim($_POST['billingaddress']);
    $bstate = trim($_POST['bilingstate']);
    $bcity = trim($_POST['billingcity']);
    $bpincode = trim($_POST['billingpincode']);
    $update_stmt = mysqli_prepare($con, "UPDATE users SET billingAddress=?, billingState=?, billingCity=?, billingPincode=? WHERE id=?");
    mysqli_stmt_bind_param($update_stmt, "ssssi", $baddress, $bstate, $bcity, $bpincode, $user_id);
    if(mysqli_stmt_execute($update_stmt)){
        echo "<script>alert('Billing Address has been updated');</script>";
    }
    mysqli_stmt_close($update_stmt);
}

// ========== UPDATE SHIPPING ADDRESS ==========
if(isset($_POST['shipupdate']) && $logged_in){
    $saddress = trim($_POST['shippingaddress']);
    $sstate = trim($_POST['shippingstate']);
    $scity = trim($_POST['shippingcity']);
    $spincode = trim($_POST['shippingpincode']);
    $update_stmt = mysqli_prepare($con, "UPDATE users SET shippingAddress=?, shippingState=?, shippingCity=?, shippingPincode=? WHERE id=?");
    mysqli_stmt_bind_param($update_stmt, "ssssi", $saddress, $sstate, $scity, $spincode, $user_id);
    if(mysqli_stmt_execute($update_stmt)){
        echo "<script>alert('Shipping Address has been updated');</script>";
    }
    mysqli_stmt_close($update_stmt);
}

// ========== PROCEED TO CHECKOUT (INSERT ORDERS & SAVE ADDRESS) ==========
if(isset($_POST['ordersubmit'])){
    if(!$logged_in){
        header('location: login.php');
        exit();
    }
    if(empty($_SESSION['cart'])){
        echo "<script>alert('Your cart is empty'); window.location.href='index.php';</script>";
        exit();
    }
    
    // Get the current billing and shipping addresses from the form (they were submitted)
    $billing_address = trim($_POST['billingaddress'] ?? '');
    $billing_state = trim($_POST['bilingstate'] ?? '');
    $billing_city = trim($_POST['billingcity'] ?? '');
    $billing_pincode = trim($_POST['billingpincode'] ?? '');
    $shipping_address = trim($_POST['shippingaddress'] ?? '');
    $shipping_state = trim($_POST['shippingstate'] ?? '');
    $shipping_city = trim($_POST['shippingcity'] ?? '');
    $shipping_pincode = trim($_POST['shippingpincode'] ?? '');
    $country = 'Nigeria'; // default country
    
    // Insert the address into the addresses table (creates a new record for each checkout)
    $addr_stmt = mysqli_prepare($con, "INSERT INTO addresses (userId, billingAddress, billingCity, billingState, billingPincode, billingCountry, shippingAddress, shippingCity, shippingState, shippingPincode, shippingCountry) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($addr_stmt, "issssssssss", $user_id, $billing_address, $billing_city, $billing_state, $billing_pincode, $country, $shipping_address, $shipping_city, $shipping_state, $shipping_pincode, $country);
    if(!mysqli_stmt_execute($addr_stmt)){
        echo "<script>alert('Failed to save address. Please try again.'); window.location.href='my-cart.php';</script>";
        exit();
    }
    mysqli_stmt_close($addr_stmt);
    
    $order_inserted = true;
    foreach($_SESSION['cart'] as $pid => $details){
        $pid = intval($pid);
        $qty = intval($details['quantity']);
        // Insert each product as a separate order row
        $insert_stmt = mysqli_prepare($con, "INSERT INTO orders(userId, productId, quantity, orderDate) VALUES(?, ?, ?, NOW())");
        mysqli_stmt_bind_param($insert_stmt, "iii", $user_id, $pid, $qty);
        if(!mysqli_stmt_execute($insert_stmt)){
            $order_inserted = false;
            break;
        }
        mysqli_stmt_close($insert_stmt);
    }
    if($order_inserted){
        // Clear cart after successful order placement
        unset($_SESSION['cart']);
        echo "<script>alert('Order placed successfully!'); window.location.href='payment-method.php';</script>";
        exit();
    } else {
        echo "<script>alert('There was an error processing your order. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Shopping Cart - Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Shopping Cart | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FCF9F5;
            color: #2A2826;
            line-height: 1.5;
        }
        h1, h2, h3, h4, .cart-title {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .veloria-breadcrumb {
            background: transparent;
            padding: 16px 0;
            margin: 0 0 24px;
        }
        .veloria-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.85rem;
        }
        .veloria-breadcrumb li a {
            color: #C47A5E;
            text-decoration: none;
        }
        .veloria-breadcrumb li.active {
            color: #7A726C;
        }
        .cart-table-wrapper {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 20px;
            margin-bottom: 32px;
            overflow-x: auto;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table th, .cart-table td {
            padding: 16px 12px;
            text-align: center;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .cart-table th {
            font-weight: 600;
            color: #4A4440;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cart-product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
        }
        .product-name {
            font-weight: 600;
            color: #2A2826;
            text-decoration: none;
        }
        .product-name:hover {
            color: #C47A5E;
        }
        .quant-input {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F5F0EB;
            border-radius: 40px;
            padding: 4px 12px;
        }
        .quant-input input {
            width: 50px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 1rem;
        }
        .arrows {
            display: flex;
            flex-direction: column;
        }
        .arrow {
            cursor: pointer;
            font-size: 0.7rem;
            color: #C47A5E;
            line-height: 1;
        }
        .btn-remove {
            background: none;
            border: none;
            color: #C47A5E;
            cursor: pointer;
            font-size: 1.2rem;
        }
        .btn-remove:hover {
            color: #A85E44;
        }
        .address-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 24px;
            margin-bottom: 24px;
        }
        .address-card h4 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 6px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 6px;
            display: block;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 0.9rem;
            border: 1px solid #E0D6CE;
            border-radius: 16px;
            background: #FFFFFF;
            transition: 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-update {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-update:hover {
            background: #A85E44;
        }
        .grand-total-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 24px;
            text-align: right;
        }
        .grand-total {
            font-size: 1.5rem;
            font-weight: 700;
            color: #C47A5E;
        }
        .btn-checkout {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 12px 28px;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 16px;
        }
        .btn-checkout:hover {
            background: #A85E44;
        }
        .empty-cart {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 24px;
            font-size: 1.2rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .cart-table th, .cart-table td {
                padding: 10px 6px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body oncontextmenu="return false;">

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active">Shopping Cart</li>
        </ul>
    </div>

    <?php if(!empty($_SESSION['cart'])): ?>
    <form name="cart" method="post">
        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Remove</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price (₦)</th>
                        <th>Shipping (₦)</th>
                        <th>Total (₦)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $cart_ids = array_keys($_SESSION['cart']);
                if(!empty($cart_ids)){
                    $in_query = implode(',', array_fill(0, count($cart_ids), '?'));
                    $sql = "SELECT * FROM products WHERE id IN ($in_query) ORDER BY id ASC";
                    $stmt = mysqli_prepare($con, $sql);
                    $types = str_repeat('i', count($cart_ids));
                    mysqli_stmt_bind_param($stmt, $types, ...$cart_ids);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $totalprice = 0;
                    $totalqunty = 0;
                    while($row = mysqli_fetch_assoc($result)){
                        $pid = $row['id'];
                        $quantity = $_SESSION['cart'][$pid]['quantity'];
                        $subtotal = ($quantity * $row['productPrice']) + $row['shippingCharge'];
                        $totalprice += $subtotal;
                        $totalqunty += $quantity;
                        // Image path with fallback
                        $img_path = "admin/productimages/".$pid."/".$row['productImage1'];
                        if(!file_exists($img_path) || empty($row['productImage1'])){
                            $img_path = "admin/productimages/placeholder.jpg";
                        }
                ?>
                    <tr>
                        <td><input type="checkbox" name="remove_code[]" value="<?php echo $pid; ?>" class="form-check-input"></td>
                        <td><img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>" class="cart-product-img"></td>
                        <td><a href="product-details.php?pid=<?php echo $pid; ?>" class="product-name"><?php echo htmlspecialchars($row['productName']); ?></a></td>
                        <td>
                            <div class="quant-input">
                                <div class="arrows">
                                    <div class="arrow plus"><i class="fas fa-chevron-up"></i></div>
                                    <div class="arrow minus"><i class="fas fa-chevron-down"></i></div>
                                </div>
                                <input type="text" name="quantity[<?php echo $pid; ?>]" value="<?php echo $quantity; ?>">
                            </div>
                         </td>
                        <td><?php echo number_format($row['productPrice'], 2); ?></td>
                        <td><?php echo number_format($row['shippingCharge'], 2); ?></td>
                        <td><strong><?php echo number_format($subtotal, 2); ?></strong></td>
                    </tr>
                <?php 
                    }
                    mysqli_stmt_close($stmt);
                    $_SESSION['total_qty'] = $totalqunty; // store for later use
                } ?>
                </tbody>
            </table>
        </div>

        <div class="row g-4">
            <!-- Action Buttons -->
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap gap-3">
                    <a href="index.php" class="btn-update" style="background:#E0D6CE; color:#2A2826; text-decoration:none; padding:10px 24px;">Continue Shopping</a>
                    <input type="submit" name="submit" value="Update Cart" class="btn-update">
                </div>
            </div>

            <!-- Billing & Shipping Addresses (only for logged-in users) -->
            <?php if($logged_in): 
                $user_stmt = mysqli_prepare($con, "SELECT * FROM users WHERE id = ?");
                mysqli_stmt_bind_param($user_stmt, "i", $user_id);
                mysqli_stmt_execute($user_stmt);
                $user_result = mysqli_stmt_get_result($user_stmt);
                $user_row = mysqli_fetch_assoc($user_result);
                mysqli_stmt_close($user_stmt);
            ?>
            <div class="col-md-6">
                <div class="address-card">
                    <h4><i class="fas fa-file-invoice"></i> Billing Address</h4>
                    <div class="form-group">
                        <label>Billing Address <span>*</span></label>
                        <textarea class="form-control" name="billingaddress" required><?php echo htmlspecialchars($user_row['billingAddress']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>State <span>*</span></label>
                        <input type="text" class="form-control" name="bilingstate" value="<?php echo htmlspecialchars($user_row['billingState']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>City <span>*</span></label>
                        <input type="text" class="form-control" name="billingcity" value="<?php echo htmlspecialchars($user_row['billingCity']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Pincode <span>*</span></label>
                        <input type="text" class="form-control" name="billingpincode" value="<?php echo htmlspecialchars($user_row['billingPincode']); ?>" required>
                    </div>
                    <button type="submit" name="update" class="btn-update">Update Billing</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="address-card">
                    <h4><i class="fas fa-truck"></i> Shipping Address</h4>
                    <div class="form-group">
                        <label>Shipping Address <span>*</span></label>
                        <textarea class="form-control" name="shippingaddress" required><?php echo htmlspecialchars($user_row['shippingAddress']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>State <span>*</span></label>
                        <input type="text" class="form-control" name="shippingstate" value="<?php echo htmlspecialchars($user_row['shippingState']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>City <span>*</span></label>
                        <input type="text" class="form-control" name="shippingcity" value="<?php echo htmlspecialchars($user_row['shippingCity']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Pincode <span>*</span></label>
                        <input type="text" class="form-control" name="shippingpincode" value="<?php echo htmlspecialchars($user_row['shippingPincode']); ?>" required>
                    </div>
                    <button type="submit" name="shipupdate" class="btn-update">Update Shipping</button>
                </div>
            </div>
            <?php else: ?>
            <div class="col-12">
                <div class="address-card text-center">
                    <p><a href="login.php" class="btn-update">Login</a> to update your billing and shipping addresses.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Grand Total & Checkout -->
            <div class="col-md-12">
                <div class="grand-total-card">
                    <div>Grand Total: <span class="grand-total">₦<?php echo number_format($totalprice, 2); ?></span></div>
                    <?php if($logged_in): ?>
                    <button type="submit" name="ordersubmit" class="btn-checkout">Proceed to Checkout →</button>
                    <?php else: ?>
                    <a href="login.php" class="btn-checkout" style="display:inline-block; text-align:center; text-decoration:none;">Login to Checkout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
    <?php else: ?>
    <div class="empty-cart">
        <i class="fas fa-shopping-cart" style="font-size: 3rem; color:#C47A5E; margin-bottom: 16px; display:block;"></i>
        Your shopping cart is empty.<br>
        <a href="index.php" class="btn-update" style="display:inline-block; margin-top:20px;">Continue Shopping</a>
    </div>
    <?php endif; ?>

    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/echo.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script>
    $(document).ready(function(){
        if($('#brand-slider').length) {
            $("#brand-slider").owlCarousel({
                autoPlay: 3000,
                items: 5,
                itemsDesktop: [1199, 4],
                itemsDesktopSmall: [979, 3],
                navigation: true,
                pagination: false
            });
        }
        // Quantity arrows
        $('.arrow').on('click', function(e){
            var $input = $(this).closest('.quant-input').find('input');
            var val = parseInt($input.val());
            if($(this).hasClass('plus')) {
                $input.val(val + 1);
            } else if($(this).hasClass('minus') && val > 1) {
                $input.val(val - 1);
            }
        });
    });
</script>
</body>
</html>