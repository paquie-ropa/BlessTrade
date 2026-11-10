<?php
// BlessTrade Township Marketplace Header Inclusion File
// Connects session parameters and provides cohesive responsive navigation header

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser_name = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : null;
$currentUser_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlessTrade - SA Township Marketplace</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Lucide Icons via CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom Style Sheet -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body style="background-color:#f0fdf4;" class="d-flex flex-column min-h-screen">

<!-- Top Announcement Bar -->
<div class="bg-emerald-900 text-white text-center py-1.5 px-3 font-semibold text-xs font-sans tracking-wide">
    🇿🇦 Empowering Local Township Micro-Traders in Soweto, Alex & Tembisa with Safe Handshakes!
</div>

<!-- Main Top Navigation Hub -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
    <div class="container-fluid max-width-7xl px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <div class="bg-emerald-600 p-1.5 rounded-3" style="color:white; line-height:1;">
                <i data-lucide="shopping-bag" style="width:20px; height:20px;"></i>
            </div>
            <span>Bless<span class="brand-accent">Trade</span></span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#blesstradeNav" aria-controls="blesstradeNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="blesstradeNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'products.php' ? 'active' : ''; ?>" href="products.php">Browse Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php if ($currentUser_name): ?>
                    <!-- Authorized Nav buttons -->
                    <?php if ($currentUser_role === 'seller'): ?>
                        <a href="sell.php" class="btn btn-outline-success btn-sm border-2 fw-bold px-3 py-2 rounded-3 me-2" style="font-size:0.8rem;">
                            <i data-lucide="plus-circle" class="me-1" style="width:16px; height:16px; vertical-align:middle;"></i> Sell Item
                        </a>
                        <a href="dashboard.php" class="btn btn-dark btn-sm fw-bold px-3 py-2 rounded-3 me-2" style="font-size:0.8rem;">
                            <i data-lucide="layout-dashboard" class="me-1" style="width:16px; height:16px; vertical-align:middle;"></i> My Dashboard
                        </a>
                    <?php elseif ($currentUser_role === 'admin'): ?>
                        <a href="admin/index.php" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-3 me-2" style="font-size:0.8rem;">
                            <i data-lucide="shield-alert" class="me-1" style="width:16px; height:16px; vertical-align:middle;"></i> Admin Control
                        </a>
                    <?php else: ?>
                        <!-- Buyer view details link to simulated buyers dashboard -->
                        <a href="dashboard.php" class="btn btn-dark btn-sm fw-bold px-3 py-2 rounded-3 me-2" style="font-size:0.8rem;">
                            <i data-lucide="shopping-cart" class="me-1" style="width:16px; height:16px; vertical-align:middle;"></i> My Orders
                        </a>
                    <?php endif; ?>

                    <span class="text-xs fw-bold text-slate-800 me-2 border-end pe-2 d-none d-md-inline" style="font-size:0.8rem;">
                        👤 Hey, <?php echo htmlspecialchars(explode(' ', $currentUser_name)[0]); ?> 
                        <span class="badge bg-emerald-100 text-emerald-800 ms-1" style="font-size:0.6rem;"><?php echo strtoupper($currentUser_role); ?></span>
                    </span>
                    
                    <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold px-3 py-2 rounded-3" style="font-size:0.8rem; border-radius:10px;">
                        Logout
                    </a>

                <?php else: ?>
                    <!-- Guest Nav links -->
                    <a href="login.php" class="nav-link text-slate-700 fw-bold me-2" style="font-size:0.85rem;">Sign In</a>
                    <a href="register.php" class="btn auth-btn text-xs fw-bold px-3" style="font-size:0.8rem;">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Container segment starting -->
<div class="flex-grow-1">
