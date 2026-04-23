<?php
session_start();
if(!isset($_SESSION['agent_id'])) { exit; }
include('../includes/config.php');
$session_id = intval($_POST['session_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$agent_id = $_SESSION['agent_id'];
$stmt = mysqli_prepare($con, "INSERT INTO chat_messages (session_id, sender_type, sender_id, message) VALUES (?, 'agent', ?, ?)");
mysqli_stmt_bind_param($stmt, "iis", $session_id, $agent_id, $message);
mysqli_stmt_execute($stmt);
?>