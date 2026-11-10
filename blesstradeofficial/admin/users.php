<?php
// BlessTrade Township Marketplace admin/users.php
// Administration user management console

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

$success_msg = '';
$error_msg = '';

// Process delete action query parameter
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    
    // Prevent self-deletion
    if ($delete_id === intval($_SESSION['user_id'])) {
        $error_msg = 'Error! You cannot delete your own active supervisor account.';
    } elseif ($delete_id === 1) { // Protect root seed admin id
        $error_msg = 'Error! System protection guards preventing deletion of seed Main Administrator.';
    } else {
        $stmt_delete = "DELETE FROM users WHERE id = $delete_id";
        if (mysqli_query($conn, $stmt_delete)) {
            $success_msg = "Account ID #bt_$delete_id was successfully removed from databases.";
        } else {
            $error_msg = "SQL Server error preventing deletion of account record.";
        }
    }
}

// Fetch all registered users
$query = "SELECT * FROM users ORDER BY role ASC, id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlessTrade - Admin Manage Users</title>
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
        <a class="navbar-brand d-flex align-items-center gap-2 text-white" href="index.php">
            <div class="bg-danger p-1.5 rounded-3" style="color:white; line-height:1;">
                <i data-lucide="shield-alert" style="width:20px; height:20px;"></i>
            </div>
            <span>BlessTrade <span class="badge bg-danger text-xs ms-1" style="font-size:0.6rem; vertical-align:middle;">ADMIN</span></span>
        </a>
        
        <div class="d-flex align-items-center gap-2.5">
            <span class="text-white text-xs fw-semibold me-2 d-none d-md-inline" style="font-size:0.8rem;">
                👑 Supervisor: <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>
            </span>
            <a href="index.php" class="btn btn-outline-light btn-sm fw-bold px-3 py-1.5 rounded-2" style="font-size:0.75rem;">
                Dashboard Overview
            </a>
            <a href="../logout.php" class="btn btn-danger btn-sm fw-bold px-3 py-1.5 rounded-2" style="font-size:0.75rem;">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid max-width-7xl px-4 py-5 text-start font-sans">
    
    <!-- Title Section -->
    <div class="border-bottom pb-3 mb-4">
        <span class="text-xs text-danger uppercase fw-bold font-mono tracking-wider">Account Supervision Controls</span>
        <h1 class="h2 fw-extrabold text-slate-900 font-display mb-0">System Directories Auditing & Control</h1>
    </div>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success py-2.5 px-3 rounded-3 mb-4" style="font-size:12px; font-weight:700;">
            🎉 <?php echo htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger py-2.5 px-3 rounded-3 mb-4" style="font-size:11px; font-weight:700;">
            ⚠️ <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- Sidebar Navigation Menu -->
        <div class="col-lg-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 mb-4">
                <h6 class="text-xs uppercase fw-bold tracking-widest text-muted p-2 border-bottom">Admin Action Panels</h6>
                <div class="list-group list-group-flush" style="font-size: 0.85rem;">
                    <a href="index.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-semibold text-slate-700">
                        📊 Dashboard Statistics
                    </a>
                    <a href="users.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-bold text-danger">
                        👥 Manage Users Registrations
                    </a>
                    <a href="products.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-semibold text-slate-700">
                        📦 Manage Product Listings
                    </a>
                </div>
            </div>
            
            <div class="card border-0 rounded-4 bg-danger text-white p-4">
                <i data-lucide="shield-check" class="text-white mb-2" style="width:30px; height:30px;"></i>
                <h6 class="fw-bold font-display">Administrative Standard</h6>
                <p class="mb-0 text-white-50 leading-relaxed font-sans" style="font-size:0.75rem;">
                    Removing user records automatically cascades to erase associated product listings logs and reservation files to ensure relational DB safety. Ensure confirmation before purging keys.
                </p>
            </div>
        </div>

        <!-- Master Directory table Col -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-xs rounded-4 p-4">
                <h5 class="fw-bold text-slate-900 border-bottom pb-2.5 mb-3.5 font-display d-flex align-items-center gap-1.5">
                    <i data-lucide="users" class="text-danger"></i> Registered Users Index Directory
                </h5>

                <div class="table-responsive text-sm">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-xs text-muted font-display uppercase tracking-wider">
                                <th>ID Key</th>
                                <th>Trading Name</th>
                                <th>Secure Email Address</th>
                                <th>Cell Cellular</th>
                                <th>Role Tier</th>
                                <th>Primary Township</th>
                                <th class="text-end">Command Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="font-mono fw-bold">#bt_<?php echo $user['id']; ?></td>
                                        <td><b><?php echo htmlspecialchars($user['fullname']); ?></b></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td class="font-mono text-muted"><?php echo htmlspecialchars($user['phone'] ?: '+27 81 000 0000'); ?></td>
                                        <td>
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="badge bg-danger text-white uppercase text-center" style="font-size:0.55rem; font-weight:800;">ADMIN SU</span>
                                            <?php elseif ($user['role'] === 'seller'): ?>
                                                <span class="badge bg-dark text-white uppercase text-center" style="font-size:0.55rem; font-weight:800;">SELLER MERCH</span>
                                            <?php else: ?>
                                                <span class="badge bg-emerald-100 text-emerald-800 uppercase text-center" style="font-size:0.55rem; font-weight:800;">BUYER TRAD</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['location']); ?></td>
                                        <td class="text-end">
                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-xs py-1 px-2.5 rounded-2 font-display text-xs fw-bold" style="font-size: 0.7rem;" onclick="return confirm('Permanently purge this user account? This cascadingly clears all listings.');">
                                                    Delete User
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted text-xs font-mono" style="font-size: 0.72rem;">Protected</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No system user accounts registered.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
