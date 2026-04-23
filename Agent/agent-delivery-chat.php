<?php
session_start();
if(!isset($_SESSION['agent_id'])) { header('location: index.php'); exit(); }
include('../includes/config.php');
$delivery_id = intval($_GET['delivery_id']);
$agent_id = $_SESSION['agent_id'];

// Mark messages as read
mysqli_query($con, "UPDATE agent_delivery_messages SET is_read = 1 WHERE delivery_id = $delivery_id AND sender_type = 'delivery'");
?>
<!DOCTYPE html>
<html>
<head><title>Chat with Delivery</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .chat-container { max-width: 800px; margin: 20px auto; background: white; border-radius: 28px; border: 1px solid #EFE8E2; overflow: hidden; }
    .chat-header { background: #C47A5E; color: white; padding: 1rem; }
    .chat-messages { height: 400px; overflow-y: auto; padding: 1rem; background: #FCF9F5; }
    .message { margin-bottom: 1rem; display: flex; flex-direction: column; }
    .agent-message { align-items: flex-end; }
    .delivery-message { align-items: flex-start; }
    .message-content { max-width: 70%; padding: 8px 16px; border-radius: 18px; }
    .agent-message .message-content { background: #C47A5E; color: white; border-bottom-right-radius: 4px; }
    .delivery-message .message-content { background: #F0E9E3; color: #2A2826; border-bottom-left-radius: 4px; }
    .message-time { font-size: 0.65rem; color: #7A726C; margin-top: 4px; }
    .chat-input { display: flex; padding: 1rem; border-top: 1px solid #EFE8E2; }
    .chat-input input { flex: 1; border-radius: 40px; border: 1px solid #E0D6CE; padding: 0.6rem 1rem; }
    .chat-input button { background: #C47A5E; border: none; border-radius: 40px; padding: 0 1.5rem; margin-left: 0.5rem; color: white; }
</style>
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="container">
    <div class="chat-container">
        <div class="chat-header"><h5>Chat with Delivery #<?php echo $delivery_id; ?></h5></div>
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Type your reply...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var delivery_id = <?php echo $delivery_id; ?>;
function loadMessages() {
    $.get('get-agent-delivery-messages.php', {delivery_id: delivery_id}, function(data) {
        $('#chatMessages').html(data);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}
function sendMessage() {
    var msg = $('#messageInput').val();
    if(msg.trim() == '') return;
    $.post('send-agent-delivery-message.php', {delivery_id: delivery_id, message: msg}, function() {
        $('#messageInput').val('');
        loadMessages();
    });
}
setInterval(loadMessages, 3000);
loadMessages();
</script>
<?php include('includes/footer.php'); ?>
</body>
</html>