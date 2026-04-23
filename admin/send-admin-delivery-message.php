<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { exit; }
$delivery_id = intval($_POST['delivery_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$admin_id = $_SESSION['id'];
$stmt = mysqli_prepare($con, "INSERT INTO admin_delivery_messages (delivery_id, admin_id, sender_type, message) VALUES (?, ?, 'admin', ?)");
mysqli_stmt_bind_param($stmt, "iis", $delivery_id, $admin_id, $message);
mysqli_stmt_execute($stmt);
?>