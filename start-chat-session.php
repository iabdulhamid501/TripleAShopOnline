<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['login']) == 0) { header('location: login.php'); exit(); }
$user_id = intval($_SESSION['id']);
$order_id = intval($_POST['order_id']);
$agent_id = intval($_POST['agent_id']);
$size = isset($_POST['size']) ? trim($_POST['size']) : '';

// Check if an open session already exists for this order and agent
$check = mysqli_prepare($con, "SELECT id FROM chat_sessions WHERE order_id = ? AND customer_id = ? AND agent_id = ? AND status = 'open'");
mysqli_stmt_bind_param($check, "iii", $order_id, $user_id, $agent_id);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if(mysqli_stmt_num_rows($check) > 0) {
    mysqli_stmt_bind_result($check, $existing_id);
    mysqli_stmt_fetch($check);
    $session_id = $existing_id;
} else {
    // Create new session
    $stmt = mysqli_prepare($con, "INSERT INTO chat_sessions (customer_id, agent_id, order_id, product_size, status) VALUES (?, ?, ?, ?, 'open')");
    mysqli_stmt_bind_param($stmt, "iiis", $user_id, $agent_id, $order_id, $size);
    mysqli_stmt_execute($stmt);
    $session_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
}
mysqli_stmt_close($check);
header("Location: chat.php?session_id=$session_id");
exit();
?>