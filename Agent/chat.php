<?php
session_start();
if(!isset($_SESSION['agent_id'])) { 
    header('location: index.php'); 
    exit(); 
}

// Include database config
if(file_exists('includes/config.php')) {
    include('includes/config.php');
} else {
    die('Configuration file not found.');
}

if(!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$session_id = intval($_GET['session_id']);
$agent_id = intval($_SESSION['agent_id']);

// ========== CLOSE CHAT HANDLER ==========
if(isset($_GET['close'])) {
    $close_stmt = mysqli_prepare($con, "UPDATE chat_sessions SET status = 'closed' WHERE id = ? AND agent_id = ?");
    mysqli_stmt_bind_param($close_stmt, "ii", $session_id, $agent_id);
    mysqli_stmt_execute($close_stmt);
    mysqli_stmt_close($close_stmt);
    header('location: resolved-chats.php');
    exit();
}

// Fetch session details with customer, order, and product info (including image)
// IMPORTANT: Select product_id (p.id) for the correct image folder
$query = "SELECT cs.*, u.name as customer_name, u.email as customer_email, 
                 o.id as order_id, p.id as product_id, p.productName, p.productImage1, cs.product_size
          FROM chat_sessions cs
          JOIN users u ON cs.customer_id = u.id
          JOIN orders o ON cs.order_id = o.id
          JOIN products p ON o.productId = p.id
          WHERE cs.id = ? AND cs.agent_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ii", $session_id, $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) == 0) {
    header('location: dashboard.php');
    exit();
}
$session = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$customer_name = htmlspecialchars($session['customer_name']);
$customer_email = htmlspecialchars($session['customer_email']);
$order_id = $session['order_id'];
$product_id = $session['product_id'];
$product_name = htmlspecialchars($session['productName']);
$product_size = htmlspecialchars($session['product_size'] ?: 'N/A');
$product_image = $session['productImage1'];

// Correct image path: ../admin/productimages/{product_id}/{productImage1}
$image_path = "../admin/productimages/" . $product_id . "/" . $product_image;
if(!file_exists($image_path) || empty($product_image)) {
    $image_path = "../admin/productimages/placeholder.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Agent Chat | Triple A ShopOnline</title>
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
        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-content-wrapper {
            display: flex;
            flex: 1;
        }
        .main-content {
            flex: 1;
            padding: 20px 24px;
            background: #FCF9F5;
        }
        /* Telegram-style chat layout */
        .chat-layout {
            display: flex;
            gap: 24px;
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            overflow: hidden;
            min-height: 600px;
        }
        /* Chat area (left) */
        .chat-area {
            flex: 2;
            display: flex;
            flex-direction: column;
            background: white;
        }
        .chat-header {
            background: #C47A5E;
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #FCF9F5;
            min-height: 500px;
            max-height: 600px;
        }
        /* Message bubbles */
        .message {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
        }
        .agent-message {
            align-items: flex-end;
        }
        .customer-message {
            align-items: flex-start;
        }
        .message-content {
            max-width: 70%;
            padding: 8px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            position: relative;
        }
        .agent-message .message-content {
            background: #C47A5E;
            color: white;
            border-bottom-right-radius: 4px;
        }
        .customer-message .message-content {
            background: #F0E9E3;
            color: #2A2826;
            border-bottom-left-radius: 4px;
        }
        .message-time {
            font-size: 0.65rem;
            color: #7A726C;
            margin-top: 4px;
            margin-left: 8px;
            margin-right: 8px;
        }
        /* Chat input */
        .chat-input {
            display: flex;
            padding: 1rem;
            border-top: 1px solid #EFE8E2;
            background: white;
        }
        .chat-input input {
            flex: 1;
            border-radius: 40px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
        }
        .chat-input button {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0 1.5rem;
            margin-left: 0.5rem;
            color: white;
            font-weight: 600;
        }
        /* Info panel (right) */
        .info-panel {
            flex: 1;
            background: #FCF9F5;
            border-left: 1px solid #EFE8E2;
            padding: 1.5rem;
        }
        .info-panel h6 {
            font-weight: 700;
            color: #4A4440;
            margin-bottom: 1rem;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 4px;
        }
        .customer-detail {
            margin-bottom: 1.5rem;
        }
        .order-detail {
            margin-top: 1rem;
        }
        .order-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #EFE8E2;
        }
        .info-row {
            margin-bottom: 0.75rem;
        }
        .info-label {
            font-weight: 600;
            font-size: 0.75rem;
            color: #7A726C;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 0.9rem;
            color: #2A2826;
        }
        .btn-close-chat {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 40px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            color: white;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-close-chat:hover {
            background: rgba(255,255,255,0.4);
            color: white;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .chat-layout {
                flex-direction: column;
            }
            .info-panel {
                border-left: none;
                border-top: 1px solid #EFE8E2;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <?php include('includes/sidebar.php'); ?>
        
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Agent Chat</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb veloria-breadcrumb" style="background:transparent; padding:0;">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chat</li>
                    </ol>
                </nav>
            </div>

            <div class="chat-layout">
                <!-- Chat Area -->
                <div class="chat-area">
                    <div class="chat-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chat Session #<?php echo $session_id; ?></h5>
                        <a href="chat.php?session_id=<?php echo $session_id; ?>&close=1" class="btn-close-chat" onclick="return confirm('Mark this chat as resolved?')">Close Chat</a>
                    </div>
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input">
                        <input type="text" id="messageInput" placeholder="Type your reply...">
                        <button onclick="sendMessage()">Send</button>
                    </div>
                </div>

                <!-- Info Panel (Customer + Order Details) -->
                <div class="info-panel">
                    <h6><i class="fas fa-user-circle"></i> Customer</h6>
                    <div class="customer-detail">
                        <div class="info-row">
                            <div class="info-label">Name</div>
                            <div class="info-value"><?php echo $customer_name; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo $customer_email; ?></div>
                        </div>
                    </div>

                    <h6><i class="fas fa-shopping-bag"></i> Order Details</h6>
                    <div class="order-detail">
                        <div class="info-row">
                            <div class="info-label">Order ID</div>
                            <div class="info-value">#<?php echo $order_id; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Product</div>
                            <div class="info-value"><?php echo $product_name; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Size</div>
                            <div class="info-value"><?php echo $product_size; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Product Image</div>
                            <img src="<?php echo $image_path; ?>" class="order-img" alt="Product Image" onerror="this.src='../admin/productimages/placeholder.jpg'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
var session_id = <?php echo $session_id; ?>;
function loadMessages() {
    $.get('get-messages.php', {session_id: session_id}, function(data) {
        $('#chatMessages').html(data);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}
function sendMessage() {
    var msg = $('#messageInput').val();
    if(msg.trim() == '') return;
    $.post('send-message.php', {session_id: session_id, message: msg}, function() {
        $('#messageInput').val('');
        loadMessages();
    });
}
setInterval(loadMessages, 3000);
loadMessages();
</script>
</body>
</html>