<?php
session_start();
if(!isset($_SESSION['agent_id'])) { exit; }
include('../includes/config.php');
$delivery_id = intval($_GET['delivery_id']);
$stmt = mysqli_prepare($con, "SELECT * FROM agent_delivery_messages WHERE delivery_id = ? ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $delivery_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'agent') ? 'agent-message' : 'delivery-message';
    $sender = ($msg['sender_type'] == 'agent') ? 'You' : 'Delivery';
    $time = date('H:i', strtotime($msg['created_at']));
    echo '<div class="message ' . $class . '">
            <div class="message-content">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
            <div class="message-time">' . $sender . ' • ' . $time . '</div>
          </div>';
}
?>