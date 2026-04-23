<?php
session_start();
if(!isset($_SESSION['agent_id'])) { 
    header('location: index.php'); 
    exit(); 
}

if(file_exists('includes/config.php')) {
    include('includes/config.php');
} else {
    die('Configuration file not found.');
}

if(!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$agent_id = intval($_SESSION['agent_id']);

// Fetch closed chat sessions – include product_id for correct image path
$query = "SELECT cs.id as session_id, cs.order_id, cs.product_size, cs.created_at as chat_started, cs.updated_at as chat_closed,
                 u.name as customer_name, u.email as customer_email,
                 o.orderDate, o.orderStatus,
                 p.id as product_id, p.productName, p.productImage1
          FROM chat_sessions cs
          JOIN users u ON cs.customer_id = u.id
          JOIN orders o ON cs.order_id = o.id
          JOIN products p ON o.productId = p.id
          WHERE cs.agent_id = ? AND cs.status = 'closed'
          ORDER BY cs.updated_at DESC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Chat History | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FCF9F5; color: #2A2826; margin: 0; padding: 0; overflow-x: hidden; }
        .admin-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .admin-content-wrapper { display: flex; flex: 1; }
        .main-content { flex: 1; padding: 20px 24px; background: #FCF9F5; }
        .data-card { background: white; border-radius: 24px; border: 1px solid #EFE8E2; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .table-custom { width: 100%; border-collapse: collapse; }
        .table-custom th, .table-custom td { padding: 12px 10px; border-bottom: 1px solid #F0E9E3; vertical-align: middle; }
        .table-custom th { background: #FCF9F5; font-weight: 600; color: #4A4440; font-size: 0.85rem; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 12px; }
        .btn-view { background: #C47A5E; border: none; border-radius: 40px; padding: 0.3rem 1rem; font-weight: 500; font-size: 0.75rem; color: white; text-decoration: none; transition: 0.2s; display: inline-block; }
        .btn-view:hover { background: #A85E44; }
        .badge-closed { background: #6c757d; color: white; padding: 4px 12px; border-radius: 40px; font-size: 0.7rem; font-weight: 600; }
        .breadcrumb-custom { background: transparent; padding: 0; }
        .breadcrumb-custom .breadcrumb-item a { color: #C47A5E; text-decoration: none; }

        /* ========== CINEMATIC SOCIAL MEDIA TRANSCRIPT MODAL ========== */
        .modal-content {
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
        }
        .modal-header {
            background: linear-gradient(135deg, #C47A5E 0%, #A85E44 100%);
            border-bottom: none;
            padding: 1.2rem 1.5rem;
        }
        .modal-header .modal-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.3rem;
            letter-spacing: -0.3px;
            color: white;
        }
        .modal-body {
            background: #FCF9F5;
            padding: 1.5rem;
            max-height: 550px;
        }
        .transcript-message {
            margin-bottom: 1.2rem;
            display: flex;
            flex-direction: column;
            animation: fadeInUp 0.2s ease;
        }
        .transcript-agent {
            align-items: flex-end;
        }
        .transcript-customer {
            align-items: flex-start;
        }
        .transcript-message .message-content {
            max-width: 75%;
            padding: 10px 18px;
            border-radius: 24px;
            font-size: 0.9rem;
            line-height: 1.45;
            word-wrap: break-word;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        .transcript-agent .message-content {
            background: #C47A5E;
            color: white;
            border-bottom-right-radius: 4px;
            background: linear-gradient(135deg, #C47A5E 0%, #B56A4E 100%);
        }
        .transcript-customer .message-content {
            background: white;
            color: #2A2826;
            border-bottom-left-radius: 4px;
            border: 1px solid #EFE8E2;
        }
        .transcript-message .message-time {
            font-size: 0.65rem;
            color: #7A726C;
            margin-top: 4px;
            margin-left: 12px;
            margin-right: 12px;
            font-weight: 400;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .modal-footer {
            border-top: 1px solid #EFE8E2;
            background: white;
            padding: 0.8rem 1.5rem;
        }
        .btn-secondary {
            background: #F0E9E3;
            border: none;
            border-radius: 40px;
            padding: 0.4rem 1.2rem;
            font-weight: 500;
            color: #4A4440;
        }
        .btn-secondary:hover {
            background: #E0D6CE;
        }
        @media (max-width: 768px) { .main-content { padding: 16px; } .table-custom { min-width: 700px; } }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include('includes/header.php'); ?>
    <div class="admin-content-wrapper">
        <?php include('includes/sidebar.php'); ?>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Chat History</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Chat History</li>
                    </ol>
                </nav>
            </div>
            <div class="data-card">
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr><th>#</th><th>Image</th><th>Order ID</th><th>Customer</th><th>Product</th><th>Size</th><th>Started</th><th>Closed</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                        <?php if(mysqli_num_rows($result) > 0): $cnt=1; while($row = mysqli_fetch_assoc($result)): 
                            $img_path = "../admin/productimages/" . $row['product_id'] . "/" . $row['productImage1'];
                            if(!file_exists($img_path) || empty($row['productImage1'])) $img_path = "../admin/productimages/placeholder.jpg";
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td><img src="<?php echo $img_path; ?>" class="product-img"></td>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?><br><small><?php echo htmlspecialchars($row['customer_email']); ?></small></td>
                                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_size'] ?: 'N/A'); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($row['chat_started'])); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($row['chat_closed'])); ?></td>
                                <td><button class="btn-view" data-bs-toggle="modal" data-bs-target="#transcriptModal" data-session="<?php echo $row['session_id']; ?>" data-customer="<?php echo htmlspecialchars($row['customer_name']); ?>" data-product="<?php echo htmlspecialchars($row['productName']); ?>">View Transcript</button></td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="9" class="text-center">No closed chat sessions.<?td?>
                        <?php endif; ?>
                        </tbody>
                    <tr>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</div>

<!-- Transcript Modal -->
<div class="modal fade" id="transcriptModal" tabindex="-1" aria-labelledby="transcriptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transcriptModalLabel">Chat Transcript</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transcriptBody"><div class="text-center">Loading...</div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#transcriptModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var sessionId = button.data('session');
        var customerName = button.data('customer');
        var productName = button.data('product');
        var modal = $(this);
        modal.find('.modal-title').html('Chat with ' + customerName + ' about ' + productName);
        $.get('get-chat-transcript.php', {session_id: sessionId}, function(data) {
            $('#transcriptBody').html(data);
        });
    });
});
</script>
</body>
</html>