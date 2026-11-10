<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// BlessTrade Township Marketplace index.php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch maximum 4 available items to showcase on front desk
$query = "SELECT p.*, c.name AS category_name, u.fullname AS seller_fullname, u.location AS seller_location
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          LEFT JOIN users u ON p.seller_id = u.id 
          WHERE p.status = 'available' 
          ORDER BY p.id DESC LIMIT 4";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5 text-start animate-in fade-in">
    
    <!-- 1. Hero Carousel Banner Slides -->
    <div class="hero-slider-container">
        <div id="blesstradeCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#blesstradeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#blesstradeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&w=1200&q=80');">
                    <div class="carousel-overlay">
                        <div class="carousel-caption-custom">
                            <span class="carousel-tag">Featured Marketplace</span>
                            <h2 class="display-5 fw-extrabold text-white font-display mb-3">Safe Township Peer-To-Peer Trading Circle</h2>
                            <p class="text-sm text-slate-200 mb-4 font-sans leading-relaxed">
                                List and discover secondhand home appliances, smartphone units, and fashion accessories completely free of merchant listing costs in Gauteng.
                            </p>
                            <a href="products.php" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 text-white fw-bold px-4 py-2.5 rounded-3 border-0 transition-all font-display text-xs uppercase tracking-wide">
                                Browse Catalogue
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80');">
                    <div class="carousel-overlay">
                        <div class="carousel-caption-custom">
                            <span class="carousel-tag" style="background-color: var(--accent-color);">Economic Trust</span>
                            <h2 class="display-5 fw-extrabold text-white font-display mb-3">Handshake Safety with PayFast Checkout</h2>
                            <p class="text-sm text-slate-200 mb-4 font-sans leading-relaxed">
                                Inspect items offline, try appliances before cash transfers, and rely on secure mobile checkout structures verified by operations supervisors.
                            </p>
                            <a href="about.php" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-3 border-0 transition-all font-display text-xs uppercase tracking-wide">
                                Read Safe Rules
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#blesstradeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#blesstradeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- 2. Platform Welcome Introduction -->
    <div class="card border border-light rounded-4 p-4 p-md-5 mb-5 shadow-xs bg-white">
        <div class="row g-4 align-items-center">
            <div class="col-md-8">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded inline-block mb-2">
                    Township Support standard
                </span>
                <h3 class="h3 fw-extrabold text-slate-900 font-display">Support Your Local Township Micro-Economy</h3>
                <p class="text-slate-500 font-sans leading-relaxed mb-0 mt-2" style="font-size:0.88rem;">
                    BlessTrade is a specialized Community-to-Community (C2C) micro-marketplace tailored specifically for micro-entrepreneurs and residents of South African townships in Soweto, Alex, and Tembisa. Trade secondhand items safely, connect directly on secure messaging links, and handle COD transactions easily.
                </p>
                <div class="d-flex gap-4 pt-3 mt-1">
                    <a href="about.php" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 text-decoration-none flex align-items-center gap-1">
                        Learn Our Story &rarr;
                    </a>
                    <a href="contact.php" class="text-xs font-bold text-slate-500 hover:text-slate-700 text-decoration-none flex align-items-center gap-1">
                        Contact Support Hotline &rarr;
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-emerald-50 rounded-4 p-4 border border-emerald-100/40" style="font-size:0.83rem; line-height: 1.6;">
                    <h5 class="font-display fw-bold text-emerald-950 mb-2.5 d-flex align-items-center gap-1.5 border-b pb-2">
                        <i data-lucide="shield-check" class="text-emerald-600" style="width:18px; height:18px;"></i>
                        Township Trust Rules
                    </h5>
                    <ul class="list-unstyled space-y-1.5 text-emerald-900 mb-0">
                        <li>💡 List items completely free</li>
                        <li>📍 Filter by local neighborhoods</li>
                        <li>🛡️ Verify specs before checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Featured Products Grid -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-baseline mb-4 border-bottom pb-2">
            <h3 class="h3 fw-extrabold text-slate-900 font-display d-flex align-items-center gap-2">
                <i data-lucide="grid" class="text-emerald-600"></i>
                Featured Township Products
            </h3>
            <a href="products.php" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 text-decoration-none">
                View All Products &rarr;
            </a>
        </div>

        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($prod = mysqli_fetch_assoc($result)): ?>
                    <div class="col-6 col-md-3">
                        <div class="product-grid-card" onclick="window.location.href='view_product.php?id=<?php echo $prod['id']; ?>'">
                            <div class="product-image-wrapper">
                                <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>" class="product-image-actual">
                                <span class="condition-badge <?php echo $prod['item_condition'] === 'new' ? 'badge-new' : 'badge-used'; ?>">
                                    <?php echo $prod['item_condition']; ?>
                                </span>
                            </div>
                            <div class="p-2 space-y-1 text-start pt-3">
                                <span class="category-tag-small">
                                    <?php echo htmlspecialchars($prod['category_name']); ?>
                                </span>
                                <h5 class="product-title-small text-truncate" title="<?php echo htmlspecialchars($prod['title']); ?>">
                                    <?php echo htmlspecialchars($prod['title']); ?>
                                </h5>
                                <div class="d-flex justify-content-between align-items-baseline mt-2">
                                    <span class="price-text-large">
                                        R <?php echo number_format($prod['price'], 2); ?>
                                    </span>
                                    <span class="text-xs text-muted font-mono" style="font-size:0.68rem;">
                                        <?php echo htmlspecialchars(explode(',', $prod['seller_location'])[0]); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 py-5 text-center text-muted">
                    No active catalogue products currently listed. Keep an eye out for updates!
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>