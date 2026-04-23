<?php 
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');
    exit();
}

$user_id = intval($_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Order History | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Owl Carousel CSS (required for brands slider) -->
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
        .btn-track {
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-weight: 500;
            font-size: 0.75rem;
            color: #2A2826;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
        }
        .btn-track:hover {
            background: #C47A5E;
            color: white;
        }
        .btn-chat {
            background: #C47A5E;
            color: white;
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-weight: 500;
            font-size: 0.75rem;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
            margin-left: 5px;
        }
        .btn-chat:hover {
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
        .empty-orders {
            text-align: center;
            padding: 3rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .order-table {
                min-width: 800px;
            }
        }
    </style>
    <script language="javascript" type="text/javascript">
        var popUpWin=0;
        function popUpWindow(URLStr, left, top, width, height) {
            if(popUpWin) {
                if(!popUpWin.closed) popUpWin.close();
            }
            popUpWin = window.open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
        }
    </script>
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
            <li class="breadcrumb-item active" aria-current="page">Order History</li>
        </ol>
    </nav>

    <div class="order-table-wrapper">
        <h2 class="h4 mb-4" style="font-family: 'Instrument Serif', serif;"><i class="fas fa-history"></i> Your Order History</h2>
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
                        <th>Payment</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Use prepared statement to fetch orders
                    $sql = "SELECT p.productImage1, p.productName, p.id AS product_id, 
                                   o.productId, o.quantity, p.productPrice, p.shippingCharge, 
                                   o.paymentMethod, o.orderDate, o.id AS order_id
                            FROM orders o
                            JOIN products p ON o.productId = p.id
                            WHERE o.userId = ? AND o.paymentMethod IS NOT NULL
                            ORDER BY o.orderDate DESC";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if(mysqli_num_rows($result) > 0) {
                        $cnt = 1;
                        while($row = mysqli_fetch_assoc($result)) {
                            $qty = $row['quantity'];
                            $price = $row['productPrice'];
                            $shipping = $row['shippingCharge'];
                            $grandtotal = ($qty * $price) + $shipping;
                            
                            // Build image path with fallback
                            $img_path = "admin/productimages/".$row['product_id']."/".$row['productImage1'];
                            if(!file_exists($img_path) || empty($row['productImage1'])) {
                                $img_path = "admin/productimages/placeholder.jpg";
                            }
                    ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><img class="product-img" src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>"></td>
                        <td class="product-name"><a href="product-details.php?pid=<?php echo $row['product_id']; ?>"><?php echo htmlspecialchars($row['productName']); ?></a></td>
                        <td><?php echo $qty; ?></td>
                        <td>₦<?php echo number_format($price, 2); ?></td>
                        <td>₦<?php echo number_format($shipping, 2); ?></td>
                        <td>₦<?php echo number_format($grandtotal, 2); ?></td>
                        <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                        <td><?php echo htmlspecialchars($row['orderDate']); ?></td>
                        <td>
                            <a href="javascript:void(0);" onClick="popUpWindow('track-order.php?oid=<?php echo $row['order_id']; ?>');" class="btn-track">Track</a>
                            <a href="my-chat.php?orderid=<?php echo $row['order_id']; ?>" class="btn-chat">Chat</a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="10" class="empty-orders">No orders found. <a href="index.php" style="color:#C47A5E;">Start shopping</a></td></tr>';
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- jQuery (required for Owl Carousel) -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Owl Carousel JS -->
<script src="assets/js/owl.carousel.min.js"></script>
<!-- Initialize Owl Carousel for brands slider -->
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