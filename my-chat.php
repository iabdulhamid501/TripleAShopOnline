<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['login']) == 0) { 
    header('location: login.php'); 
    exit(); 
}

$user_id = intval($_SESSION['id']);
$order_id = intval($_GET['orderid']);

// Verify order belongs to user and fetch product ID for image path
$order_check = mysqli_prepare($con, "SELECT o.id, p.id as product_id, p.productName, p.productImage1, c.categoryName, p.category 
    FROM orders o 
    JOIN products p ON o.productId = p.id 
    JOIN category c ON p.category = c.id 
    WHERE o.id = ? AND o.userId = ?");
mysqli_stmt_bind_param($order_check, "ii", $order_id, $user_id);
mysqli_stmt_execute($order_check);
$order_res = mysqli_stmt_get_result($order_check);
if(mysqli_num_rows($order_res) == 0) { 
    header('location: order-history.php'); 
    exit(); 
}
$order = mysqli_fetch_assoc($order_res);
$category_id = $order['category'];

// Categories that require size (adjust IDs as needed)
$size_categories = [6, 12, 9, 10, 11, 17]; // added 17 for Footwears
$require_size = in_array($category_id, $size_categories);

// Process form submission
$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = intval($_POST['agent_id']);
    $size = isset($_POST['size']) ? trim($_POST['size']) : '';
    
    if($require_size && empty($size)) {
        $message = '<div class="alert alert-danger">Please provide your size.</div>';
    } else {
        // Check if an open session already exists for this order and agent
        $check = mysqli_prepare($con, "SELECT id FROM chat_sessions WHERE order_id = ? AND customer_id = ? AND agent_id = ? AND status = 'open'");
        mysqli_stmt_bind_param($check, "iii", $order_id, $user_id, $agent_id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if(mysqli_stmt_num_rows($check) > 0) {
            mysqli_stmt_bind_result($check, $existing_id);
            mysqli_stmt_fetch($check);
            $session_id = $existing_id;
            mysqli_stmt_close($check);
        } else {
            // Create new session
            $insert = mysqli_prepare($con, "INSERT INTO chat_sessions (customer_id, agent_id, order_id, product_size, status, created_at) VALUES (?, ?, ?, ?, 'open', NOW())");
            mysqli_stmt_bind_param($insert, "iiis", $user_id, $agent_id, $order_id, $size);
            if(mysqli_stmt_execute($insert)) {
                $session_id = mysqli_insert_id($con);
            } else {
                $message = '<div class="alert alert-danger">Failed to start chat. Please try again.</div>';
            }
            mysqli_stmt_close($insert);
        }
        if(isset($session_id)) {
            header("location: chat.php?session_id=$session_id");
            exit();
        }
    }
}

// Fetch all agents
$agents_query = mysqli_query($con, "SELECT id, username, fullname FROM agents ORDER BY username");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Start Chat | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        /* FIX: Prevent any shaking/dancing */
        .chat-card,
        .product-info,
        .product-img,
        .btn-primary,
        .btn-secondary,
        .form-control,
        .form-select {
            transition: none !important;
            transform: none !important;
            animation: none !important;
        }
        .btn-primary:hover,
        .btn-secondary:hover {
            transform: none !important;
        }
        .chat-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
        }
        .product-info {
            background: #FCF9F5;
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
        }
        .form-control, .form-select {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
        }
        .btn-primary {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
        }
        .btn-primary:hover {
            background: #A85E44;
        }
        .btn-secondary {
            background: #E0D6CE;
            color: #2A2826;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #CEC3B9;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .alert {
            border-radius: 20px;
        }
    </style>
</head>
<body oncontextmenu="return false;">

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <nav aria-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="order-history.php">Order History</a></li>
            <li class="breadcrumb-item active" aria-current="page">Start Chat</li>
        </ol>
    </nav>

    <div class="chat-card">
        <h3 class="mb-3" style="font-family: 'Instrument Serif', serif;">
            <i class="fas fa-comments"></i> Chat about: <?php echo htmlspecialchars($order['productName']); ?>
        </h3>

        <div class="product-info">
            <?php
            // Use product ID for correct image path
            $product_id = $order['product_id'];
            $img_path = "admin/productimages/" . $product_id . "/" . $order['productImage1'];
            if(!file_exists($img_path) || empty($order['productImage1'])) {
                $img_path = "admin/productimages/placeholder.jpg";
            }
            ?>
            <img class="product-img" src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($order['productName']); ?>" onerror="this.src='admin/productimages/placeholder.jpg'">
            <div>
                <strong>Product:</strong> <?php echo htmlspecialchars($order['productName']); ?><br>
                <strong>Category:</strong> <?php echo htmlspecialchars($order['categoryName']); ?>
            </div>
        </div>

        <?php if(!empty($message)) echo $message; ?>

        <form method="post">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <div class="mb-3">
                <label class="form-label">Select Agent</label>
                <select name="agent_id" class="form-select" required>
                    <option value="">-- Choose an Agent --</option>
                    <?php while($agent = mysqli_fetch_assoc($agents_query)): ?>
                        <option value="<?php echo $agent['id']; ?>"><?php echo htmlspecialchars($agent['username'] . ' (' . $agent['fullname'] . ')'); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <?php if($require_size): ?>
            <div class="mb-3">
                <label class="form-label">Your Size (e.g., S, M, L, XL, 38, 40, etc.)</label>
                <input type="text" name="size" class="form-control" placeholder="Enter your size" required>
            </div>
            <?php endif; ?>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Start Chat <i class="fas fa-arrow-right ms-2"></i></button>
                <a href="order-history.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>