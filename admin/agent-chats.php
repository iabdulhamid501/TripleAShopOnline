<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { header('location: logout.php'); exit(); }

// Fetch agents with message threads
$query = "SELECT a.id, a.username, a.fullname, 
                 (SELECT COUNT(*) FROM admin_agent_messages WHERE agent_id = a.id AND sender_type = 'agent' AND is_read = 0) as unread_count,
                 (SELECT message FROM admin_agent_messages WHERE agent_id = a.id ORDER BY created_at DESC LIMIT 1) as last_message,
                 (SELECT created_at FROM admin_agent_messages WHERE agent_id = a.id ORDER BY created_at DESC LIMIT 1) as last_time
          FROM agents a
          WHERE EXISTS (SELECT 1 FROM admin_agent_messages WHERE agent_id = a.id)
          ORDER BY last_time DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Agent Chats | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #FCF9F5; font-family: 'Inter', sans-serif; }
        .card { border-radius: 24px; border: 1px solid #EFE8E2; }
        .agent-item { cursor: pointer; transition: 0.2s; }
        .agent-item:hover { background: #FCF9F5; }
        .unread-badge { background: #C47A5E; color: white; border-radius: 20px; padding: 2px 8px; font-size: 0.7rem; }
        .last-message { font-size: 0.8rem; color: #7A726C; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2"><?php include_once('includes/sidebar.php'); ?></div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <h1 class="h2">Agent Conversations</h1>
                <hr>
                <div class="card p-3">
                    <div class="list-group list-group-flush">
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <a href="agent-chat.php?agent_id=<?php echo $row['id']; ?>" class="list-group-item list-group-item-action agent-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($row['fullname'] ?: $row['username']); ?></strong>
                                    <?php if($row['unread_count'] > 0): ?>
                                        <span class="unread-badge ms-2"><?php echo $row['unread_count']; ?> new</span>
                                    <?php endif; ?>
                                    <div class="last-message"><?php echo htmlspecialchars(substr($row['last_message'], 0, 50)) . (strlen($row['last_message']) > 50 ? '...' : ''); ?></div>
                                </div>
                                <small class="text-muted"><?php echo date('d M H:i', strtotime($row['last_time'])); ?></small>
                            </div>
                        </a>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($result) == 0): ?>
                            <div class="text-center p-4">No agent messages yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>
</body>
</html>