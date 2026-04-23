<?php
session_start();
if(!isset($_SESSION['agent_id'])) { exit; }
include('../includes/config.php');
$delivery_id = intval($_POST['delivery_id']);
$message = trim($_POST['message']);
if($message == '') exit;
$agent_id = $_SESSION['agent_id'];
$stmt = mysqli_prepare($con, "INSERT INTO agent_delivery_messages (delivery_id, agent_id, sender_type, message) VALUES (?, ?, 'agent', ?)");
mysqli_stmt_bind_param($stmt, "iis", $delivery_id, $agent_id, $message);
mysqli_stmt_execute($stmt);
?>