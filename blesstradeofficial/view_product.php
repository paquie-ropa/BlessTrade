<?php
// BlessTrade Township Marketplace view_product.php
require_once 'includes/config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: products.php');
    exit;
}

// Fetch single product joining category and seller details
$query = "SELECT p.*, c.name AS category_name, u.fullname AS seller_fullname, u.location AS seller_location, u.phone AS seller_phone
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          LEFT JOIN users u ON p.seller_id = u.id 
          WHERE p.id = $product_id LIMIT 1";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: products.php');
    exit;
}

$product = mysqli_fetch_assoc($result);

require_once 'includes/header.php';
?>

<div class="container my-5 text-start animate-in fade-in font-sans">
    
    <!-- Navigation back breadcrumb -->
    <div class="mb-4">
        <a href="products.php" class="text-xs text-emerald-750 fw-bold hover:underline d-inline-flex align-items-center gap-1.5">
            <i data-lucide="arrow-left" style="width:14px; height:14px;"></i> Back to Products Catalogue
        </a>
    </div>

    <!-- Details Card layout -->
    <div class="bg-white border rounded-4 p-4 p-md-5 shadow-xs">
        <div class="row g-5">
            
            <!-- Col 1: Left details picture visual wrapper -->
            <div class="col-md-6">
                <div class="bg-slate-50 border p-3 rounded-4 d-flex align-items-center justify-content-center" style="height: 380px;">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="product visual display" class="img-fluid rounded-3" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                </div>
            </div>

            <!-- Col 2: Specifications, Merchant details, and actions -->
            <div class="col-md-6 text-start d-flex flex-column justify-content-between">
                <div>
                    <!-- Condition and category flags -->
                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="badge bg-emerald-100 text-emerald-800 uppercase px-2 py-1 fw-bold" style="font-size:0.6rem; letter-spacing:0.04em;">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </span>
                        <span class="badge <?php echo $product['item_condition'] === 'new' ? 'bg-success text-white' : 'bg-warning text-dark'; ?> uppercase px-2 py-1 fw-bold" style="font-size:0.6rem; letter-spacing:0.04em;">
                            Condition: <?php echo strtoupper($product['item_condition']); ?>
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="h3 fw-extrabold text-slate-900 font-display mb-1"><?php echo htmlspecialchars($product['title']); ?></h1>
                    
                    <!-- Asking Price info -->
                    <div class="py-2.5">
                        <span class="text-xs text-muted d-block uppercase fw-bold font-mono" style="font-size: 0.65rem; letter-spacing:0.04em;">Asking Price Range</span>
                        <span class="text-2xl fw-extrabold text-emerald-600 font-display">R <?php echo number_format($product['price'], 2); ?></span>
                    </div>

                    <hr class="my-3" style="border-top:1px solid #f1f5f9;">

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-slate-800 font-display mb-1.5" style="font-size:0.85rem;">Specification Details</h6>
                        <p class="text-slate-650 text-sm leading-relaxed mb-0">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </p>
                    </div>
                </div>

                <!-- Merchant contacts and checkout buttons -->
                <div>
                    <!-- Merchant Profile Panel -->
                    <div class="bg-emerald-50 border border-emerald-150 rounded-4 p-3.5 mb-4 text-start">
                        <h6 class="fw-bold text-emerald-950 font-display mb-2 d-flex align-items-center gap-1.5" style="font-size:0.8rem;">
                            <i data-lucide="user-check" style="width:16px; height:16px; color:#059669;"></i>
                            Verified Local Merchant Info
                        </h6>
                        <div class="font-sans text-xs text-emerald-900 space-y-1">
                            <li class="list-unstyled"><b>Name:</b> <?php echo htmlspecialchars($product['fullname'] ?: 'Independent Merchant Group'); ?></li>
                            <li class="list-unstyled"><b>Primary Coordinate Town:</b> <?php echo htmlspecialchars($product['seller_location']); ?></li>
                            <!-- Show seller phone contact if session logged in, else hide -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li class="list-unstyled"><b>Cellular WhatsApp line:</b> <a href="tel:<?php echo htmlspecialchars($product['seller_phone']); ?>" class="fw-bold text-emerald-700"><?php echo htmlspecialchars($product['seller_phone']); ?></a></li>
                            <?php else: ?>
                                <li class="list-unstyled"><b>Cellular Contact:</b> <span class="text-slate-400 font-mono italic">[Sign in to unveil active cell calls]</span></li>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Guarded Buy Now Option button -->
                    <?php if ($product['status'] === 'sold'): ?>
                        <button disabled class="btn btn-danger w-100 py-3 rounded-3 text-white fw-bold border-0 font-display text-sm uppercase tracking-wider">
                            Sold Out / Reserved
                        </button>
                    <?php else: ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php?id=<?php echo $product['id']; ?>" class="btn btn-emerald-650 bg-emerald-600 hover:bg-emerald-700 w-100 py-3.5 rounded-3 text-white text-center fw-bold border-0 font-sans text-sm uppercase tracking-wide">
                                🤝 Buy Now & Reserve Item
                            </a>
                        <?php else: ?>
                            <!-- Buyer redirect warning guard notice -->
                            <div class="space-y-2">
                                <a href="login.php" class="btn btn-dark w-100 py-3.5 rounded-3 text-white text-center fw-bold border-0 font-sans text-sm uppercase">
                                    🔑 Sign In to Buy Now
                                </a>
                                <span class="text-center text-muted d-block font-sans text-xs italic" style="font-size: 0.72rem;">*You must be registered and logged into a township account to reserve items.</span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
