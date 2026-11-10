<?php
// BlessTrade Township Marketplace admin/index.php
// Administration supervisor dashboard panel

require_once '../includes/config.php';

// Safe check: enforce administrator role session access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div style='padding:50px; text-align:center; font-family:sans-serif;'>
            <h3>🔒 Access Denied! Administrator Authorization Required.</h3>
            <p>Please log in using an Admin Supervisor account (admin@blesstrade.co.za).</p>
            <a href='../login.php' style='display:inline-block; padding:8px 16px; background:#dc2626; color:#fff; text-decoration:none; border-radius:6px;'>Login Panel</a>
          </div>";
    exit;
}

$user_name = $_SESSION['user_fullname'];

// Fetch Total Users count
$user_count_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$user_count = mysqli_fetch_assoc($user_count_res)['total'];

// Fetch Total Products listing count
$prod_count_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$prod_count = mysqli_fetch_assoc($prod_count_res)['total'];

// Fetch Total Orders transaction count
$ord_count_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$ord_count = mysqli_fetch_assoc($ord_count_res)['total'];

// Fetch recent users list
$users_list = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlessTrade - Admin Console Panel</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Lucide Icons via CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom Style Sheet loads from root style.css -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body style="background-color:#ecfdf5;">

<!-- Admin Nav Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3 px-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 text-white" href="../index.php">
            <div class="bg-danger p-1.5 rounded-3" style="color:white; line-height:1;">
                <i data-lucide="shield-alert" style="width:20px; height:20px;"></i>
            </div>
            <span>BlessTrade <span class="badge bg-danger text-xs ms-1" style="font-size:0.6rem; vertical-align:middle;">ADMIN</span></span>
        </a>
        
        <div class="d-flex align-items-center gap-2.5">
            <span class="text-white text-xs fw-semibold me-2 d-none d-md-inline" style="font-size:0.8rem;">
                👑 Supervisor: <?php echo htmlspecialchars($user_name); ?>
            </span>
            <a href="../index.php" class="btn btn-outline-light btn-sm fw-bold px-3 py-1.5 rounded-2" style="font-size:0.75rem;">
                Back To Main
            </a>
            <a href="../logout.php" class="btn btn-danger btn-sm fw-bold px-3 py-1.5 rounded-2" style="font-size:0.75rem;">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid max-width-7xl px-4 py-5 text-start">
    
    <!-- Header Title -->
    <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-baseline flex-wrap">
        <div>
            <span class="text-xs text-danger uppercase fw-bold font-mono tracking-wider">Supervision Console</span>
            <h1 class="h2 fw-extrabold text-slate-900 font-display mb-0">Platform Administration Overview</h1>
        </div>
        <div class="font-mono text-xs text-muted">
            🗓️ SAST Date: <?php echo date('Y-m-d H:i'); ?>
        </div>
    </div>

    <!-- Stats row indicators -->
    <div class="row g-4 mb-5">
        <!-- Users count stat -->
        <div class="col-md-4">
            <div class="card border-0 shadow-xs rounded-4 p-4 d-flex flex-row align-items-center gap-3">
                <div class="bg-primary text-white p-3 rounded-3" style="--bs-bg-opacity: .1; color:#0d6efd !important;">
                    <i data-lucide="users" style="width:28px; height:28px;"></i>
                </div>
                <div>
                    <span class="text-xs text-muted uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.04em;">System Users</span>
                    <h3 class="fw-extrabold font-display mb-0 text-slate-900"><?php echo $user_count; ?> registered</h3>
                </div>
            </div>
        </div>

        <!-- Products count stat -->
        <div class="col-md-4">
            <div class="card border-0 shadow-xs rounded-4 p-4 d-flex flex-row align-items-center gap-3">
                <div class="bg-success text-white p-3 rounded-3" style="--bs-bg-opacity: .1; color:#198754 !important;">
                    <i data-lucide="layers" style="width:28px; height:28px;"></i>
                </div>
                <div>
                    <span class="text-xs text-muted uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.04em;">Listed Products</span>
                    <h3 class="fw-extrabold font-display mb-0 text-slate-900"><?php echo $prod_count; ?> items listed</h3>
                </div>
            </div>
        </div>

        <!-- Orders count stat -->
        <div class="col-md-4">
            <div class="card border-0 shadow-xs rounded-4 p-4 d-flex flex-row align-items-center gap-3">
                <div class="bg-warning text-white p-3 rounded-3" style="--bs-bg-opacity: .1; color:#ffc107 !important;">
                    <i data-lucide="check-square" style="width:28px; height:28px;"></i>
                </div>
                <div>
                    <span class="text-xs text-muted uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.04em;">Transactions / Reservations</span>
                    <h3 class="fw-extrabold font-display mb-0 text-slate-900"><?php echo $ord_count; ?> orders placed</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Selection Quick Action Menu Nodes -->
    <div class="row g-4">
        <!-- Sidebar Navigation Menu -->
        <div class="col-lg-3">
            <div class="card border-0 rounded-4 shadow-xs p-3">
                <h6 class="text-xs uppercase fw-bold tracking-widest text-muted p-2 border-bottom">Admin Master Navigation</h6>
                <div class="list-group list-group-flush" style="font-size: 0.85rem;">
                    <a href="index.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-bold text-danger">
                        📊 Dashboard Statistics
                    </a>
                    <a href="users.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-semibold text-slate-700">
                        👥 Manage Users Registrations
                    </a>
                    <a href="products.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-semibold text-slate-700">
                        📦 Manage Product Listings
                    </a>
                </div>
            </div>
        </div>

        <!-- Feed List Display -->
        <div class="col-lg-9">
            <div class="card border-0 rounded-4 shadow-xs p-4">
                <h5 class="fw-bold text-slate-900 border-bottom pb-2.5 mb-3.5 font-display d-flex align-items-center gap-1.5">
                    <i data-lucide="list-filter" class="text-danger"></i> Recent Registrations (Audit Feed)
                </h5>

                <div class="table-responsive text-sm">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-xs text-muted uppercase font-display tracking-wider border-bottom">
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Role Type</th>
                                <th>Primary Location</th>
                                <th class="text-end">Registered On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($users_list && mysqli_num_rows($users_list) > 0): ?>
                                <?php while ($u = mysqli_fetch_assoc($users_list)): ?>
                                    <tr>
                                        <td class="font-mono fw-bold">#bt_<?php echo $u['id']; ?></td>
                                        <td><b><?php echo htmlspecialchars($u['fullname']); ?></b></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td>
                                            <?php if ($u['role'] === 'admin'): ?>
                                                <span class="badge bg-danger text-white uppercase" style="font-size:0.6rem;">ADMIN</span>
                                            <?php elseif ($u['role'] === 'seller'): ?>
                                                <span class="badge bg-dark text-white uppercase" style="font-size:0.6rem;">SELLER</span>
                                            <?php else: ?>
                                                <span class="badge bg-emerald-100 text-emerald-800 uppercase" style="font-size:0.6rem;">BUYER</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($u['location']); ?></td>
                                        <td class="font-mono text-muted text-end" style="font-size: 0.75rem;"><?php echo $u['created_at']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No users currently registered on database.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pt-3 border-top text-end">
                    <a href="users.php" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-2" style="font-size: 0.75rem;">Manage & Audit Entire Directory &rarr;</a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Initialize Lucide Icons -->
<script>
    lucide.createIcons();
</script>
</body>
</html>
