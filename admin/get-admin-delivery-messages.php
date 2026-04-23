<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { exit; }
$delivery_id = intval($_GET['delivery_id']);
$stmt = mysqli_prepare($con, "SELECT * FROM admin_delivery_messages WHERE delivery_id = ? ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $delivery_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'admin') ? 'admin-message' : 'delivery-message';
    $sender = ($msg['sender_type'] == 'admin') ? 'You' : 'Delivery';
    $time = date('H:i', strtotime($msg['created_at']));
    echo '<div class="message ' . $class . '">
            <div class="message-content">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
            <div class="message-time">' . $sender . ' • ' . $time . '</div>
          </div>';
}
?>