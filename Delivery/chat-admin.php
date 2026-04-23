<?php
session_start();
if(!isset($_SESSION['delivery_id'])) { 
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

$delivery_id = intval($_SESSION['delivery_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Chat with Admin | Triple A ShopOnline</title>
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
        /* Modern chat container */
        .chat-wrapper {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 180px);
            min-height: 500px;
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
            padding: 1.5rem;
            background: #FCF9F5;
        }
        /* Message bubbles */
        .message {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
        }
        .delivery-message {
            align-items: flex-end;
        }
        .admin-message {
            align-items: flex-start;
        }
        .message-content {
            max-width: 70%;
            padding: 10px 18px;
            border-radius: 24px;
            font-size: 0.9rem;
            line-height: 1.4;
            word-wrap: break-word;
            position: relative;
        }
        .delivery-message .message-content {
            background: #C47A5E;
            color: white;
            border-bottom-right-radius: 4px;
        }
        .admin-message .message-content {
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
        /* Input area */
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
            font-family: 'Inter', sans-serif;
        }
        .chat-input button {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0 1.5rem;
            margin-left: 0.5rem;
            color: white;
            font-weight: 600;
            transition: 0.2s;
        }
        .chat-input button:hover {
            background: #A85E44;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .chat-wrapper {
                height: calc(100vh - 140px);
            }
            .message-content {
                max-width: 85%;
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
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chat with Admin</li>
                </ol>
            </nav>

            <div class="chat-wrapper">
                <div class="chat-header">
                    <h5 class="mb-0"><i class="fas fa-user-shield"></i> Admin Support</h5>
                </div>
                <div class="chat-messages" id="chatMessages"></div>
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Type your message...">
                    <button onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
var delivery_id = <?php echo $delivery_id; ?>;

function loadMessages() {
    $.get('get-admin-messages.php', {delivery_id: delivery_id}, function(data) {
        $('#chatMessages').html(data);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}

function sendMessage() {
    var msg = $('#messageInput').val();
    if(msg.trim() == '') return;
    $.post('send-admin-message.php', {delivery_id: delivery_id, message: msg}, function() {
        $('#messageInput').val('');
        loadMessages();
    });
}

setInterval(loadMessages, 3000);
loadMessages();
</script>
</body>
</html>