<?php
session_start();
if(!isset($_SESSION['agent_id'])) { exit; }
include('../includes/config.php');
$agent_id = intval($_GET['agent_id']);
$stmt = mysqli_prepare($con, "SELECT * FROM admin_agent_messages WHERE agent_id = ? ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'agent') ? 'agent-message' : 'admin-message';
    $sender = ($msg['sender_type'] == 'agent') ? 'You' : 'Admin';
    $time = date('H:i', strtotime($msg['created_at']));
    echo '<div class="message ' . $class . '">
            <div class="message-content">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
            <div class="message-time">' . $sender . ' • ' . $time . '</div>
          </div>';
}
?>