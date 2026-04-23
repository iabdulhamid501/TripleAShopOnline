<?php
session_start();
include_once('../includes/config.php');
if(strlen($_SESSION["aid"])==0) { header('location:logout.php'); exit(); }
$id = intval($_GET['id']);
mysqli_query($con, "DELETE FROM blog WHERE id=$id");
echo "<script>alert('Deleted'); window.location='manage-blog.php';</script>";
?>