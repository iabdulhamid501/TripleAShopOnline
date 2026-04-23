<?php
session_start();
if(!isset($_SESSION['agent_id'])) { exit; }
include('../includes/config.php');
$agent_id = intval($_POST['agent_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$stmt = mysqli_prepare($con, "INSERT INTO admin_agent_messages (agent_id, sender_type, message) VALUES (?, 'agent', ?)");
mysqli_stmt_bind_param($stmt, "is", $agent_id, $message);
mysqli_stmt_execute($stmt);
?>