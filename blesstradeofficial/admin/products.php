<?php
// BlessTrade Township Marketplace admin/products.php
// Administration product catalogue supervision audit panel

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

// Delete item audit action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    
    $delete_query = "DELETE FROM products WHERE id = $item_id";
    if (mysqli_query($conn, $delete_query)) {
        $success_msg = "Listing Item #bt_prod_$item_id was successfully audited and deleted from platform catalogues.";
    } else {
        $error_msg = "Error! Could not delete product from database.";
    }
}

// Fetch all registered products
$query = "SELECT p.*, c.name AS category_name, u.fullname AS seller_fullname 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          LEFT JOIN users u ON p.seller_id = u.id 
          ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlessTrade - Admin Manage Products</title>
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
        <span class="text-xs text-danger uppercase fw-bold font-mono tracking-wider">Catalogue Auditing Controls</span>
        <h1 class="h2 fw-extrabold text-slate-900 font-display mb-0">System Catalogue Supervision & Audit</h1>
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
                    <a href="users.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-semibold text-slate-700">
                        👥 Manage Users Registrations
                    </a>
                    <a href="products.php" class="list-group-item list-group-item-action border-0 py-2.5 fw-bold text-danger">
                        📦 Manage Product Listings
                    </a>
                </div>
            </div>
            
            <div class="card border-0 rounded-4 bg-danger text-white p-4">
                <i data-lucide="package" class="text-white mb-2" style="width:30px; height:30px;"></i>
                <h6 class="fw-bold font-display">Directory Supervision</h6>
                <p class="mb-0 text-white-50 leading-relaxed font-sans" style="font-size:0.75rem;">
                    Admin supervisors monitor listings regularly to verify safety guidelines. Listings breaching South African safety regulations or lacking accurate pricing are audits-purged.
                </p>
            </div>
        </div>

        <!-- Master Catalogue table Col -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-xs rounded-4 p-4">
                <h5 class="fw-bold text-slate-900 border-bottom pb-2.5 mb-3.5 font-display d-flex align-items-center gap-1.5">
                    <i data-lucide="package" class="text-danger"></i> Product Listings Supervision Directory
                </h5>

                <div class="table-responsive text-sm">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-xs text-muted font-display uppercase tracking-wider">
                                <th>Listing ID</th>
                                <th>Item Detail</th>
                                <th>Assigned Group</th>
                                <th>Asking Price</th>
                                <th>Merchant Owner</th>
                                <th>Status Option</th>
                                <th class="text-end">Command Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while ($prod = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="font-mono fw-bold">#bt_<?php echo $prod['id']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="Details image" class="img-fluid rounded border shadow-3xs bg-light" style="width:40px; height:40px; object-fit:contain;">
                                                <div>
                                                    <span class="fw-bold text-slate-800 d-block text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($prod['title']); ?></span>
                                                    <span class="badge <?php echo $prod['item_condition'] === 'new' ? 'bg-success text-white' : 'bg-warning text-dark'; ?>" style="font-size:0.55rem;"><?php echo strtoupper($prod['item_condition']); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($prod['category_name']); ?></td>
                                        <td class="fw-bold text-emerald-600 font-display">R <?php echo number_format($prod['price'], 2); ?></td>
                                        <td><b><?php echo htmlspecialchars($prod['seller_fullname'] ?: 'Independent Trader'); ?></b></td>
                                        <td>
                                            <?php if ($prod['status'] === 'sold'): ?>
                                                <span class="badge bg-danger text-white uppercase" style="font-size:0.55rem; font-weight:800;">SOLD OUT</span>
                                            <?php else: ?>
                                                <span class="badge bg-emerald-100 text-emerald-800 uppercase" style="font-size:0.55rem; font-weight:800;">AVAILABLE</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="products.php?action=delete&id=<?php echo $prod['id']; ?>" class="btn btn-outline-danger btn-xs py-1 px-2.5 rounded-2 font-display text-xs fw-bold" style="font-size:0.7rem;" onclick="return confirm('Audits-delete this item permanently?');">
                                                Remove Item
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No catalogue products listed.</td>
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
