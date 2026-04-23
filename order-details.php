<?php 
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Order Details | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Owl Carousel CSS (for brand slider) -->
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
        .order-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
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
        .table-custom th,
        .table-custom td {
            padding: 12px 10px;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .table-custom th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
            font-size: 0.85rem;
        }
        .table-custom td {
            font-size: 0.85rem;
        }
        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
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
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .order-card {
                padding: 1.5rem;
            }
            .table-custom {
                min-width: 700px;
            }
        }
    </style>
    <script language="javascript" type="text/javascript">
        var popUpWin=0;
        function popUpWindow(URLStr, left, top, width, height) {
            if(popUpWin) {
                if(!popUpWin.closed) popUpWin.close();
            }
            popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
        }
    </script>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <nav aria-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
        </ol>
    </nav>

    <div class="order-card">
        <h2 class="section-title"><i class="fas fa-file-invoice"></i> Order Details</h2>

        <?php
        $orderid = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        if(empty($orderid) || empty($email)) {
            echo '<div class="alert alert-warning">Please provide Order ID and Email to view order details.</div>';
        } else {
            // Validate order exists and email matches (using prepared statement)
            $validate_stmt = mysqli_prepare($con, "SELECT o.id FROM orders o JOIN users u ON o.userId = u.id WHERE o.id = ? AND u.email = ?");
            mysqli_stmt_bind_param($validate_stmt, "is", $orderid, $email);
            mysqli_stmt_execute($validate_stmt);
            mysqli_stmt_store_result($validate_stmt);
            if(mysqli_stmt_num_rows($validate_stmt) == 0) {
                echo '<div class="alert alert-danger">Either Order ID or Registered Email is invalid. Please try again.</div>';
            } else {
                mysqli_stmt_close($validate_stmt);
                // Fetch order products with order status
                $prod_stmt = mysqli_prepare($con, "SELECT p.id as pid, p.productImage1, p.productName, p.productPrice, 
                                                         o.quantity, o.paymentMethod, o.orderDate, o.id as orderid, o.orderStatus
                                                  FROM orders o
                                                  JOIN products p ON o.productId = p.id
                                                  WHERE o.id = ? AND o.paymentMethod IS NOT NULL");
                mysqli_stmt_bind_param($prod_stmt, "i", $orderid);
                mysqli_stmt_execute($prod_stmt);
                $result = mysqli_stmt_get_result($prod_stmt);
                if(mysqli_num_rows($result) == 0) {
                    echo '<div class="alert alert-info">No order details found. It may be pending payment.</div>';
                } else {
        ?>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price (₦)</th>
                        <th>Total (₦)</th>
                        <th>Payment Method</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnt = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        $qty = $row['quantity'];
                        $price = $row['productPrice'];
                        $total = $qty * $price;
                        // Image path
                        $image_path = "admin/productimages/" . $row['pid'] . "/" . $row['productImage1'];
                        if(empty($row['productImage1']) || !file_exists($image_path)) {
                            $image_path = "admin/productimages/placeholder.jpg";
                        }
                        // Status badge color
                        $status = htmlspecialchars($row['orderStatus'] ?: 'Pending');
                        $badge_class = '';
                        if($status == 'Delivered') $badge_class = 'background:#2E7D32; color:white;';
                        elseif($status == 'Cancelled') $badge_class = 'background:#d9534f; color:white;';
                        elseif($status == 'Processing') $badge_class = 'background:#C47A5E; color:white;';
                        else $badge_class = 'background:#FFEAD2; color:#C47A5E;';
                    ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><img class="product-img" src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>"></td>
                        <td><a href="product-details.php?pid=<?php echo $row['pid']; ?>"><?php echo htmlspecialchars($row['productName']); ?></a></td>
                        <td><?php echo $qty; ?></td>
                        <td>₦<?php echo number_format($price, 2); ?></td>
                        <td>₦<?php echo number_format($total, 2); ?></td>
                        <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                        <td><span class="badge-status" style="<?php echo $badge_class; ?>"><?php echo $status; ?></span></td>
                        <td><?php echo htmlspecialchars($row['orderDate']); ?></td>
                        <td><a href="javascript:void(0);" onClick="popUpWindow('track-order.php?oid=<?php echo $row['orderid']; ?>');" class="btn-track">Track</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php 
                }
                mysqli_stmt_close($prod_stmt);
            }
        }
        ?>
    </div>

    <!-- Brand Slider (horizontal) -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- jQuery & Owl Carousel (for horizontal brand slider) -->
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