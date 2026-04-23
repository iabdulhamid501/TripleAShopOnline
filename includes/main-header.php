<?php 
// FIXED: Corrected typo $_Get to $_GET
if(isset($_GET['action'])){
    if(!empty($_SESSION['cart'])){
        foreach($_POST['quantity'] as $key => $val){
            if($val==0){
                unset($_SESSION['cart'][$key]);
            }else{
                $_SESSION['cart'][$key]['quantity']=$val;
            }
        }
    }
}
?>

<!-- ========== VELORIA ELEGANT HEADER (Modern, Nigerian Naira) ========== -->
<style>
    /* VELORIA design system – same as new homepage */
    .veloria-header {
        font-family: 'Inter', sans-serif;
        background: #FCF9F5;
        border-bottom: 1px solid #E9E2DA;
    }
    .veloria-header .top-bar {
        background: #2A2826;
        color: #E3DCD5;
        font-size: 0.75rem;
        text-align: center;
        padding: 10px 0;
        letter-spacing: 0.5px;
    }
    .veloria-header .top-bar a {
        color: #E3DCD5;
        text-decoration: none;
        margin: 0 8px;
    }
    .veloria-header .top-bar a:hover {
        color: #C47A5E;
    }
    .veloria-header .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding: 24px 0;
        gap: 20px;
    }
    .veloria-header .logo h2 {
        font-family: 'Instrument Serif', serif;
        font-size: 2rem;
        font-weight: 500;
        letter-spacing: -0.5px;
        margin: 0;
        color: #2A2826;
    }
    .veloria-header .logo h2 span {
        color: #C47A5E;
        font-weight: 600;
    }
    .veloria-header .search-area {
        flex: 1;
        max-width: 380px;
    }
    .veloria-header .control-group {
        display: flex;
        border: 1px solid #E0D6CE;
        border-radius: 60px;
        background: white;
        overflow: hidden;
    }
    .veloria-header .search-field {
        flex: 1;
        border: none;
        padding: 10px 18px;
        font-size: 0.85rem;
        outline: none;
        background: transparent;
        font-family: 'Inter', sans-serif;
    }
    .veloria-header .search-button {
        background: transparent;
        border: none;
        padding: 0 16px;
        color: #C47A5E;
        cursor: pointer;
        font-size: 1rem;
    }
    .veloria-header .dropdown-cart {
        position: relative;
    }
    .veloria-header .lnk-cart {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #2A2826;
        padding: 10px 18px;
        border-radius: 40px;
        text-decoration: none;
        color: white !important;
        font-weight: 500;
        font-size: 0.85rem;
        transition: 0.2s;
        cursor: pointer;
    }
    .veloria-header .lnk-cart:hover {
        background: #C47A5E;
    }
    .veloria-header .items-cart-inner {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .veloria-header .total-price-basket .lbl {
        font-size: 0.75rem;
        text-transform: uppercase;
    }
    .veloria-header .total-price {
        font-weight: 700;
    }
    .veloria-header .basket i {
        font-size: 1.2rem;
    }
    .veloria-header .basket-item-count .count {
        background: #C47A5E;
        color: #2A2826;
        border-radius: 30px;
        padding: 2px 8px;
        font-weight: bold;
        font-size: 0.7rem;
    }
    .veloria-header .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        border: 1px solid #EDE5DE;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
        padding: 16px;
        min-width: 320px;
        z-index: 1000;
        list-style: none;
        margin-top: 8px;
        display: none;
    }
    .veloria-header .dropdown-menu.show {
        display: block;
    }
    .veloria-header .cart-item {
        border-bottom: 1px solid #EFE8E2;
        padding: 12px 0;
    }
    .veloria-header .cart-item .name a {
        font-weight: 600;
        font-size: 0.9rem;
        color: #2A2826;
        text-decoration: none;
    }
    .veloria-header .cart-item .price {
        font-size: 0.8rem;
        color: #C47A5E;
    }
    .veloria-header .cart-total {
        margin-top: 12px;
        text-align: right;
    }
    .veloria-header .btn-primary {
        background: #C47A5E;
        border: none;
        border-radius: 40px;
        padding: 10px 20px;
        font-weight: 600;
        color: white;
        text-align: center;
        display: inline-block;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .veloria-header .btn-primary:hover {
        background: #A85E44;
    }
    .veloria-header .text-right {
        text-align: right;
    }
    @media (max-width: 768px) {
        .veloria-header .main-header {
            flex-direction: column;
            align-items: stretch;
        }
        .veloria-header .search-area {
            max-width: 100%;
        }
        .veloria-header .dropdown-menu {
            position: fixed;
            top: auto;
            right: 16px;
            left: 16px;
            width: auto;
        }
    }
</style>

<div class="veloria-header">
    <!-- Top bar: contact & currency -->
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="hidden-xs">+234 906 482 3328</span> 
                    <a href="mailto:concierge@tripleashoponline.com">concierge@tripleashoponline.com</a>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <a href="./Agent/index.php"><i class="fas fa-sign-in-alt"></i> Agent Login</a> | 
                    <?php if(strlen($_SESSION['login']) == 0): ?>
                    <a href="./Delivery/index.php"><i class="fas fa-sign-in-alt"></i> Deivery Login</a>
                    <?php else: ?>
                    <a href="logout.php">Sign Out</a>
                    <?php endif; ?> | 
                    <span>Currency: ₦</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-header">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">
                    <h2>Triple A<br><span>ShopOnline</span></h2>
                </a>
            </div>

            <!-- Search form -->
            <div class="search-area">
                <form name="search" method="post" action="search-result.php">
                    <div class="control-group">
                        <input class="search-field" placeholder="Search here..." name="product" required="required" />
                        <button class="search-button" type="submit" name="search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Cart Dropdown (Bootstrap 5 compatible) -->
            <div class="top-cart-row">
                <?php if(!empty($_SESSION['cart'])): ?>
                <div class="dropdown-cart">
                    <a href="#" class="lnk-cart" id="cartDropdownLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="items-cart-inner">
                            <div class="total-price-basket">
                                <span class="lbl">cart -</span>
                                <span class="total-price">
                                    <span class="sign">₦</span>
                                    <span class="value"><?php echo isset($_SESSION['tp']) ? number_format($_SESSION['tp'], 2) : '0.00'; ?></span>
                                </span>
                            </div>
                            <div class="basket">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div class="basket-item-count">
                                <span class="count"><?php echo isset($_SESSION['qnty']) ? $_SESSION['qnty'] : '0'; ?></span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="cartDropdownLink">
                        <?php
                        $sql = "SELECT * FROM products WHERE id IN(";
                        foreach($_SESSION['cart'] as $id => $value){
                            $sql .= (int)$id . ",";
                        }
                        $sql = rtrim($sql, ',') . ") ORDER BY id ASC";
                        $query = mysqli_query($con, $sql);
                        $totalprice = 0;
                        $totalqunty = 0;
                        if($query && mysqli_num_rows($query) > 0){
                            while($row = mysqli_fetch_array($query)){
                                $quantity = $_SESSION['cart'][$row['id']]['quantity'];
                                $subtotal = $quantity * $row['productPrice'] + $row['shippingCharge'];
                                $totalprice += $subtotal;
                                $totalqunty += $quantity;
                                $_SESSION['qnty'] = $totalqunty;
                        ?>
                        <li>
                            <div class="cart-item product-summary">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="image">
                                            <a href="product-details.php?pid=<?php echo $row['id'];?>">
                                                <img src="admin/productimages/<?php echo $row['id'];?>/<?php echo $row['productImage1'];?>" width="50" height="60" alt="<?php echo $row['productName']; ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <h3 class="name"><a href="product-details.php?pid=<?php echo $row['id'];?>"><?php echo htmlspecialchars($row['productName']); ?></a></h3>
                                        <div class="price">₦<?php echo number_format($row['productPrice'] + $row['shippingCharge'], 2); ?> × <?php echo $quantity; ?></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php } } ?>
                        <li>
                            <hr>
                            <div class="cart-total">
                                <div class="pull-right">
                                    <span class="text">Total :</span>
                                    <span class='price'>₦<?php echo number_format($totalprice, 2); ?></span>
                                </div>
                                <div class="clearfix"></div>
                                <a href="my-cart.php" class="btn btn-primary btn-block">My Cart</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="dropdown-cart">
                    <a href="#" class="lnk-cart" id="cartDropdownLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="items-cart-inner">
                            <div class="total-price-basket">
                                <span class="lbl">cart -</span>
                                <span class="total-price">
                                    <span class="sign">₦</span>
                                    <span class="value">0.00</span>
                                </span>
                            </div>
                            <div class="basket">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div class="basket-item-count">
                                <span class="count">0</span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="cartDropdownLink">
                        <li>
                            <div class="cart-item product-summary">
                                <div class="row">
                                    <div class="col-xs-12">
                                        Your Shopping Cart is Empty.
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="cart-total">
                                <a href="index.php" class="btn btn-primary btn-block">Continue Shopping</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome & Google Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

<!-- Bootstrap 5 JS Bundle (only once – ensure it's not duplicated elsewhere) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>