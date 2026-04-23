<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
{   
    header('location:login.php');
}
else{
    // Code for Product deletion from wishlist	
    $wid = intval($_GET['del']);
    if(isset($_GET['del']))
    {
        $query = mysqli_query($con, "delete from wishlist where id='$wid'");
    }

    if(isset($_GET['action']) && $_GET['action']=="add"){
        $id = intval($_GET['id']);
        $query = mysqli_query($con, "delete from wishlist where productId='$id'");
        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $sql_p = "SELECT * FROM products WHERE id={$id}";
            $query_p = mysqli_query($con, $sql_p);
            if(mysqli_num_rows($query_p) != 0){
                $row_p = mysqli_fetch_array($query_p);
                $_SESSION['cart'][$row_p['id']] = array("quantity" => 1, "price" => $row_p['productPrice']);	
                header('location:my-wishlist.php');
            } else {
                $message = "Product ID is invalid";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="My Wishlist - Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>My Wishlist | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel (for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    
    <style>
        /* VELORIA global styles */
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
        h1, h2, h3, h4, .page-title {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        /* Breadcrumb */
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
        /* Wishlist Table (responsive card on mobile) */
        .wishlist-wrapper {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 24px;
            margin-bottom: 32px;
            overflow-x: auto;
        }
        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
        }
        .wishlist-table th,
        .wishlist-table td {
            padding: 20px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #F0E9E3;
            text-align: left;
        }
        .wishlist-table th {
            font-weight: 600;
            color: #4A4440;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 16px;
        }
        .product-name a {
            font-weight: 600;
            color: #2A2826;
            text-decoration: none;
        }
        .product-name a:hover {
            color: #C47A5E;
        }
        .rating {
            margin: 8px 0;
            font-size: 0.8rem;
            color: #FFB800;
        }
        .rating .rate {
            color: #FFB800;
        }
        .rating .non-rate {
            color: #DDD;
        }
        .review {
            font-size: 0.7rem;
            color: #7A726C;
            margin-left: 6px;
        }
        .price {
            font-weight: 700;
            font-size: 1.1rem;
            color: #C47A5E;
        }
        .btn-wishlist-cart {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.75rem;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
        }
        .btn-wishlist-cart:hover {
            background: #A85E44;
        }
        .btn-remove {
            color: #C47A5E;
            font-size: 1.2rem;
            transition: 0.2s;
        }
        .btn-remove:hover {
            color: #A55;
        }
        .empty-wishlist {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 24px;
            font-size: 1.2rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .wishlist-table th, .wishlist-table td {
                padding: 12px 8px;
            }
            .product-img {
                width: 60px;
                height: 60px;
            }
            .btn-wishlist-cart {
                padding: 6px 12px;
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>

<!-- VELORIA Header -->
<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Breadcrumb -->
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active">My Wishlist</li>
        </ul>
    </div>

    <div class="wishlist-wrapper">
        <table class="wishlist-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Details</th>
                    <th>Price</th>
                    <th>Action</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ret = mysqli_query($con, "select products.productName as pname, products.productName as proid, products.productImage1 as pimage, products.productPrice as pprice, wishlist.productId as pid, wishlist.id as wid from wishlist join products on products.id=wishlist.productId where wishlist.userId='".$_SESSION['id']."'");
                $num = mysqli_num_rows($ret);
                if($num > 0) {
                    while ($row = mysqli_fetch_array($ret)) {
                        $pd = $row['pid'];
                        $review_query = mysqli_query($con, "select * from productreviews where productId='$pd'");
                        $review_count = mysqli_num_rows($review_query);
                ?>
                <tr>
                    <td data-label="Product">
                        <img src="admin/productimages/<?php echo $row['pid']; ?>/<?php echo $row['pimage']; ?>" alt="<?php echo htmlentities($row['pname']); ?>" class="product-img">
                    </td>
                    <td data-label="Details">
                        <div class="product-name">
                            <a href="product-details.php?pid=<?php echo $row['pid']; ?>"><?php echo htmlentities($row['pname']); ?></a>
                        </div>
                        <div class="rating">
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star non-rate"></i>
                            <span class="review">(<?php echo $review_count; ?> Reviews)</span>
                        </div>
                    </td>
                    <td data-label="Price">
                        <div class="price">₦<?php echo number_format($row['pprice'], 2); ?></div>
                    </td>
                    <td data-label="Action">
                        <a href="my-wishlist.php?page=product&action=add&id=<?php echo $row['pid']; ?>" class="btn-wishlist-cart">Add to Cart</a>
                    </td>
                    <td data-label="Remove">
                        <a href="my-wishlist.php?del=<?php echo $row['wid']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="btn-remove">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    }
                } else { 
                ?>
                <tr>
                    <td colspan="5" class="empty-wishlist">
                        <i class="fas fa-heart" style="font-size: 3rem; color:#C47A5E; margin-bottom: 16px; display:block;"></i>
                        Your wishlist is empty.<br>
                        <a href="index.php" class="btn-wishlist-cart" style="display:inline-block; margin-top:20px;">Start Shopping</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts (preserve jQuery, Owl Carousel, etc.) -->
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
    });
</script>
</body>
</html>
<?php } ?>