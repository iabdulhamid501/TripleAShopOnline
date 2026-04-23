<?php
session_start();
if(!isset($_SESSION['delivery_id'])) { exit; }
include('../includes/config.php');
$delivery_id = intval($_POST['delivery_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$stmt = mysqli_prepare($con, "INSERT INTO admin_delivery_messages (delivery_id, sender_type, message) VALUES (?, 'delivery', ?)");
mysqli_stmt_bind_param($stmt, "is", $delivery_id, $message);
mysqli_stmt_execute($stmt);
?>