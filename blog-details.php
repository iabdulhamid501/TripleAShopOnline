<?php
session_start();
error_reporting(0);
include('includes/config.php');

$id = intval($_GET['id']);
$post = null;
$stmt = mysqli_prepare($con, "SELECT * FROM blog WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($res);

if(!$post) { header('location:blog.php'); exit(); }

// Handle comment submission
if(isset($_POST['submit_comment'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $comment = trim($_POST['comment']);
    if($name && $email && $comment) {
        $stmt = mysqli_prepare($con, "INSERT INTO blog_comments (post_id, user_name, user_email, comment, status) VALUES (?, ?, ?, ?, 'pending')");
        mysqli_stmt_bind_param($stmt, "isss", $id, $name, $email, $comment);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('Comment submitted for moderation.'); window.location='blog-details.php?id=$id';</script>";
    }
}

// Fetch approved comments
$comments = mysqli_query($con, "SELECT * FROM blog_comments WHERE post_id=$id AND status='approved' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($post['title']); ?> | Triple A ShopOnline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&family=Instrument+Serif&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FCF9F5; }
        .post-card, .comment-card { background: white; border-radius: 28px; border: 1px solid #EFE8E2; padding: 2rem; margin-bottom: 2rem; }
        .comment-item { border-bottom: 1px solid #F0E9E3; padding: 1rem 0; }
        .btn-submit { background: #C47A5E; border-radius: 40px; border: none; padding: 0.5rem 1.5rem; color: white; }
        .form-control { border-radius: 20px; border: 1px solid #E0D6CE; }
    </style>
</head>
<body>
<?php include('includes/top-header.php'); include('includes/main-header.php'); include('includes/menu-bar.php'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="post-card">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="text-muted mb-3"><i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?> | <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
                <?php
                $img = "assets/images/blog/" . $post['image'];
                if(!file_exists($img)) $img = "assets/images/blog-placeholder.jpg";
                ?>
                <img src="<?php echo $img; ?>" class="img-fluid rounded mb-4" style="max-height:400px; width:100%; object-fit:cover;">
                <div><?php echo $post['content']; ?></div>
            </div>

            <!-- Comments Section -->
            <div class="comment-card">
                <h3>Comments (<?php echo mysqli_num_rows($comments); ?>)</h3>
                <?php while($c = mysqli_fetch_assoc($comments)): ?>
                <div class="comment-item">
                    <strong><?php echo htmlspecialchars($c['user_name']); ?></strong> <small class="text-muted"><?php echo date('d M Y', strtotime($c['created_at'])); ?></small>
                    <p><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
                </div>
                <?php endwhile; ?>

                <h4 class="mt-4">Leave a Comment</h4>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                        <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Your Email" required></div>
                        <div class="col-12"><textarea name="comment" class="form-control" rows="4" placeholder="Your Comment" required></textarea></div>
                        <div class="col-12"><button type="submit" name="submit_comment" class="btn-submit">Post Comment</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
</body>
</html>