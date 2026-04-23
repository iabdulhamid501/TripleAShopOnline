<?php
session_start();
error_reporting(0);
include('includes/config.php');

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
    // Use original session variables: 'login' to check if logged in, 'id' for user ID
    if(!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0){   
        header('location: login.php');
        exit();
    } else {
        $user_id = intval($_SESSION['id']);
        // Check if already in wishlist
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

// Get search term (from POST or GET)
$search_term = '';
if(isset($_POST['product']) && !empty(trim($_POST['product']))){
    $search_term = trim($_POST['product']);
} elseif(isset($_GET['search']) && !empty(trim($_GET['search']))){
    $search_term = trim($_GET['search']);
}
$search_display = htmlspecialchars($search_term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Search results - Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Search Results | Triple A ShopOnline</title>

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
        h1, h2, h3, h4, .section-title {
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
        .search-header {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 24px;
            margin-bottom: 32px;
            text-align: center;
        }
        .search-header h2 {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
        .search-header p {
            color: #7A726C;
        }
        .veloria-sidebar-module {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 20px;
            margin-bottom: 28px;
        }
        .section-title {
            font-size: 1.3rem;
            margin-bottom: 16px;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 6px;
        }
        .subcategory-list, .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .subcategory-list li, .category-list li {
            margin-bottom: 10px;
        }
        .subcategory-list a, .category-list a {
            color: #4A4440;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            transition: 0.2s;
        }
        .subcategory-list a:hover, .category-list a:hover {
            color: #C47A5E;
            padding-left: 5px;
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
            position: relative;
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
            padding: 60px;
            background: white;
            border-radius: 24px;
            font-size: 1.2rem;
            color: #7A726C;
            grid-column: 1 / -1;
        }
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 16px;
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
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active">Search Results</li>
        </ul>
    </div>

    <!-- Search Header -->
    <div class="search-header">
        <h2><i class="fas fa-search" style="color:#C47A5E;"></i> Search Results</h2>
        <?php if(!empty($search_display)): ?>
        <p>Showing results for: <strong>"<?php echo $search_display; ?>"</strong></p>
        <?php else: ?>
        <p>Please enter a search term.</p>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3">
            <!-- Sub Categories -->
            <div class="veloria-sidebar-module">
                <h3 class="section-title">Sub Categories</h3>
                <ul class="subcategory-list">
                    <?php 
                    $subcat_sql = mysqli_query($con, "SELECT id, subcategoryName FROM subcategory ORDER BY subcategoryName");
                    while($subrow = mysqli_fetch_assoc($subcat_sql)) {
                    ?>
                    <li><a href="sub-category.php?scid=<?php echo $subrow['id']; ?>"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($subrow['subcategoryName']); ?></a></li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Shop by Category -->
            <div class="veloria-sidebar-module">
                <h3 class="section-title">Shop by</h3>
                <h4 class="widget-title" style="font-weight:600; margin-bottom:12px;">Category</h4>
                <ul class="category-list">
                    <?php 
                    $cat_sql = mysqli_query($con, "SELECT id, categoryName FROM category ORDER BY categoryName");
                    while($catrow = mysqli_fetch_assoc($cat_sql)) {
                    ?>
                    <li><a href="category.php?cid=<?php echo $catrow['id']; ?>"><?php echo htmlspecialchars($catrow['categoryName']); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- Main Content: Product Grid -->
        <div class="col-md-9">
            <div class="product-grid">
                <?php
                if(empty($search_term)) {
                    echo '<div class="no-product">
                            <i class="fas fa-search" style="font-size: 3rem; color:#C47A5E; margin-bottom: 16px; display:block;"></i>
                            No search term provided.<br>
                            <a href="index.php" class="btn-cart" style="display:inline-block; margin-top:20px;">Continue Shopping</a>
                          </div>';
                } else {
                    // Use prepared statement for search
                    $like_term = "%{$search_term}%";
                    $search_stmt = mysqli_prepare($con, "SELECT * FROM products WHERE productName LIKE ?");
                    mysqli_stmt_bind_param($search_stmt, "s", $like_term);
                    mysqli_stmt_execute($search_stmt);
                    $result = mysqli_stmt_get_result($search_stmt);
                    $num = mysqli_num_rows($result);
                    if($num > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
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
                            <a href="search-result.php?action=add&id=<?php echo $row['id']; ?>" class="btn-cart">Add to Cart</a>
                            <?php else: ?>
                            <div class="btn-cart out-of-stock">Out of Stock</div>
                            <?php endif; ?>
                            <a href="search-result.php?pid=<?php echo $row['id']; ?>&action=wishlist" class="btn-wishlist" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                        }
                    } else {
                ?>
                <div class="no-product">
                    <i class="fas fa-box-open" style="font-size: 3rem; color:#C47A5E; margin-bottom: 16px; display:block;"></i>
                    No products found matching your search.<br>
                    <a href="index.php" class="btn-cart" style="display:inline-block; margin-top:20px;">Continue Shopping</a>
                </div>
                <?php 
                    }
                    mysqli_stmt_close($search_stmt);
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