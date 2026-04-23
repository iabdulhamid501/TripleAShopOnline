<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['login']) == 0) { header('location: login.php'); exit(); }
$session_id = intval($_GET['session_id']);
// Verify session belongs to this customer
$check = mysqli_prepare($con, "SELECT id FROM chat_sessions WHERE id = ? AND customer_id = ?");
mysqli_stmt_bind_param($check, "ii", $session_id, $_SESSION['id']);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if(mysqli_stmt_num_rows($check) == 0) { header('location: order-history.php'); exit(); }
mysqli_stmt_close($check);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FCF9F5; }
        .chat-container { max-width: 800px; margin: 40px auto; background: white; border-radius: 28px; border: 1px solid #EFE8E2; overflow: hidden; }
        .chat-header { background: #C47A5E; color: white; padding: 1rem; }
        .chat-messages { height: 400px; overflow-y: auto; padding: 1rem; background: #FCF9F5; }
        .message { margin-bottom: 1rem; display: flex; }
        .customer-message { justify-content: flex-end; }
        .customer-message .message-content { background: #C47A5E; color: white; border-radius: 20px 20px 4px 20px; padding: 8px 16px; max-width: 70%; }
        .agent-message .message-content { background: #F0E9E3; color: #2A2826; border-radius: 20px 20px 20px 4px; padding: 8px 16px; max-width: 70%; }
        .chat-input { display: flex; padding: 1rem; border-top: 1px solid #EFE8E2; }
        .chat-input input { flex: 1; border-radius: 40px; border: 1px solid #E0D6CE; padding: 0.6rem 1rem; }
        .chat-input button { background: #C47A5E; border: none; border-radius: 40px; padding: 0 1.5rem; margin-left: 0.5rem; color: white; font-weight: 600; }
    </style>
</head>
<body>
<?php include('includes/top-header.php'); include('includes/main-header.php'); include('includes/menu-bar.php'); ?>
<div class="container">
    <div class="chat-container">
        <div class="chat-header"><h5>Chat with Agent</h5></div>
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Type your message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var session_id = <?php echo $session_id; ?>;
function loadMessages() {
    $.get('get-chat-messages.php', {session_id: session_id}, function(data) {
        $('#chatMessages').html(data);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    });
}
function sendMessage() {
    var msg = $('#messageInput').val();
    if(msg.trim() == '') return;
    $.post('send-chat-message.php', {session_id: session_id, message: msg}, function() {
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