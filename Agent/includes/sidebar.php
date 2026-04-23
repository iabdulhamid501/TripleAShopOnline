<?php
// Agent Sidebar - VELORIA Style
// Place this file in Agent/includes/sidebar.php
// No order counts needed – agents see only their assigned chats/orders.
// The session variable 'agent_username' is set during login.
?>
<style>
    /* VELORIA Agent Sidebar - Modern & Elegant */
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
        <div class="sb-sidenav-menu-heading">Agent Menu</div>
        
        <!-- Dashboard -->
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        
        <a class="nav-link" href="chat-with-admin.php">
            <i class="fas fa-envelope"></i> Chat with Admin
        </a>

        <!-- Active Chats (direct link – no collapse) -->
        <a class="nav-link" href="active-chats.php">
            <i class="fas fa-comments"></i> Active Chats
        </a>
        
        <!-- Assigned Orders (could be a page that lists orders assigned to this agent) -->
        <a class="nav-link" href="assigned-orders.php">
            <i class="fas fa-boxes"></i> Assigned Orders
        </a>

        <a class="nav-link" href="resolved-chats.php">
            <i class="fas fa-check-circle"></i> Resolved Chats
        </a>
        
        <!-- Chat History (all closed chats) -->
        <a class="nav-link" href="chat-history.php">
            <i class="fas fa-history"></i> Chat History
        </a>
        
        <!-- Profile & Settings -->
        <div class="sb-sidenav-menu-heading">Account</div>
        <a class="nav-link" href="agent-profile.php">
            <i class="fas fa-id-card"></i> Profile
        </a>
        <a class="nav-link" href="change-password.php">
            <i class="fas fa-key"></i> Change Password
        </a>
        <a class="nav-link" href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
    
    <!-- Sidebar Footer (logged in agent) -->
    <div class="sb-sidenav-footer mt-auto">
        <div class="small">Logged in as:</div>
        <?php echo htmlspecialchars($_SESSION['agent_username'] ?? 'Agent'); ?>
    </div>
</div>

<!-- Ensure Bootstrap 5 JS is loaded (already in header) -->
<script>
    // Optional: Bootstrap collapse auto‑initialises, but we keep for consistency
    document.addEventListener('DOMContentLoaded', function() {
        var collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(function(el) {
            new bootstrap.Collapse(el, { toggle: false });
        });
    });
</script>