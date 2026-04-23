<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { exit; }
$agent_id = intval($_POST['agent_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$admin_id = intval($_SESSION['id']);
$stmt = mysqli_prepare($con, "INSERT INTO admin_agent_messages (agent_id, admin_id, sender_type, message) VALUES (?, ?, 'admin', ?)");
mysqli_stmt_bind_param($stmt, "iis", $agent_id, $admin_id, $message);
mysqli_stmt_execute($stmt);
?>