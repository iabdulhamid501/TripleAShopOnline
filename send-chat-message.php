<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['login']) == 0) { exit; }
$session_id = intval($_POST['session_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$user_id = intval($_SESSION['id']);
$stmt = mysqli_prepare($con, "INSERT INTO chat_messages (session_id, sender_type, sender_id, message) VALUES (?, 'customer', ?, ?)");
mysqli_stmt_bind_param($stmt, "iis", $session_id, $user_id, $message);
mysqli_stmt_execute($stmt);
?>