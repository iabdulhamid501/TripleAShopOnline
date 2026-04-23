<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['id']) == 0) {   
    header('location: logout.php');
    exit();
} else {
    $user_id = intval($_SESSION['id']);

    // Ensure addresses table exists
    $table_check = mysqli_query($con, "SHOW TABLES LIKE 'addresses'");
    if(mysqli_num_rows($table_check) == 0) {
        $create_sql = "CREATE TABLE `addresses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `userId` int(11) NOT NULL,
            `billingAddress` text,
            `billingCity` varchar(255) DEFAULT NULL,
            `billingState` varchar(255) DEFAULT NULL,
            `billingPincode` varchar(20) DEFAULT NULL,
            `billingCountry` varchar(100) DEFAULT NULL,
            `shippingAddress` text,
            `shippingCity` varchar(255) DEFAULT NULL,
            `shippingState` varchar(255) DEFAULT NULL,
            `shippingPincode` varchar(20) DEFAULT NULL,
            `shippingCountry` varchar(100) DEFAULT NULL,
            `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        mysqli_query($con, $create_sql);
    }

    // Delete product from cart (session based)
    if(isset($_GET['del'])) {
        $del_id = intval($_GET['del']);
        unset($_SESSION['cart'][$del_id]);
        echo "<script>alert('Product removed from cart.');</script>";
        echo "<script>window.location.href='checkout.php';</script>";
        exit();
    }

    // Add new address (prepared statement)
    if(isset($_POST['submit'])) {
        $baddress = trim($_POST['baddress']);
        $bcity = trim($_POST['bcity']);
        $bstate = trim($_POST['bstate']);
        $bpincode = trim($_POST['bpincode']);
        $bcountry = trim($_POST['bcountry']);
        $saddress = trim($_POST['saddress']);
        $scity = trim($_POST['scity']);
        $sstate = trim($_POST['sstate']);
        $spincode = trim($_POST['spincode']);
        $scountry = trim($_POST['scountry']);

        $insert_stmt = mysqli_prepare($con, "INSERT INTO addresses(userId, billingAddress, billingCity, billingState, billingPincode, billingCountry, shippingAddress, shippingCity, shippingState, shippingPincode, shippingCountry) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "issssssssss", $user_id, $baddress, $bcity, $bstate, $bpincode, $bcountry, $saddress, $scity, $sstate, $spincode, $scountry);
        if(mysqli_stmt_execute($insert_stmt)) {
            echo "<script>alert('Address added successfully');</script>";
            echo "<script>window.location.href='checkout.php';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
        mysqli_stmt_close($insert_stmt);
        exit();
    }

    // Proceed to payment (store selected address and total in session)
    if(isset($_POST['proceedpayment'])) {
        $address_id = intval($_POST['selectedaddress']);
        $gtotal = floatval($_POST['grandtotal']);
        $_SESSION['checkout_address_id'] = $address_id;
        $_SESSION['checkout_grand_total'] = $gtotal;
        header('location: payment.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Checkout | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (for copy address) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
        }
        .checkout-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .section-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 12px;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }
        .table-custom th, .table-custom td {
            padding: 12px 8px;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
        }
        .btn-delete {
            background: #F7EAE8;
            border: none;
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-size: 0.75rem;
            color: #A55;
            text-decoration: none;
            display: inline-block;
        }
        .btn-delete:hover {
            background: #A55;
            color: white;
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
        .address-row {
            background: #FCF9F5;
            border-radius: 20px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            border: 1px solid #EFE8E2;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
        }
        .form-control {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
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
            .table-custom {
                min-width: 600px;
            }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="my-cart.php">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Left Column: Cart Summary -->
        <div class="col-lg-7">
            <div class="checkout-card">
                <h2 class="section-title"><i class="fas fa-shopping-cart"></i> Your Cart</h2>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_total = 0;
                            if(!empty($_SESSION['cart'])) {
                                $cart_ids = array_keys($_SESSION['cart']);
                                $in_query = implode(',', array_fill(0, count($cart_ids), '?'));
                                $sql = "SELECT id, productName, productImage1, productPrice FROM products WHERE id IN ($in_query)";
                                $stmt = mysqli_prepare($con, $sql);
                                $types = str_repeat('i', count($cart_ids));
                                mysqli_stmt_bind_param($stmt, $types, ...$cart_ids);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                while($row = mysqli_fetch_assoc($result)) {
                                    $pid = $row['id'];
                                    $qty = $_SESSION['cart'][$pid]['quantity'];
                                    $price = $row['productPrice'];
                                    $total = $qty * $price;
                                    $grand_total += $total;
                                    $img_path = "admin/productimages/".$pid."/".$row['productImage1'];
                                    if(!file_exists($img_path) || empty($row['productImage1'])) {
                                        $img_path = "admin/productimages/placeholder.jpg";
                                    }
                            ?>
                            <tr>
                                <td><img class="product-img" src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>"></td>
                                <td><a href="product-details.php?pid=<?php echo $pid; ?>"><?php echo htmlspecialchars($row['productName']); ?></a></td>
                                <td>₦<?php echo number_format($price, 2); ?></td>
                                <td><?php echo $qty; ?></td>
                                <td>₦<?php echo number_format($total, 2); ?></td>
                                <td><a href="checkout.php?del=<?php echo $pid; ?>" class="btn-delete" onclick="return confirm('Remove this item?')">Delete</a></td>
                            </tr>
                            <?php 
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                echo '<tr><td colspan="6" class="text-center">Your cart is empty. <a href="index.php">Continue shopping</a></td></tr>';
                            }
                            ?>
                            <?php if($grand_total > 0) { ?>
                            <tr style="font-weight:bold; background:#FCF9F5;">
                                <td colspan="4" class="text-end">Grand Total:</td>
                                <td>₦<?php echo number_format($grand_total, 2); ?></td>
                                <td></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Address Form -->
            <div class="checkout-card">
                <h2 class="section-title"><i class="fas fa-plus-circle"></i> Add New Address</h2>
                <form method="post" name="address">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h5 class="mb-3">Billing Address</h5>
                            <div class="mb-3"><label class="form-label">Address</label><input type="text" name="baddress" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label">City</label><input type="text" name="bcity" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label">State</label><input type="text" name="bstate" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label">Pincode</label><input type="text" name="bpincode" class="form-control" pattern="[0-9]+" maxlength="6" required></div>
                            <div class="mb-3"><label class="form-label">Country</label><input type="text" name="bcountry" class="form-control" required></div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Shipping Address <input type="checkbox" name="adcheck" value="1" class="ms-2"> <small>Same as billing</small></h5>
                            <div class="mb-3"><label class="form-label">Address</label><input type="text" name="saddress" id="saddress" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label">City</label><input type="text" name="scity" id="scity" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label">State</label><input type="text" name="sstate" id="sstate" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label">Pincode</label><input type="text" name="spincode" id="spincode" class="form-control" pattern="[0-9]+" maxlength="6" required></div>
                            <div class="mb-3"><label class="form-label">Country</label><input type="text" name="scountry" id="scountry" class="form-control" required></div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" name="submit" class="btn-primary">Add Address</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Existing Addresses & Checkout -->
        <div class="col-lg-5">
            <div class="checkout-card">
                <h2 class="section-title"><i class="fas fa-address-book"></i> Select Address</h2>
                <?php
                $addr_stmt = mysqli_prepare($con, "SELECT * FROM addresses WHERE userId = ? ORDER BY id DESC");
                mysqli_stmt_bind_param($addr_stmt, "i", $user_id);
                mysqli_stmt_execute($addr_stmt);
                $addr_result = mysqli_stmt_get_result($addr_stmt);
                if(mysqli_num_rows($addr_result) > 0) {
                ?>
                <form method="post">
                    <input type="hidden" name="grandtotal" value="<?php echo $grand_total; ?>">
                    <?php while($addr = mysqli_fetch_assoc($addr_result)) { ?>
                    <div class="address-row">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="selectedaddress" value="<?php echo $addr['id']; ?>" required>
                            <label class="form-check-label">
                                <strong>Billing:</strong> <?php echo htmlspecialchars($addr['billingAddress'] . ', ' . $addr['billingCity'] . ', ' . $addr['billingState'] . ' - ' . $addr['billingPincode'] . ', ' . $addr['billingCountry']); ?>
                            </label><br>
                            <label class="form-check-label">
                                <strong>Shipping:</strong> <?php echo htmlspecialchars($addr['shippingAddress'] . ', ' . $addr['shippingCity'] . ', ' . $addr['shippingState'] . ' - ' . $addr['shippingPincode'] . ', ' . $addr['shippingCountry']); ?>
                            </label>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="mt-3">
                        <button type="submit" name="proceedpayment" class="btn-primary w-100" <?php echo ($grand_total <= 0) ? 'disabled' : ''; ?>>Proceed to Payment <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </form>
                <?php } else { ?>
                <p class="text-muted">No saved addresses. Please add one using the form.</p>
                <?php } ?>
                mysqli_stmt_close($addr_stmt);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
        $('input[name="adcheck"]').click(function(){
            if($(this).prop("checked") == true){
                $('#saddress').val($('input[name="baddress"]').val());
                $('#scity').val($('input[name="bcity"]').val());
                $('#sstate').val($('input[name="bstate"]').val());
                $('#spincode').val($('input[name="bpincode"]').val());
                $('#scountry').val($('input[name="bcountry"]').val());
            }
        });
    });
</script>
</body>
</html>
<?php } ?>