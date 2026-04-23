<?php
session_start();
error_reporting(0);
include('includes/config.php');

// ========== CART ADDITION ==========
if(isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['id'])){
    $id = intval($_GET['id']);
    // Check product existence and stock
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
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
if(isset($_GET['action']) && $_GET['action'] == "wishlist" && $pid > 0){
    // Check if user is logged in (using session variable from your login system)
    if(!isset($_SESSION['uid']) || strlen($_SESSION['uid']) == 0){   
        header('location: login.php');
        exit();
    } else {
        $user_id = intval($_SESSION['uid']);
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

// ========== REVIEW SUBMISSION ==========
if(isset($_POST['submit']) && $pid > 0){
    $quality = intval($_POST['quality']);
    $price = intval($_POST['price']);
    $value = intval($_POST['value']);
    $name = trim($_POST['name']);
    $summary = trim($_POST['summary']);
    $review_text = trim($_POST['review']);
    
    if(!empty($name) && !empty($summary) && !empty($review_text)){
        $review_stmt = mysqli_prepare($con, "INSERT INTO productreviews(productId, quality, price, value, name, summary, review, reviewDate) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($review_stmt, "iiissss", $pid, $quality, $price, $value, $name, $summary, $review_text);
        if(mysqli_stmt_execute($review_stmt)){
            echo "<script>alert('Review submitted successfully');</script>";
            echo "<script>window.location.href='product-details.php?pid=$pid';</script>";
            exit();
        }
        mysqli_stmt_close($review_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Product details - Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Product Details | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    <!-- Lightbox -->
    <link href="assets/css/lightbox.css" rel="stylesheet">
    <!-- RateIt -->
    <link rel="stylesheet" href="assets/css/rateit.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F5; color: #2A2826; line-height: 1.5; }
        h1, h2, h3, h4, .section-title { font-family: 'Instrument Serif', serif; font-weight: 500; letter-spacing: -0.01em; }
        .container { max-width: 1300px; margin: 0 auto; padding: 0 24px; }
        .veloria-breadcrumb { background: transparent; padding: 16px 0; margin: 0 0 24px; }
        .veloria-breadcrumb ul { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 8px; font-size: 0.85rem; }
        .veloria-breadcrumb li a { color: #C47A5E; text-decoration: none; }
        .veloria-breadcrumb li.active { color: #7A726C; }
        .product-gallery { background: white; border-radius: 28px; border: 1px solid #EFE8E2; padding: 20px; margin-bottom: 24px; }
        .main-image img { width: 100%; border-radius: 20px; cursor: pointer; }
        .thumbnails { display: flex; gap: 12px; margin-top: 16px; flex-wrap: wrap; }
        .thumb { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 2px solid transparent; cursor: pointer; transition: 0.2s; }
        .thumb.active, .thumb:hover { border-color: #C47A5E; }
        .product-info-card { background: white; border-radius: 28px; border: 1px solid #EFE8E2; padding: 28px; height: 100%; }
        .product-title { font-size: 1.8rem; margin-bottom: 12px; }
        .rating-reviews { margin-bottom: 20px; }
        .price { font-size: 1.8rem; font-weight: 700; color: #C47A5E; }
        .old-price { font-size: 1rem; color: #AFA49B; text-decoration: line-through; margin-left: 12px; }
        .info-row { margin-bottom: 12px; display: flex; flex-wrap: wrap; }
        .info-label { width: 130px; font-weight: 600; color: #4A4440; }
        .info-value { flex: 1; color: #2A2826; }
        .quantity-selector { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
        .qty-input { width: 70px; padding: 8px; text-align: center; border: 1px solid #E0D6CE; border-radius: 40px; }
        .btn-cart { background: #C47A5E; border: none; border-radius: 40px; padding: 12px 28px; font-weight: 600; color: white; text-decoration: none; display: inline-block; }
        .btn-wishlist { background: transparent; border: 1px solid #EFE8E2; border-radius: 40px; padding: 12px 20px; color: #C47A5E; text-decoration: none; display: inline-block; }
        .product-tabs { background: white; border-radius: 28px; border: 1px solid #EFE8E2; padding: 24px; margin: 32px 0; }
        .nav-tabs { border-bottom: 1px solid #EFE8E2; display: flex; gap: 24px; margin-bottom: 24px; }
        .nav-tabs button { background: none; border: none; padding: 12px 0; font-weight: 600; color: #7A726C; border-bottom: 2px solid transparent; transition: 0.2s; }
        .nav-tabs button.active { color: #C47A5E; border-bottom-color: #C47A5E; }
        .review-item { border-bottom: 1px solid #EFE8E2; padding: 20px 0; }
        .review-form { margin-top: 32px; }
        .sidebar-widget { background: white; border-radius: 24px; border: 1px solid #EFE8E2; padding: 20px; margin-bottom: 28px; }
        .section-title { font-size: 1.3rem; margin-bottom: 16px; border-bottom: 2px solid #C47A5E; display: inline-block; padding-bottom: 6px; }
        .hot-deal-item { margin-bottom: 20px; }
        @media (max-width: 768px) { .product-title { font-size: 1.4rem; } .price { font-size: 1.4rem; } .thumb { width: 60px; height: 60px; } }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <?php
    // Fetch product details using prepared statement
    $prod_stmt = mysqli_prepare($con, "SELECT p.*, c.categoryName, s.subcategoryName 
                                       FROM products p 
                                       LEFT JOIN category c ON p.category = c.id 
                                       LEFT JOIN subcategory s ON p.subCategory = s.id 
                                       WHERE p.id = ?");
    mysqli_stmt_bind_param($prod_stmt, "i", $pid);
    mysqli_stmt_execute($prod_stmt);
    $prod_result = mysqli_stmt_get_result($prod_stmt);
    if(mysqli_num_rows($prod_result) == 0) {
        echo '<div class="alert alert-danger">Product not found.</div>';
        include('includes/footer.php');
        exit();
    }
    $row = mysqli_fetch_assoc($prod_result);
    mysqli_stmt_close($prod_stmt);
    ?>

    <!-- Breadcrumb -->
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li><a href="category.php?cid=<?php echo $row['category']; ?>"><?php echo htmlspecialchars($row['categoryName']); ?></a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li><?php echo htmlspecialchars($row['subcategoryName']); ?></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active"><?php echo htmlspecialchars($row['productName']); ?></li>
        </ul>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar-widget">
                <h3 class="section-title">Categories</h3>
                <?php
                $cat_sql = mysqli_query($con, "SELECT id, categoryName FROM category ORDER BY categoryName");
                while($cat = mysqli_fetch_assoc($cat_sql)) {
                ?>
                <div><a href="category.php?cid=<?php echo $cat['id']; ?>" style="color:#4A4440; text-decoration:none; display:block; padding:8px 0;"><?php echo htmlspecialchars($cat['categoryName']); ?></a></div>
                <?php } ?>
            </div>

            <div class="sidebar-widget">
                <h3 class="section-title">Hot Deals</h3>
                <?php
                $hot = mysqli_query($con, "SELECT id, productName, productPrice, productPriceBeforeDiscount, productImage1 FROM products ORDER BY RAND() LIMIT 4");
                while($hotrow = mysqli_fetch_assoc($hot)) {
                    $img_path = "admin/productimages/".$hotrow['id']."/".$hotrow['productImage1'];
                    if(!file_exists($img_path) || empty($hotrow['productImage1'])) $img_path = "admin/productimages/placeholder.jpg";
                ?>
                <div class="hot-deal-item">
                    <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($hotrow['productName']); ?>" style="width:100%; border-radius:16px;">
                    <h5><a href="product-details.php?pid=<?php echo $hotrow['id']; ?>" style="color:#2A2826;"><?php echo htmlspecialchars($hotrow['productName']); ?></a></h5>
                    <div class="price">₦<?php echo number_format($hotrow['productPrice'], 2); ?></div>
                    <?php if($hotrow['productPriceBeforeDiscount']) { ?>
                    <div class="old-price">₦<?php echo number_format($hotrow['productPriceBeforeDiscount'], 2); ?></div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Main Product Content -->
        <div class="col-md-9">
            <div class="row g-4">
                <!-- Gallery -->
                <div class="col-md-5">
                    <div class="product-gallery">
                        <?php
                        $main_img = "admin/productimages/".$row['id']."/".$row['productImage1'];
                        if(!file_exists($main_img) || empty($row['productImage1'])) $main_img = "admin/productimages/placeholder.jpg";
                        ?>
                        <div class="main-image">
                            <a href="<?php echo $main_img; ?>" data-lightbox="product-gallery">
                                <img src="<?php echo $main_img; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>" id="mainProductImage">
                            </a>
                        </div>
                        <div class="thumbnails">
                            <?php
                            $images = [$row['productImage1'], $row['productImage2'], $row['productImage3']];
                            foreach($images as $idx => $img) {
                                if(!empty($img)) {
                                    $thumb_path = "admin/productimages/".$row['id']."/".$img;
                                    if(file_exists($thumb_path)) {
                                        $active = ($idx == 0) ? 'active' : '';
                                        echo "<img src='$thumb_path' class='thumb $active' onclick='changeImage(this, \"$thumb_path\")'>";
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-md-7">
                    <div class="product-info-card">
                        <h1 class="product-title"><?php echo htmlspecialchars($row['productName']); ?></h1>
                        <?php
                        $review_count_stmt = mysqli_prepare($con, "SELECT COUNT(*) as cnt FROM productreviews WHERE productId = ?");
                        mysqli_stmt_bind_param($review_count_stmt, "i", $pid);
                        mysqli_stmt_execute($review_count_stmt);
                        $review_count_res = mysqli_stmt_get_result($review_count_stmt);
                        $review_count_row = mysqli_fetch_assoc($review_count_res);
                        $review_count = $review_count_row['cnt'];
                        mysqli_stmt_close($review_count_stmt);
                        ?>
                        <div class="rating-reviews">
                            <div class="rating rateit-small" data-rateit-value="4"></div>
                            <span>(<?php echo $review_count; ?> reviews)</span>
                        </div>

                        <div class="info-row"><div class="info-label">Availability:</div><div class="info-value"><?php echo htmlspecialchars($row['productAvailability']); ?></div></div>
                        <div class="info-row"><div class="info-label">Brand:</div><div class="info-value"><?php echo htmlspecialchars($row['productCompany']); ?></div></div>
                        <div class="info-row"><div class="info-label">Shipping:</div><div class="info-value"><?php echo ($row['shippingCharge'] == 0) ? "Free" : "₦".number_format($row['shippingCharge'], 2); ?></div></div>

                        <div class="price">₦<?php echo number_format($row['productPrice'], 2); ?>
                            <?php if($row['productPriceBeforeDiscount']) { ?>
                            <span class="old-price">₦<?php echo number_format($row['productPriceBeforeDiscount'], 2); ?></span>
                            <?php } ?>
                        </div>

                        <div class="quantity-selector">
                            <span class="info-label">Qty:</span>
                            <input type="number" id="productQty" value="1" min="1" class="qty-input">
                        </div>

                        <div class="d-flex gap-3">
                            <?php if($row['productAvailability'] == 'In Stock') { ?>
                            <a href="product-details.php?action=add&id=<?php echo $row['id']; ?>" class="btn-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
                            <?php } else { ?>
                            <div class="btn-cart" style="background:#aaa; cursor:not-allowed;">Out of Stock</div>
                            <?php } ?>
                            <a href="product-details.php?pid=<?php echo $row['id']; ?>&action=wishlist" class="btn-wishlist"><i class="far fa-heart"></i> Wishlist</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="product-tabs">
                <div class="nav-tabs">
                    <button class="active" data-tab="desc">Description</button>
                    <button data-tab="reviews">Reviews (<?php echo $review_count; ?>)</button>
                </div>
                <div id="desc" class="tab-pane active">
                    <p><?php echo nl2br(htmlspecialchars($row['productDescription'])); ?></p>
                </div>
                <div id="reviews" class="tab-pane" style="display:none;">
                    <?php
                    $rev_stmt = mysqli_prepare($con, "SELECT * FROM productreviews WHERE productId = ? ORDER BY reviewDate DESC");
                    mysqli_stmt_bind_param($rev_stmt, "i", $pid);
                    mysqli_stmt_execute($rev_stmt);
                    $rev_result = mysqli_stmt_get_result($rev_stmt);
                    if(mysqli_num_rows($rev_result) > 0) {
                        while($rv = mysqli_fetch_assoc($rev_result)) {
                    ?>
                    <div class="review-item">
                        <div><strong><?php echo htmlspecialchars($rv['name']); ?></strong> – <span><?php echo $rv['reviewDate']; ?></span></div>
                        <div>Quality: <?php echo $rv['quality']; ?>★ &nbsp; Price: <?php echo $rv['price']; ?>★ &nbsp; Value: <?php echo $rv['value']; ?>★</div>
                        <div><em>"<?php echo htmlspecialchars($rv['review']); ?>"</em></div>
                    </div>
                    <?php } } else { echo "<p>No reviews yet. Be the first to review!</p>"; } ?>

                    <div class="review-form">
                        <h4>Write a Review</h4>
                        <form method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Your Name *</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Summary *</label>
                                    <input type="text" name="summary" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label>Review *</label>
                                    <textarea name="review" class="form-control" rows="4" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label>Quality (1-5)</label>
                                    <select name="quality" class="form-control">
                                        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Price (1-5)</label>
                                    <select name="price" class="form-control">
                                        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Value (1-5)</label>
                                    <select name="value" class="form-control">
                                        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="submit" class="btn-cart">Submit Review</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php
            $rel_stmt = mysqli_prepare($con, "SELECT * FROM products WHERE subCategory = ? AND category = ? AND id != ? LIMIT 6");
            mysqli_stmt_bind_param($rel_stmt, "iii", $row['subCategory'], $row['category'], $pid);
            mysqli_stmt_execute($rel_stmt);
            $rel_result = mysqli_stmt_get_result($rel_stmt);
            if(mysqli_num_rows($rel_result) > 0) {
            ?>
            <div class="related-products">
                <h3 class="section-title">You May Also Like</h3>
                <div class="row g-4">
                    <?php while($rel = mysqli_fetch_assoc($rel_result)) {
                        $rel_img = "admin/productimages/".$rel['id']."/".$rel['productImage1'];
                        if(!file_exists($rel_img) || empty($rel['productImage1'])) $rel_img = "admin/productimages/placeholder.jpg";
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="product-card" style="background:white; border-radius:24px; border:1px solid #EFE8E2; overflow:hidden;">
                            <a href="product-details.php?pid=<?php echo $rel['id']; ?>">
                                <img src="<?php echo $rel_img; ?>" style="width:100%; aspect-ratio:1/1; object-fit:cover;">
                            </a>
                            <div class="p-3">
                                <h5><a href="product-details.php?pid=<?php echo $rel['id']; ?>" style="color:#2A2826;"><?php echo htmlspecialchars($rel['productName']); ?></a></h5>
                                <div class="price">₦<?php echo number_format($rel['productPrice'], 2); ?></div>
                                <?php if($rel['productPriceBeforeDiscount']) { ?>
                                <div class="old-price">₦<?php echo number_format($rel['productPriceBeforeDiscount'], 2); ?></div>
                                <?php } ?>
                                <a href="product-details.php?action=add&id=<?php echo $rel['id']; ?>" class="btn-cart" style="display:inline-block; margin-top:10px; padding:8px 16px;">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/lightbox.min.js"></script>
<script src="assets/js/jquery.rateit.min.js"></script>
<script>
    // Tab switching
    document.querySelectorAll('.nav-tabs button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.nav-tabs button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const tab = this.getAttribute('data-tab');
            document.getElementById('desc').style.display = tab === 'desc' ? 'block' : 'none';
            document.getElementById('reviews').style.display = tab === 'reviews' ? 'block' : 'none';
        });
    });

    // Thumbnail image change
    function changeImage(thumb, src) {
        document.getElementById('mainProductImage').src = src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        const mainLink = document.querySelector('.main-image a');
        if(mainLink) mainLink.href = src;
    }

    // Owl Carousel for brand slider
    $(document).ready(function(){
        if($('#brand-slider').length) {
            $("#brand-slider").owlCarousel({
                autoPlay: 3000,
                items: 5,
                itemsDesktop: [1199,4],
                itemsDesktopSmall: [979,3],
                navigation: true,
                pagination: false
            });
        }
    });
</script>
</body>
</html>