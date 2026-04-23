<?php 
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');
    exit();
} else {
    $user_id = intval($_SESSION['id']);

    // Delete a pending order (only if paymentMethod is null and belongs to the user)
    if(isset($_GET['id'])) {
        $del_id = intval($_GET['id']);
        $del_stmt = mysqli_prepare($con, "DELETE FROM orders WHERE userId = ? AND paymentMethod IS NULL AND id = ?");
        mysqli_stmt_bind_param($del_stmt, "ii", $user_id, $del_id);
        if(mysqli_stmt_execute($del_stmt)) {
            echo "<script>alert('Order deleted successfully');</script>";
            echo "<script>window.location.href='pending-orders.php'</script>";
            exit();
        } else {
            echo "<script>alert('Delete failed. Please try again.');</script>";
        }
        mysqli_stmt_close($del_stmt);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Pending Orders | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Owl Carousel CSS (required for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .order-table-wrapper {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 1.5rem;
            margin: 2rem 0;
            overflow-x: auto;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-table th,
        .order-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .order-table th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .order-table td {
            font-size: 0.85rem;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
        }
        .product-name a {
            color: #2A2826;
            text-decoration: none;
            font-weight: 600;
        }
        .product-name a:hover {
            color: #C47A5E;
        }
        .btn-delete {
            background: #F7EAE8;
            border: none;
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-weight: 500;
            font-size: 0.75rem;
            color: #A55;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
        }
        .btn-delete:hover {
            background: #A55;
            color: white;
        }
        .btn-payment {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
        }
        .btn-payment:hover {
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
        @media (max-width: 768px) {
            .order-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pending Orders</li>
        </ol>
    </nav>

    <div class="order-table-wrapper">
        <h2 class="h4 mb-4" style="font-family: 'Instrument Serif', serif;"><i class="fas fa-hourglass-half"></i> Pending Orders (Awaiting Payment)</h2>
        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price (₦)</th>
                        <th>Shipping (₦)</th>
                        <th>Total (₦)</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Use prepared statement to fetch pending orders
                    $sql = "SELECT o.id AS order_id, p.id AS product_id, p.productImage1, p.productName, 
                                   o.quantity, p.productPrice, p.shippingCharge, o.orderDate
                            FROM orders o
                            JOIN products p ON o.productId = p.id
                            WHERE o.userId = ? AND o.paymentMethod IS NULL
                            ORDER BY o.orderDate DESC";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    $cnt = 1;
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $qty = $row['quantity'];
                            $price = $row['productPrice'];
                            $shipping = $row['shippingCharge'];
                            $grandtotal = ($qty * $price) + $shipping;
                            
                            // Image path with fallback
                            $img_path = "admin/productimages/".$row['product_id']."/".$row['productImage1'];
                            if(!file_exists($img_path) || empty($row['productImage1'])) {
                                $img_path = "admin/productimages/placeholder.jpg";
                            }
                    ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><img class="product-img" src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>" onerror="this.src='admin/productimages/placeholder.jpg'"></td>
                        <td class="product-name"><a href="product-details.php?pid=<?php echo $row['product_id']; ?>"><?php echo htmlspecialchars($row['productName']); ?></a></td>
                        <td><?php echo $qty; ?></td>
                        <td>₦<?php echo number_format($price, 2); ?></td>
                        <td>₦<?php echo number_format($shipping, 2); ?></td>
                        <td>₦<?php echo number_format($grandtotal, 2); ?></td>
                        <td><?php echo htmlspecialchars($row['orderDate']); ?></td>
                        <td>
                            <a href="pending-orders.php?id=<?php echo $row['order_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this pending order?')">Delete</a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="9" class="text-center">No pending orders found. <a href="index.php" style="color:#C47A5E;">Continue shopping</a></td></tr>';
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                </tbody>
            </table>
        </div>
        <?php if($cnt > 1) { // If there were any pending orders ?>
        <div class="text-center mt-4">
            <a href="payment-method.php" class="btn-payment">Proceed to Payment <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <?php } ?>
    </div>

    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- jQuery (required for Owl Carousel) -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
    });
</script>
</body>
</html>
<?php } ?>