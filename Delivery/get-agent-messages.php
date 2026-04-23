<?php
session_start();
if(!isset($_SESSION['delivery_id'])) { exit; }
include('../includes/config.php');

$delivery_id = intval($_GET['delivery_id']);
$agent_id = intval($_GET['agent_id']);

$stmt = mysqli_prepare($con, "SELECT * FROM agent_delivery_messages 
                              WHERE delivery_id = ? AND agent_id = ? 
                              ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "ii", $delivery_id, $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'delivery') ? 'delivery-message' : 'agent-message';
    $sender = ($msg['sender_type'] == 'delivery') ? 'You' : 'Agent';
    $time = date('H:i', strtotime($msg['created_at']));
    echo '<div class="message ' . $class . '">
            <div class="message-content">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
            <div class="message-time">' . $sender . ' • ' . $time . '</div>
          </div>';
}
?>