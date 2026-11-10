<?php
// BlessTrade Township Marketplace dashboard.php
require_once 'includes/config.php';

// Safe check: enforce login session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_fullname'];
$user_role = $_SESSION['user_role'];

$success_msg = '';
$error_msg = '';

// Process seller listing deletion requests safely
if ($user_role === 'seller' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Safety check: ensure product actually belongs to currently active seller
    $check_query = "SELECT id FROM products WHERE id = $product_id AND seller_id = $user_id LIMIT 1";
    $check_res = mysqli_query($conn, $check_query);
    
    if ($check_res && mysqli_num_rows($check_res) === 1) {
        $delete_query = "DELETE FROM products WHERE id = $product_id";
        if (mysqli_query($conn, $delete_query)) {
            $success_msg = "Listing Item #bt_prod_$product_id was successfully removed.";
        } else {
            $error_msg = "SQL Database Server Error preventing deletion of this listing.";
        }
    } else {
        $error_msg = "Security Breach! You can only delete your own listed products.";
    }
}

// Populate role specific stats
$products_listed = [];
$orders_placed = [];

if ($user_role === 'seller') {
    // 1. Fetch current seller products
    $p_query = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.seller_id = $user_id ORDER BY p.id DESC";
    $p_res = mysqli_query($conn, $p_query);
    while ($row = mysqli_fetch_assoc($p_res)) {
        $products_listed[] = $row;
    }

    // 2. Fetch incoming orders from buyers
    $o_query = "SELECT o.*, p.title AS product_title, p.image AS product_image, u.fullname AS buyer_fullname 
                FROM orders o 
                LEFT JOIN products p ON o.product_id = p.id 
                LEFT JOIN users u ON o.buyer_id = u.id 
                WHERE o.seller_id = $user_id ORDER BY o.id DESC";
    $o_res = mysqli_query($conn, $o_query);
    while ($row = mysqli_fetch_assoc($o_res)) {
        $orders_placed[] = $row;
    }

} else {
    // Buyer role dashboard populate:
    // Fetch user buying orders / reservation logs
    $o_query = "SELECT o.*, p.title AS product_title, p.image AS product_image, u.fullname AS seller_fullname 
                FROM orders o 
                LEFT JOIN products p ON o.product_id = p.id 
                LEFT JOIN users u ON o.seller_id = u.id 
                WHERE o.buyer_id = $user_id ORDER BY o.id DESC";
    $o_res = mysqli_query($conn, $o_query);
    while ($row = mysqli_fetch_assoc($o_res)) {
         $orders_placed[] = $row;
    }
}

require_once 'includes/header.php';
?>

<div class="container my-5 text-start animate-in fade-in">
    
    <!-- Dashboard main header banner -->
    <div class="bg-white border rounded-4 p-4 p-md-5 shadow-xs mb-4 d-flex flex-col md:flex-row justify-content-between align-items-center gap-4">
        <div>
            <div class="d-flex align-items-center gap-2.5 mb-2">
                <span class="badge bg-emerald-100 text-emerald-800 uppercase fw-bold font-mono px-2 py-1" style="font-size:0.65rem; border-radius:4px;"><?php echo strtoupper($user_role); ?> DASHBOARD</span>
                <span class="text-xs text-muted font-mono">ID Key: #bt_user_<?php echo $user_id; ?></span>
            </div>
            <h2 class="fw-extrabold text-slate-950 font-display mb-1">Hey, <?php echo htmlspecialchars($user_name); ?>!</h2>
            <p class="text-xs text-slate-500 mb-0 font-sans">Registered Trading Area: <?php echo htmlspecialchars($_SESSION['user_location'] ?: 'Johannesburg, GP'); ?></p>
        </div>

        <div>
            <?php if ($user_role === 'seller'): ?>
                <a href="sell.php" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 text-white fw-bold px-4 py-2.5 rounded-3 border-0 shadow">
                    📢 Post Brand New Item
                </a>
            <?php else: ?>
                <!-- Non-Seller upgrade reminder -->
                <div class="text-end bg-emerald-50 border border-emerald-200/50 p-3 rounded-3" style="font-size:0.8rem; max-width:280px;">
                    <span class="fw-bold text-emerald-900 d-block mb-1">Want to earn cash?</span>
                    Register or upgrade your profile role to **seller** from administrators to list merchandise!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success py-2 px-3 rounded-3 mb-4" style="font-size: 11px; font-weight:700;">
            🎉 <?php echo htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger py-2 px-3 rounded-3 mb-4" style="font-size: 11px; font-weight:700;">
            ⚠️ <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <!-- Multi-role Conditional Layout UI -->
    <?php if ($user_role === 'seller'): ?>
        
        <div class="row g-4">
            
            <!-- Col 1: Seller's active listed items catalogue -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-xs p-4 h-100 bg-white">
                    <h5 class="fw-extrabold text-slate-950 border-b pb-2.5 mb-3.5 font-display d-flex align-items-center gap-1.5">
                        <i data-lucide="package-open" class="text-emerald-600"></i> My Active Merchandise Catalog
                    </h5>

                    <div class="table-responsive" style="max-height: 480px; overflow-y:auto;">
                        <table class="table align-middle text-sm" style="font-size:0.83rem;">
                            <thead>
                                <tr class="text-xs text-muted uppercase">
                                    <th>Listing Detail</th>
                                    <th>Price/Condition</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($products_listed) > 0): ?>
                                    <?php foreach ($products_listed as $prod): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="item pic" class="img-fluid rounded border" style="width:42px; height:42px; object-fit:contain; bg-light">
                                                    <div>
                                                        <span class="fw-bold text-slate-900 d-block text-truncate" style="max-width:140px;"><?php echo htmlspecialchars($prod['title']); ?></span>
                                                        <span class="text-xs text-muted font-display"><?php echo htmlspecialchars($prod['category_name']); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-emerald-700 fw-bold font-display">R <?php echo number_format($prod['price'], 2); ?></span>
                                                <span class="badge bg-slate-100 text-slate-700 uppercase ms-1" style="font-size:0.55rem;"><?php echo $prod['item_condition']; ?></span>
                                            </td>
                                            <td>
                                                <?php if ($prod['status'] === 'sold'): ?>
                                                    <span class="badge bg-danger text-white col-12 py-1 uppercase" style="font-size:0.58rem;">SOLD</span>
                                                <?php else: ?>
                                                    <a href="dashboard.php?action=delete&id=<?php echo $prod['id']; ?>" class="btn btn-outline-danger btn-xs py-1 px-2 rounded font-mono text-xs fw-bold" onclick="return confirm('Purge this listing product item archive?');" style="font-size:0.7rem;">
                                                        Purge
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">You have not posted any trading products yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Col 2: Incoming Orders/Purchase Transactions notifications -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-xs p-4 h-100 bg-white">
                    <h5 class="fw-extrabold text-slate-950 border-b pb-2.5 mb-3.5 font-display d-flex align-items-center gap-1.5">
                        <i data-lucide="check-square" class="text-emerald-600"></i> Incoming Township Orders
                    </h5>

                    <div class="space-y-4" style="max-height: 480px; overflow-y:auto;">
                        <?php if (count($orders_placed) > 0): ?>
                            <?php foreach ($orders_placed as $ord): ?>
                                <div class="p-3 bg-slate-50 border rounded-3 text-start">
                                    <div class="d-flex justify-content-between mb-2 pb-1.5 border-bottom flex-wrap gap-1">
                                        <span class="text-xs text-muted font-mono">Order: #bt_ord_<?php echo $ord['id']; ?></span>
                                        <span class="badge bg-warning text-dark uppercase fw-extrabold" style="font-size:0.6rem;"><?php echo strtoupper($ord['status']); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2.5 mb-3">
                                        <img src="<?php echo htmlspecialchars($ord['product_image']); ?>" alt="item pic" class="img-fluid rounded border" style="width:40px; height:40px; object-fit:contain;">
                                        <div>
                                            <h6 class="fw-bold text-slate-900 mb-0.5 text-xs text-truncate" style="max-width:200px;"><?php echo htmlspecialchars($ord['product_title']); ?></h6>
                                            <span class="text-xs fw-extrabold text-emerald-650">Settlement Amount: R <?php echo number_format($ord['amount'], 2); ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Buyer delivery metrics -->
                                    <div class="p-2 border bg-white rounded-2 mb-2 font-mono" style="font-size:0.75rem;">
                                        <span class="fw-bold text-slate-800 d-block mb-1">🙆 Buyer: <?php echo htmlspecialchars($ord['buyer_fullname']); ?></span>
                                        <span class="text-slate-500 d-block mb-0.5">📞 Cellular Contact: <a href="tel:<?php echo htmlspecialchars($ord['buyer_phone']); ?>" class="text-emerald-700 fw-bold"><?php echo htmlspecialchars($ord['buyer_phone']); ?></a></span>
                                        <span class="text-slate-500 d-block">📍 Township Meetup: <span class="fw-bold text-slate-700"><?php echo htmlspecialchars($ord['buyer_location']); ?></span></span>
                                    </div>
                                    <span class="text-[9.5px] text-muted font-mono block">Log registered SAS Date: <?php echo $ord['created_at']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center">
                                <i data-lucide="info" class="mx-auto mb-2 text-muted" style="width:24px; height:24px;"></i>
                                No incoming buyer orders placed yet. Once buyers order items, notifications appear here instantly.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    <?php else: ?>
        
        <!-- Buyer Role Dashboard: Display reservation list -->
        <div class="card border-0 rounded-4 shadow-xs p-4 p-md-5 bg-white">
            <h5 class="fw-extrabold text-slate-950 border-b pb-2.5 mb-4 font-display d-flex align-items-center gap-1.5">
                <i data-lucide="shopping-cart" class="text-emerald-650"></i> My Placed Orders & Secure Reservations
            </h5>

            <div class="row g-4">
                <?php if (count($orders_placed) > 0): ?>
                    <?php foreach ($orders_placed as $ord): ?>
                        <div class="col-md-6 text-start">
                            <div class="p-4 bg-slate-50 border rounded-4">
                                <div class="d-flex justify-content-between mb-3.5 pb-2 border-bottom flex-wrap gap-1">
                                    <span class="text-xs text-muted font-mono fw-bold">Reservation Ticket: #bt_reserve_<?php echo $ord['id']; ?></span>
                                    <span class="badge bg-amber-100 text-amber-800 uppercase" style="font-size:0.6rem; font-weight:800;"><?php echo strtoupper($ord['status']); ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <img src="<?php echo htmlspecialchars($ord['product_image']); ?>" alt="item summary preview" class="img-fluid rounded border bg-white" style="width:60px; height:60px; object-fit:contain;">
                                    <div>
                                        <h6 class="fw-bold text-slate-900 mb-1 text-sm text-truncate" style="max-width:220px;"><?php echo htmlspecialchars($ord['product_title']); ?></h6>
                                        <span class="text-sm fw-extrabold text-emerald-650 font-display">Amount Due Reserve: R <?php echo number_format($ord['amount'], 2); ?></span>
                                    </div>
                                </div>

                                <!-- Logistics instructions -->
                                <div class="bg-white border rounded-3 p-3 font-sans" style="font-size:0.78rem; line-height:1.6;">
                                    <span class="fw-bold text-slate-800 d-block mb-1">🚚 Delivery & Meetup Parameters</span>
                                    <li class="list-unstyled mb-1">
                                        👩‍💼 <span class="fw-bold">Contact Person:</span> Nomsa Khumalo & team (+27 81 234 5678)
                                    </li>
                                    <li class="list-unstyled mb-1">
                                        💡 <span class="fw-bold">Trade Rule:</span> Simulated PayFast confirmed! Inspect device carefully face-to-face before finalizing handshakes.
                                    </li>
                                    <li class="list-unstyled">
                                        📍 <span class="fw-bold">Handoff Coordinate Area:</span> <?php echo htmlspecialchars($ord['buyer_location']); ?>
                                    </li>
                                </div>
                                <span class="text-[9.5px] text-muted font-mono block mt-3 text-start">Log Date: <?php echo $ord['created_at']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <i data-lucide="shopping-bag" class="mx-auto mb-2 text-muted" style="width:30px; height:30px;"></i>
                        <p class="mb-2">There are no orders logged in your directory.</p>
                        <a href="products.php" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 text-white btn-sm fw-bold px-3 py-2 rounded-3 mt-1 font-display" style="font-size:0.8rem;">Browse Products Catalog</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
