<?php
session_start();
include_once('includes/config.php'); // fixed path (relative to admin folder)

if(strlen($_SESSION["aid"]) == 0) { 
    header('location: logout.php'); 
    exit(); 
}

// Ensure agents table exists
$create_table = "CREATE TABLE IF NOT EXISTS `agents` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `fullname` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($con, $create_table);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Agents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #FCF9F5; font-family: 'Inter', sans-serif; }
        .card { border-radius: 24px; border: 1px solid #EFE8E2; }
        .btn-sm { border-radius: 40px; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2"><?php include_once('includes/sidebar.php'); ?></div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h2">Manage Agents</h1>
                    <a href="add-agent.php" class="btn btn-primary" style="background:#C47A5E; border-radius:40px;">+ Add Agent</a>
                </div>
                <hr>
                <div class="card p-3">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM agents ORDER BY id DESC");
                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)):
                        ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <a href="edit-agent.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete-agent.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete agent?')">Delete</a>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No agents found. Click "Add Agent" to create one.</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>
</body>
</html>