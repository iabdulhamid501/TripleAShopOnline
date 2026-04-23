<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { exit; }
$agent_id = intval($_GET['agent_id']);
$stmt = mysqli_prepare($con, "SELECT * FROM admin_agent_messages WHERE agent_id = ? ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while($msg = mysqli_fetch_assoc($result)) {
    $class = ($msg['sender_type'] == 'admin') ? 'admin-message' : 'agent-message';
    $sender = ($msg['sender_type'] == 'admin') ? 'You' : 'Agent';
    $time = date('H:i', strtotime($msg['created_at']));
    echo '<div class="message ' . $class . '">
            <div class="message-content">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
            <div class="message-time">' . $sender . ' • ' . $time . '</div>
          </div>';
}
?>