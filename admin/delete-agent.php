<?php
session_start();
include_once('../includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { header('location: logout.php'); exit(); }
$id = intval($_GET['id']);
mysqli_query($con, "DELETE FROM agents WHERE id=$id");
header('location: manage-agents.php');
?>