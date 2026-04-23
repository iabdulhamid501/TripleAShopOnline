<?php
// Admin Sidebar - VELORIA Style
// Place this file in admin/includes/sidebar.php
// Ensure $con (database connection) available and session is started

// Fetch order counts for the sidebar badges
$ret = mysqli_query($con, "SELECT 
    COUNT(id) AS totalorders,
    SUM(CASE WHEN orderStatus = '' OR orderStatus IS NULL THEN 1 ELSE 0 END) AS neworders,
    SUM(CASE WHEN orderStatus = 'Packed' THEN 1 ELSE 0 END) AS packedorders,
    SUM(CASE WHEN orderStatus = 'Dispatched' THEN 1 ELSE 0 END) AS dispatchedorders,
    SUM(CASE WHEN orderStatus = 'In Transit' THEN 1 ELSE 0 END) AS intransitorders,
    SUM(CASE WHEN orderStatus = 'Out For Delivery' THEN 1 ELSE 0 END) AS outfdorders,
    SUM(CASE WHEN orderStatus = 'Delivered' THEN 1 ELSE 0 END) AS deliveredorders,
    SUM(CASE WHEN orderStatus = 'Cancelled' THEN 1 ELSE 0 END) AS cancelledorders
    FROM orders");
$results = mysqli_fetch_assoc($ret);
$torders       = (int)($results['totalorders'] ?? 0);
$norders       = (int)($results['neworders'] ?? 0);
$porders       = (int)($results['packedorders'] ?? 0);
$dtorders      = (int)($results['dispatchedorders'] ?? 0);
$intorders     = (int)($results['intransitorders'] ?? 0);
$otforders     = (int)($results['outfdorders'] ?? 0);
$deliveredorders = (int)($results['deliveredorders'] ?? 0);
$cancelledorders = (int)($results['cancelledorders'] ?? 0);
?>

<style>
    /* VELORIA Admin Sidebar - Modern & Elegant */
    .veloria-sidebar {
        background: #2A2826;
        color: #E3DCD5;
        min-height: 100vh;
        border-radius: 0;
        font-family: 'Inter', sans-serif;
    }
    .veloria-sidebar .nav {
        flex-direction: column;
        padding: 1rem 0;
    }
    .veloria-sidebar .nav-link {
        color: #E3DCD5;
        font-size: 0.9rem;
        padding: 0.6rem 1.2rem;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .veloria-sidebar .nav-link:hover {
        background: #3F3A36;
        color: #C47A5E;
        border-left-color: #C47A5E;
    }
    .veloria-sidebar .nav-link i {
        width: 1.6rem;
        text-align: center;
        color: #C47A5E;
    }
    .veloria-sidebar .sb-sidenav-menu-heading {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        color: #B8ADA5;
        padding: 0.8rem 1.2rem 0.3rem;
    }
    .veloria-sidebar .collapse .nav-link {
        padding-left: 2.5rem;
        font-size: 0.85rem;
    }
    .veloria-sidebar .sb-sidenav-collapse-arrow {
        margin-left: auto;
        transition: transform 0.2s;
    }
    .veloria-sidebar .nav-link.collapsed .sb-sidenav-collapse-arrow {
        transform: rotate(0deg);
    }
    .veloria-sidebar .nav-link:not(.collapsed) .sb-sidenav-collapse-arrow {
        transform: rotate(180deg);
    }
    .veloria-sidebar .badge-count {
        background: #C47A5E;
        color: #2A2826;
        border-radius: 20px;
        padding: 0.1rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    .veloria-sidebar .sb-sidenav-footer {
        border-top: 1px solid #3F3A36;
        padding: 1rem 1.2rem;
        font-size: 0.75rem;
        color: #B8ADA5;
        margin-top: auto;
    }
    .veloria-sidebar .sb-sidenav-footer .small {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="veloria-sidebar d-flex flex-column h-100">
    <div class="nav flex-column">
        <!-- Core -->
        <div class="sb-sidenav-menu-heading">Core</div>
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <!-- Agent Management -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseAgent" role="button" aria-expanded="false" aria-controls="collapseAgent">
            <i class="fas fa-user-tie"></i> Agent Management
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseAgent">
            <div class="nav flex-column">
                <a class="nav-link" href="add-agent.php">Add Agent</a>
                <a class="nav-link" href="manage-agents.php">Manage Agents</a>
                <a class="nav-link" href="agent-chats.php">Agent Chats</a>
            </div>
        </div>

        <!-- Blog Management -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseBlog" role="button" aria-expanded="false" aria-controls="collapseBlog">
            <i class="fas fa-blog"></i> Blog Management
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseBlog">
            <div class="nav flex-column">
                <a class="nav-link" href="add-blog.php">Add New Post</a>
                <a class="nav-link" href="manage-blog.php">All Posts</a>
                <a class="nav-link" href="manage-comments.php">Manage Comments</a>
            </div>
        </div>

        <!-- Delivery Management -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseDelivery" role="button" aria-expanded="false" aria-controls="collapseDelivery">
            <i class="fas fa-truck"></i> Delivery Management
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseDelivery">
            <div class="nav flex-column">
                <a class="nav-link" href="add-delivery.php">Add Delivery</a>
                <a class="nav-link" href="assign-delivery.php">Assign Delivery</a>
                <a class="nav-link" href="manage-delivery.php">Manage Deliveries</a>
                <a class="nav-link" href="delivery-chats.php">Delivery Chats</a>
            </div>
        </div>

        <!-- Product Management Section -->
        <div class="sb-sidenav-menu-heading">Product Management</div>

        <!-- Categories -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseCategories" role="button" aria-expanded="false" aria-controls="collapseCategories">
            <i class="fas fa-columns"></i> Categories
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseCategories">
            <div class="nav flex-column">
                <a class="nav-link" href="add-category.php">Add</a>
                <a class="nav-link" href="manage-categories.php">Manage</a>
            </div>
        </div>

        <!-- Sub-Categories -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseSubcat" role="button" aria-expanded="false" aria-controls="collapseSubcat">
            <i class="fas fa-columns"></i> Sub-Categories
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseSubcat">
            <div class="nav flex-column">
                <a class="nav-link" href="add-subcategories.php">Add</a>
                <a class="nav-link" href="manage-subcategories.php">Manage</a>
            </div>
        </div>

        <!-- Products -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseProducts" role="button" aria-expanded="false" aria-controls="collapseProducts">
            <i class="fas fa-box"></i> Products
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseProducts">
            <div class="nav flex-column">
                <a class="nav-link" href="add-product.php">Add</a>
                <a class="nav-link" href="manage-products.php">Manage</a>
            </div>
        </div>

        <!-- Order Management Section -->
        <div class="sb-sidenav-menu-heading">Order Management</div>

        <!-- Orders (collapsible with dynamic counts) -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseOrders" role="button" aria-expanded="false" aria-controls="collapseOrders">
            <i class="fas fa-shopping-cart"></i> Orders
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseOrders">
            <div class="nav flex-column">
                <a class="nav-link" href="all-orders.php">All Orders <span class="badge-count"><?php echo $torders; ?></span></a>
                <a class="nav-link" href="new-order.php">New Orders <span class="badge-count"><?php echo $norders; ?></span></a>
                <a class="nav-link" href="packed-orders.php">Packed Orders <span class="badge-count"><?php echo $porders; ?></span></a>
                <a class="nav-link" href="dispatched-orders.php">Dispatched Orders <span class="badge-count"><?php echo $dtorders; ?></span></a>
                <a class="nav-link" href="intransit-orders.php">In Transit Orders <span class="badge-count"><?php echo $intorders; ?></span></a>
                <a class="nav-link" href="outfordelivery-orders.php">Out for Delivery <span class="badge-count"><?php echo $otforders; ?></span></a>
                <a class="nav-link" href="delivered-orders.php">Delivered Orders <span class="badge-count"><?php echo $deliveredorders; ?></span></a>
                <a class="nav-link" href="cancelled-orders.php">Cancelled Orders <span class="badge-count"><?php echo $cancelledorders; ?></span></a>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="sb-sidenav-menu-heading">Reports</div>

        <!-- Reports -->
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseReports" role="button" aria-expanded="false" aria-controls="collapseReports">
            <i class="fas fa-chart-line"></i> Reports
            <i class="fas fa-chevron-down sb-sidenav-collapse-arrow"></i>
        </a>
        <div class="collapse" id="collapseReports">
            <div class="nav flex-column">
                <a class="nav-link" href="bwdates-ordersreport.php">B/w Dates Orders Report</a>
                <a class="nav-link" href="sales-report.php">Sales Report</a>
            </div>
        </div>

        <!-- Registered Users -->
        <a class="nav-link" href="registered-users.php">
            <i class="fas fa-users"></i> Registered Users
        </a>
    </div> <!-- /.nav flex-column -->

    <!-- Sidebar Footer (Logged in user) -->
    <div class="sb-sidenav-footer mt-auto">
        <div class="small">Logged in as:</div>
        <?php echo htmlspecialchars($_SESSION['alogin'] ?? 'Admin'); ?>
    </div>
</div> <!-- /.veloria-sidebar -->

<!-- Ensure Bootstrap 5 JS is loaded (already in header) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(function(el) {
            new bootstrap.Collapse(el, { toggle: false });
        });
    });
</script>