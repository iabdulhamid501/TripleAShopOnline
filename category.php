<?php
session_start();
error_reporting(0);
include('includes/config.php');

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;

// ========== CART ADDITION ==========
if(isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = mysqli_prepare($con, "SELECT id, productPrice, productAvailability FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) != 0){
        $row_p = mysqli_fetch_assoc($result);
        if($row_p['productAvailability'] == 'In Stock'){
            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id]['quantity']++;
            } else {
                $_SESSION['cart'][$id] = array("quantity" => 1, "price" => $row_p['productPrice']);
            }
            echo "<script>alert('Product has been added to the cart');</script>";
            echo "<script>document.location = 'my-cart.php';</script>";
            exit();
        } else {
            echo "<script>alert('Sorry, this product is out of stock');</script>";
        }
    } else {
        echo "<script>alert('Invalid product');</script>";
    }
    mysqli_stmt_close($stmt);
}

// ========== WISHLIST ADDITION ==========
if(isset($_GET['pid']) && isset($_GET['action']) && $_GET['action'] == "wishlist"){
    $pid = intval($_GET['pid']);
    if(!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0){   
        header('location: login.php');
        exit();
    } else {
        $user_id = intval($_SESSION['id']);
        $check_stmt = mysqli_prepare($con, "SELECT id FROM wishlist WHERE userId = ? AND productId = ?");
        mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $pid);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if(mysqli_stmt_num_rows($check_stmt) == 0){
            $insert_stmt = mysqli_prepare($con, "INSERT INTO wishlist(userId, productId) VALUES(?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "ii", $user_id, $pid);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);
            echo "<script>alert('Product added to wishlist');</script>";
        } else {
            echo "<script>alert('Product already in wishlist');</script>";
        }
        mysqli_stmt_close($check_stmt);
        echo "<script>window.location.href='my-wishlist.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="eCommerce, Shopping Portal">
    <meta name="robots" content="all">
    <title>Category | Triple A ShopOnline</title>

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
        h1, h2, h3, h4, .logo, .section-title, .widget-title {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .veloria-sidebar-module {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 20px;
            margin-bottom: 28px;
        }
        .section-title {
            font-size: 1.5rem;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 6px;
            margin-bottom: 20px;
        }
        .widget-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2A2826;
            margin-bottom: 15px;
        }
        .subcategory-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 10px;
        }
        .subcategory-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F5F0EB;
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #4A4440;
            text-decoration: none;
            transition: all 0.2s;
        }
        .subcategory-chip i {
            color: #C47A5E;
            font-size: 0.75rem;
        }
        .subcategory-chip:hover {
            background: #C47A5E;
            color: white;
        }
        .subcategory-chip:hover i {
            color: white;
        }
        .category-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .category-links a {
            color: #4A4440;
            text-decoration: none;
            padding: 6px 0;
            transition: 0.2s;
            font-weight: 500;
        }
        .category-links a:hover {
            color: #C47A5E;
            padding-left: 5px;
        }
        .category-header {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 28px;
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
        }
        .category-header h1 {
            font-size: 2.5rem;
            color: #2A2826;
            margin: 0;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 28px;
            margin: 30px 0;
        }
        .product-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.25s ease;
            border: 1px solid #F0E9E3;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 28px -12px rgba(0,0,0,0.1);
            border-color: #DECFC4;
        }
        .product-img {
            width: 100%;
            aspect-ratio: 1 / 1.1;
            object-fit: cover;
        }
        .product-info {
            padding: 18px;
        }
        .product-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
        }
        .product-title a {
            color: #2A2826;
            text-decoration: none;
        }
        .product-title a:hover {
            color: #C47A5E;
        }
        .price {
            font-weight: 700;
            font-size: 1.2rem;
            margin: 10px 0;
        }
        .old-price {
            font-size: 0.8rem;
            color: #AFA49B;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 400;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }
        .btn-cart {
            flex: 1;
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 8px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
            text-decoration: none;
            color: #2A2826;
        }
        .btn-cart:hover:not(:disabled) {
            background: #C47A5E;
            color: white;
        }
        .btn-wishlist {
            background: transparent;
            border: 1px solid #EFE8E2;
            border-radius: 40px;
            padding: 8px 12px;
            color: #C47A5E;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-wishlist:hover {
            background: #C47A5E;
            color: white;
            border-color: #C47A5E;
        }
        .out-of-stock {
            background: #F7EAE8;
            color: #A67C7C;
            cursor: default;
        }
        .no-product {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 24px;
            font-size: 1.2rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 16px;
            }
            .category-header h1 {
                font-size: 1.8rem;
            }
            .subcategory-chips {
                gap: 8px;
            }
            .subcategory-chip {
                font-size: 0.75rem;
                padding: 4px 12px;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="row" style="margin: 40px 0;">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <!-- Sub Categories – displayed as horizontal chips -->
            <div class="veloria-sidebar-module">
                <h3 class="section-title">Sub Categories</h3>
                <div class="subcategory-chips">
                    <?php 
                    if($cid > 0){
                        $sub_sql = "SELECT id, subcategoryName FROM subcategory WHERE categoryid = ?";
                        $sub_stmt = mysqli_prepare($con, $sub_sql);
                        mysqli_stmt_bind_param($sub_stmt, "i", $cid);
                        mysqli_stmt_execute($sub_stmt);
                        $sub_result = mysqli_stmt_get_result($sub_stmt);
                        if(mysqli_num_rows($sub_result) > 0) {
                            while($subrow = mysqli_fetch_assoc($sub_result)) {
                    ?>
                    <a href="sub-category.php?scid=<?php echo $subrow['id']; ?>" class="subcategory-chip">
                        <i class="fas fa-tag"></i> <?php echo htmlspecialchars($subrow['subcategoryName']); ?>
                    </a>
                    <?php 
                            }
                        } else {
                            echo '<span style="color:#aaa;">No subcategories</span>';
                        }
                        mysqli_stmt_close($sub_stmt);
                    } else {
                        echo '<span style="color:#aaa;">Select a category</span>';
                    }
                    ?>
                </div>
            </div>

            <!-- Shop by Category -->
            <div class="veloria-sidebar-module">
                <h3 class="section-title">Shop by</h3>
                <div class="widget-header">
                    <h4 class="widget-title">Category</h4>
                </div>
                <div class="category-links">
                    <?php 
                    $cat_sql = mysqli_query($con, "SELECT id, categoryName FROM category ORDER BY categoryName");
                    while($catrow = mysqli_fetch_assoc($cat_sql)) {
                        $active = ($catrow['id'] == $cid) ? ' style="color:#C47A5E; font-weight:600;"' : '';
                    ?>
                    <a href="category.php?cid=<?php echo $catrow['id']; ?>"<?php echo $active; ?>>
                        <?php echo htmlspecialchars($catrow['categoryName']); ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Category Header -->
            <div class="category-header">
                <h1>
                    <?php 
                    if($cid > 0){
                        $cat_stmt = mysqli_prepare($con, "SELECT categoryName FROM category WHERE id = ?");
                        mysqli_stmt_bind_param($cat_stmt, "i", $cid);
                        mysqli_stmt_execute($cat_stmt);
                        $cat_res = mysqli_stmt_get_result($cat_stmt);
                        $cname = mysqli_fetch_assoc($cat_res);
                        echo htmlspecialchars($cname['categoryName']);
                        mysqli_stmt_close($cat_stmt);
                    } else {
                        echo "All Products";
                    }
                    ?>
                </h1>
            </div>

            <!-- Products Grid -->
            <div class="product-grid">
                <?php
                if($cid > 0){
                    $prod_stmt = mysqli_prepare($con, "SELECT * FROM products WHERE category = ?");
                    mysqli_stmt_bind_param($prod_stmt, "i", $cid);
                    mysqli_stmt_execute($prod_stmt);
                    $prod_result = mysqli_stmt_get_result($prod_stmt);
                    if(mysqli_num_rows($prod_result) > 0) {
                        while($row = mysqli_fetch_assoc($prod_result)) {
                            $img_path = "admin/productimages/".$row['id']."/".$row['productImage1'];
                            if(!file_exists($img_path) || empty($row['productImage1'])) {
                                $img_path = "admin/productimages/placeholder.jpg";
                            }
                ?>
                <div class="product-card">
                    <a href="product-details.php?pid=<?php echo $row['id']; ?>">
                        <img class="product-img" src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>">
                    </a>
                    <div class="product-info">
                        <div class="product-title">
                            <a href="product-details.php?pid=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['productName']); ?></a>
                        </div>
                        <div class="price">
                            ₦<?php echo number_format($row['productPrice'], 2); ?>
                            <?php if(!empty($row['productPriceBeforeDiscount'])): ?>
                            <span class="old-price">₦<?php echo number_format($row['productPriceBeforeDiscount'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="action-buttons">
                            <?php if($row['productAvailability'] == 'In Stock'): ?>
                            <a href="category.php?action=add&id=<?php echo $row['id']; ?>" class="btn-cart">Add to Cart</a>
                            <?php else: ?>
                            <div class="btn-cart out-of-stock">Out of Stock</div>
                            <?php endif; ?>
                            <a href="category.php?pid=<?php echo $row['id']; ?>&action=wishlist" class="btn-wishlist" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                        }
                    } else {
                        echo '<div class="no-product">No products found in this category.</div>';
                    }
                    mysqli_stmt_close($prod_stmt);
                } else {
                    echo '<div class="no-product">Please select a category.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Brand Slider -->
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
    });
</script>
</body>
</html>