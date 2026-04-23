<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['login']) == 0) { exit; }
$session_id = intval($_GET['session_id']);
$stmt = mysqli_prepare($con, "SELECT m.*, u.name as sender_name 
    FROM chat_messages m 
    LEFT JOIN users u ON (m.sender_type='customer' AND m.sender_id=u.id)
    WHERE m.session_id = ? 
    ORDER BY m.created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'customer') ? 'customer-message' : 'agent-message';
    $sender = ($msg['sender_type'] == 'customer') ? 'You' : 'Agent';
    echo '<div class="message '.$class.'"><div class="message-content"><strong>'.$sender.':</strong><br>'.nl2br(htmlspecialchars($msg['message'])).'<br><small>'.$msg['created_at'].'</small></div></div>';
}
?>