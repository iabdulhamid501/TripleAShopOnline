<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { 
    header('location: logout.php'); 
    exit(); 
}

$delivery_id = isset($_GET['delivery_id']) ? intval($_GET['delivery_id']) : 0;
if($delivery_id == 0) {
    header('location: delivery-chats.php');
    exit();
}

// Get delivery staff info
$delivery_info = mysqli_fetch_assoc(mysqli_query($con, "SELECT username, fullname FROM delivery WHERE id = $delivery_id"));

// Mark messages as read (admin has seen them)
mysqli_query($con, "UPDATE admin_delivery_messages SET is_read = 1 WHERE delivery_id = $delivery_id AND sender_type = 'delivery' AND is_read = 0");

// Handle sending new message
if(isset($_POST['send_message'])) {
    $message = trim($_POST['message']);
    if(!empty($message)) {
        $stmt = mysqli_prepare($con, "INSERT INTO admin_delivery_messages (delivery_id, admin_id, sender_type, message) VALUES (?, ?, 'admin', ?)");
        $admin_id = $_SESSION['aid'];
        mysqli_stmt_bind_param($stmt, "iis", $delivery_id, $admin_id, $message);
        mysqli_stmt_execute($stmt);
    }
    header("Location: admin-delivery-chat.php?delivery_id=$delivery_id");
    exit();
}

// Fetch all messages
$messages = mysqli_query($con, "SELECT * FROM admin_delivery_messages WHERE delivery_id = $delivery_id ORDER BY created_at ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Chat with <?php echo htmlspecialchars($delivery_info['username']); ?> | Admin</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
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
            display: flex;
            flex-direction: column;
        }
        /* Chat container */
        .chat-container {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 160px);
            overflow: hidden;
        }
        .chat-header {
            background: white;
            border-bottom: 1px solid #F0E9E3;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .chat-header .back-btn {
            color: #C47A5E;
            font-size: 1.2rem;
            transition: 0.2s;
        }
        .chat-header .back-btn:hover {
            color: #A85E44;
        }
        .chat-header h5 {
            margin: 0;
            font-weight: 600;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #FCF9F5;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        /* Message bubbles */
        .message {
            display: flex;
            max-width: 70%;
        }
        .message.admin {
            align-self: flex-end;
            justify-content: flex-end;
        }
        .message.delivery {
            align-self: flex-start;
        }
        .bubble {
            padding: 0.6rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            line-height: 1.4;
            word-wrap: break-word;
            max-width: 100%;
        }
        .admin .bubble {
            background: #C47A5E;
            color: white;
            border-bottom-right-radius: 4px;
        }
        .delivery .bubble {
            background: #E9E5E1;
            color: #2A2826;
            border-bottom-left-radius: 4px;
        }
        .time {
            font-size: 0.65rem;
            margin-top: 0.2rem;
            opacity: 0.7;
            text-align: right;
        }
        .admin .time {
            color: #C47A5E;
        }
        .delivery .time {
            color: #7A726C;
        }
        /* Chat input */
        .chat-input {
            background: white;
            border-top: 1px solid #F0E9E3;
            padding: 1rem 1.5rem;
        }
        .input-group-custom {
            display: flex;
            gap: 0.8rem;
        }
        .input-group-custom input {
            flex: 1;
            border: 1px solid #E2DAD4;
            border-radius: 40px;
            padding: 0.6rem 1.2rem;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
        }
        .input-group-custom input:focus {
            outline: none;
            border-color: #C47A5E;
        }
        .btn-send {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.6rem 1.5rem;
            color: white;
            font-weight: 500;
            transition: 0.2s;
        }
        .btn-send:hover {
            background: #A85E44;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 12px;
            }
            .chat-container {
                height: calc(100vh - 120px);
            }
            .message {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include_once('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        
        <div class="main-content">
            <div class="chat-container">
                <div class="chat-header">
                    <a href="delivery-chats.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
                    <i class="fas fa-truck" style="color: #C47A5E;"></i>
                    <h5>Chat with <?php echo htmlspecialchars($delivery_info['username']); ?></h5>
                </div>
                
                <div class="chat-messages" id="chatMessages">
                    <?php while($msg = mysqli_fetch_assoc($messages)): 
                        $is_admin = ($msg['sender_type'] == 'admin');
                        $time = date('H:i', strtotime($msg['created_at']));
                    ?>
                        <div class="message <?php echo $is_admin ? 'admin' : 'delivery'; ?>">
                            <div class="bubble">
                                <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                <div class="time"><?php echo $time; ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="chat-input">
                    <form method="post" action="">
                        <div class="input-group-custom">
                            <input type="text" name="message" placeholder="Type your message..." required autocomplete="off">
                            <button type="submit" name="send_message" class="btn-send">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-scroll to bottom on load
    const chatMessages = document.getElementById('chatMessages');
    if(chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
</script>
</body>
</html>