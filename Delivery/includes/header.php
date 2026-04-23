<!-- Delivery Header - VELORIA Style (with sidebar toggle and search) -->
<style>
    .veloria-delivery-header {
        background: #2A2826;
        border-bottom: 2px solid #C47A5E;
        padding: 0.5rem 1rem;
        font-family: 'Inter', sans-serif;
    }
    .veloria-delivery-header .navbar-brand {
        font-family: 'Instrument Serif', serif;
        font-size: 1.5rem;
        font-weight: 500;
        color: #F0E7E0 !important;
        letter-spacing: -0.5px;
    }
    .veloria-delivery-header .navbar-brand:after {
        content: '';
        display: block;
        width: 35px;
        height: 2px;
        background: #C47A5E;
        margin-top: 4px;
    }
    .veloria-delivery-header .btn-link {
        color: #E3DCD5;
        text-decoration: none;
        font-size: 1.2rem;
        padding: 0.25rem 0.5rem;
    }
    .veloria-delivery-header .btn-link:hover {
        color: #C47A5E;
    }
    .veloria-delivery-header .input-group .form-control {
        background: #3F3A36;
        border: none;
        border-radius: 40px 0 0 40px;
        color: #E3DCD5;
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }
    .veloria-delivery-header .input-group .form-control::placeholder {
        color: #B8ADA5;
    }
    .veloria-delivery-header .input-group .form-control:focus {
        box-shadow: none;
        background: #4A4440;
    }
    .veloria-delivery-header .btn-primary {
        background: #C47A5E;
        border: none;
        border-radius: 0 40px 40px 0;
        color: #2A2826;
        font-weight: 600;
        padding: 0.5rem 1rem;
    }
    .veloria-delivery-header .btn-primary:hover {
        background: #DBA87A;
    }
    .veloria-delivery-header .dropdown-toggle {
        color: #E3DCD5 !important;
    }
    .veloria-delivery-header .dropdown-toggle:hover {
        color: #C47A5E !important;
    }
    .veloria-delivery-header .dropdown-menu {
        background: #2A2826;
        border: 1px solid #C47A5E;
        border-radius: 12px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.15);
        padding: 0.5rem 0;
    }
    .veloria-delivery-header .dropdown-item {
        color: #E3DCD5 !important;
        font-size: 0.85rem;
        padding: 0.5rem 1.5rem;
    }
    .veloria-delivery-header .dropdown-item:hover {
        background: #3F3A36;
        color: #C47A5E !important;
    }
    .veloria-delivery-header .dropdown-divider {
        border-top-color: #3F3A36;
    }
    @media (max-width: 768px) {
        .veloria-delivery-header .navbar-brand {
            font-size: 1.2rem;
        }
        .veloria-delivery-header .input-group {
            width: 100%;
            margin: 0.5rem 0;
        }
    }
</style>

<nav class="veloria-delivery-header navbar navbar-expand navbar-dark">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-2" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Brand -->
        <a class="navbar-brand ps-2" href="dashboard.php">Triple A<br><span style="font-size:0.8rem;">Delivery Panel</span></a>
        <!-- Search Form (visible on medium screens and up) -->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="post" action="search-orders.php">
            <div class="input-group">
                <input class="form-control" type="text" name="searchinputdata" placeholder="Enter Name or Order No." aria-label="Search" required>
                <button class="btn btn-primary" type="submit" name="search"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- User Dropdown -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="delivery-profile.php"><i class="fas fa-id-card"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="change-password.php"><i class="fas fa-key"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Font Awesome 6 (ensure it's loaded) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">