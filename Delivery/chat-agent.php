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

// Handle AJAX requests
if(isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if($action == 'get_messages' && isset($_GET['agent_id'])) {
        $agent_id = intval($_GET['agent_id']);
        $messages = $con->query("SELECT * FROM agent_delivery_messages 
                                  WHERE delivery_id = $delivery_id AND agent_id = $agent_id 
                                  ORDER BY created_at ASC");
        $html = '';
        while($msg = $messages->fetch_assoc()) {
            $is_delivery = ($msg['sender_type'] == 'delivery');
            $time = date('H:i', strtotime($msg['created_at']));
            $html .= '<div class="message ' . ($is_delivery ? 'delivery-message' : 'agent-message') . '">
                        <div class="message-bubble">
                            ' . nl2br(htmlspecialchars($msg['message'])) . '
                            <div class="message-time">' . $time . '</div>
                        </div>
                      </div>';
        }
        echo $html;
        exit;
    }
    
    if($action == 'send_message' && isset($_POST['agent_id']) && isset($_POST['message'])) {
        $agent_id = intval($_POST['agent_id']);
        $message = trim($_POST['message']);
        if(!empty($message)) {
            $stmt = $con->prepare("INSERT INTO agent_delivery_messages (delivery_id, agent_id, sender_type, message) VALUES (?, ?, 'delivery', ?)");
            $stmt->bind_param("iis", $delivery_id, $agent_id, $message);
            $stmt->execute();
        }
        echo 'ok';
        exit;
    }
}

// Fetch all agents with last message preview and unread count (for delivery)
$agents_query = $con->query("
    SELECT a.id, a.username, a.fullname,
        (SELECT message FROM agent_delivery_messages 
         WHERE delivery_id = $delivery_id AND agent_id = a.id 
         ORDER BY created_at DESC LIMIT 1) as last_message,
        (SELECT created_at FROM agent_delivery_messages 
         WHERE delivery_id = $delivery_id AND agent_id = a.id 
         ORDER BY created_at DESC LIMIT 1) as last_time,
        (SELECT COUNT(*) FROM agent_delivery_messages 
         WHERE delivery_id = $delivery_id AND agent_id = a.id 
         AND sender_type = 'agent' AND is_read = 0) as unread_count
    FROM agents a
    ORDER BY last_time DESC
");
$agents = [];
$agent_list = [];
if($agents_query) {
    while($row = $agents_query->fetch_assoc()) {
        $agents[] = $row;
        $agent_list[$row['id']] = $row;
    }
}

$selected_agent_id = isset($_GET['agent_id']) ? intval($_GET['agent_id']) : (count($agents) > 0 ? $agents[0]['id'] : 0);

// If agent selected, mark his messages as read
if($selected_agent_id > 0) {
    $con->query("UPDATE agent_delivery_messages SET is_read = 1 
                 WHERE delivery_id = $delivery_id AND agent_id = $selected_agent_id 
                 AND sender_type = 'agent' AND is_read = 0");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Chat with Agent | Delivery Panel</title>
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
        }
        /* WhatsApp-like chat container */
        .whatsapp-container {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            overflow: hidden;
            display: flex;
            height: calc(100vh - 160px);
            min-height: 500px;
        }
        /* Contacts sidebar */
        .contacts-sidebar {
            width: 320px;
            background: #FCF9F5;
            border-right: 1px solid #EFE8E2;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .contacts-header {
            padding: 1rem;
            background: white;
            border-bottom: 1px solid #EFE8E2;
            font-weight: 600;
        }
        .contact-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            cursor: pointer;
            transition: 0.2s;
            border-bottom: 1px solid #F0E9E3;
        }
        .contact-item:hover {
            background: #F5F0EB;
        }
        .contact-item.active {
            background: #EFE8E2;
        }
        .contact-avatar {
            width: 48px;
            height: 48px;
            background: #C47A5E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 0.8rem;
        }
        .contact-info {
            flex: 1;
            min-width: 0;
        }
        .contact-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .contact-last-msg {
            font-size: 0.75rem;
            color: #7A726C;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .contact-time {
            font-size: 0.65rem;
            color: #7A726C;
            text-align: right;
        }
        .unread-badge {
            background: #C62828;
            color: white;
            border-radius: 20px;
            padding: 0.1rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        /* Chat area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }
        .chat-header {
            background: white;
            padding: 0.8rem 1.2rem;
            border-bottom: 1px solid #EFE8E2;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .chat-header .agent-name {
            font-weight: 600;
            font-size: 1rem;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.2rem;
            background: #FCF9F5;
            display: flex;
            flex-direction: column;
        }
        .message {
            display: flex;
            margin-bottom: 1rem;
        }
        .delivery-message {
            justify-content: flex-end;
        }
        .agent-message {
            justify-content: flex-start;
        }
        .message-bubble {
            max-width: 70%;
            padding: 0.6rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .delivery-message .message-bubble {
            background: #C47A5E;
            color: white;
            border-bottom-right-radius: 4px;
        }
        .agent-message .message-bubble {
            background: #F0E9E3;
            color: #2A2826;
            border-bottom-left-radius: 4px;
        }
        .message-time {
            font-size: 0.65rem;
            margin-top: 4px;
            text-align: right;
            color: #7A726C;
        }
        .delivery-message .message-time {
            color: #E3DCD5;
        }
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
        .empty-chat {
            text-align: center;
            padding: 2rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 12px;
            }
            .whatsapp-container {
                height: calc(100vh - 130px);
            }
            .contacts-sidebar {
                width: 80px;
            }
            .contacts-sidebar .contact-info,
            .contacts-sidebar .contact-time {
                display: none;
            }
            .contact-avatar {
                margin-right: 0;
            }
            .back-btn {
                display: inline-block !important;
            }
            .contacts-sidebar.active-mobile {
                display: flex;
                width: 100%;
                position: absolute;
                z-index: 10;
                background: white;
            }
            .chat-area.hide-mobile {
                display: none;
            }
        }
        .back-btn {
            display: none;
            color: #C47A5E;
            font-size: 1.2rem;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <?php include('includes/sidebar.php'); ?>
        
        <div class="main-content">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" style="color:#C47A5E;">Dashboard</a></li>
                    <li class="breadcrumb-item active">Chat with Agent</li>
                </ol>
            </nav>

            <div class="whatsapp-container">
                <!-- Contacts Sidebar -->
                <div class="contacts-sidebar" id="contactsSidebar">
                    <div class="contacts-header">
                        <i class="fas fa-comments"></i> Agents
                    </div>
                    <?php foreach($agents as $agent): 
                        $last_msg = htmlspecialchars($agent['last_message'] ?? 'No messages yet');
                        $last_time = $agent['last_time'] ? date('H:i', strtotime($agent['last_time'])) : '';
                        $unread = $agent['unread_count'] ?? 0;
                    ?>
                    <div class="contact-item <?php echo ($selected_agent_id == $agent['id']) ? 'active' : ''; ?>" 
                         data-agent-id="<?php echo $agent['id']; ?>"
                         onclick="selectAgent(<?php echo $agent['id']; ?>)">
                        <div class="contact-avatar">
                            <?php echo strtoupper(substr($agent['username'], 0, 1)); ?>
                        </div>
                        <div class="contact-info">
                            <div class="contact-name">
                                <?php echo htmlspecialchars($agent['username']); ?>
                                <?php if($unread > 0): ?>
                                    <span class="unread-badge"><?php echo $unread; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="contact-last-msg"><?php echo $last_msg; ?></div>
                        </div>
                        <div class="contact-time"><?php echo $last_time; ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if(count($agents) == 0): ?>
                    <div class="p-3 text-center text-muted">No agents available</div>
                    <?php endif; ?>
                </div>

                <!-- Chat Area -->
                <div class="chat-area" id="chatArea">
                    <?php if($selected_agent_id > 0 && isset($agent_list[$selected_agent_id])): 
                        $current_agent = $agent_list[$selected_agent_id];
                    ?>
                    <div class="chat-header">
                        <a href="javascript:void(0)" class="back-btn" onclick="toggleMobileSidebar()"><i class="fas fa-arrow-left"></i></a>
                        <div class="contact-avatar" style="width:40px;height:40px;font-size:1rem;">
                            <?php echo strtoupper(substr($current_agent['username'], 0, 1)); ?>
                        </div>
                        <div class="agent-name"><?php echo htmlspecialchars($current_agent['username']); ?></div>
                    </div>
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input">
                        <input type="text" id="messageInput" placeholder="Type a message...">
                        <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i> Send</button>
                    </div>
                    <?php else: ?>
                    <div class="empty-chat">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <p>Select an agent from the left to start chatting</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<?php if($selected_agent_id > 0): ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
var delivery_id = <?php echo $delivery_id; ?>;
var agent_id = <?php echo $selected_agent_id; ?>;

function loadMessages() {
    $.get(window.location.pathname, {action: 'get_messages', agent_id: agent_id}, function(data) {
        $('#chatMessages').html(data);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}

function sendMessage() {
    var msg = $('#messageInput').val();
    if(msg.trim() == '') return;
    $.post(window.location.pathname, {action: 'send_message', agent_id: agent_id, message: msg}, function() {
        $('#messageInput').val('');
        loadMessages();
        // After sending, refresh sidebar to update last message preview (simple reload after 1 sec)
        setTimeout(function() {
            location.reload();
        }, 1000);
    });
}

function selectAgent(id) {
    window.location.href = '?agent_id=' + id;
}

function toggleMobileSidebar() {
    $('#contactsSidebar').toggleClass('active-mobile');
    $('#chatArea').toggleClass('hide-mobile');
}

setInterval(loadMessages, 3000);
loadMessages();
</script>
<?php else: ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php endif; ?>
</body>
</html>