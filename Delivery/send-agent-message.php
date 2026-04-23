<?php
session_start();
if(!isset($_SESSION['delivery_id'])) { exit; }
include('../includes/config.php');

$delivery_id = intval($_POST['delivery_id']);
$agent_id = intval($_POST['agent_id']);
$message = trim($_POST['message']);
if($message == '') exit;

$stmt = mysqli_prepare($con, "INSERT INTO agent_delivery_messages (delivery_id, agent_id, sender_type, message) VALUES (?, ?, 'delivery', ?)");
mysqli_stmt_bind_param($stmt, "iis", $delivery_id, $agent_id, $message);
mysqli_stmt_execute($stmt);
?>