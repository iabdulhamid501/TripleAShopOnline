<?php
session_start();
if(!isset($_SESSION['agent_id'])) { exit; }
include('../includes/config.php');

$session_id = intval($_GET['session_id']);

$stmt = mysqli_prepare($con, "SELECT m.*, u.name as customer_name 
    FROM chat_messages m 
    LEFT JOIN users u ON (m.sender_type='customer' AND m.sender_id=u.id)
    WHERE m.session_id = ? 
    ORDER BY m.created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'agent') ? 'agent-message' : 'customer-message';
    $sender = ($msg['sender_type'] == 'agent') ? 'You' : htmlspecialchars($msg['customer_name']);
    $time = date('H:i', strtotime($msg['created_at']));
    
    echo '<div class="message ' . $class . '">
            <div class="message-content">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
            <div class="message-time">' . $time . '</div>
          </div>';
}
?>