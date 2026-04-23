<?php
session_start();
if(!isset($_SESSION['agent_id'])) { header('location: index.php'); exit(); }
include('../includes/config.php');
$agent_id = $_SESSION['agent_id'];

$deliveries = mysqli_query($con, "SELECT d.*, 
    (SELECT COUNT(*) FROM agent_delivery_messages WHERE delivery_id = d.id AND sender_type = 'delivery' AND is_read = 0) as unread_count
    FROM delivery d ORDER BY d.id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Delivery Chats</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<?php include('includes/header.php'); ?>
<div class="container mt-4">
    <h2>Chat with Delivery Staff</h2>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Name</th><th>Unread</th><th>Action</th></tr></thead>
        <tbody>
        <?php while($del = mysqli_fetch_assoc($deliveries)): ?>
        <tr>
            <td><?php echo $del['id']; ?></td>
            <td><?php echo htmlspecialchars($del['username']); ?></td>
            <td><?php echo $del['unread_count']; ?></td>
            <td><a href="agent-delivery-chat.php?delivery_id=<?php echo $del['id']; ?>" class="btn btn-sm btn-primary">Chat</a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include('includes/footer.php'); ?>
</body>
</html>