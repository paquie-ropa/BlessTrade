<?php
// BlessTrade Township Marketplace products.php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch Categories for filters
$cats_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");
$categories = [];
while ($row = mysqli_fetch_assoc($cats_res)) {
    $categories[] = $row;
}

// Extract filter parameters
$search = isset($_GET['search']) ? trim(mysqli_real_escape_string($conn, $_GET['search'])) : '';
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Construct dynamic querying with search parameters
$where_clauses = ["p.status = 'available'"];

if (!empty($search)) {
    $where_clauses[] = "(p.title LIKE '%$search%' OR p.description LIKE '%$search%')";
}

if ($category_id > 0) {
    $where_clauses[] = "p.category_id = $category_id";
}

$where_sql = implode(' AND ', $where_clauses);

$p_query = "SELECT p.*, c.name AS category_name, u.location AS seller_location 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON p.seller_id = u.id 
            WHERE $where_sql 
            ORDER BY p.id DESC";

$p_res = mysqli_query($conn, $p_query);
?>

<div class="container my-5 text-start animate-in fade-in">
    
    <!-- Header banner -->
    <div class="border-bottom pb-3 mb-4">
        <span class="text-xs text-emerald-700 uppercase fw-bold tracking-widest font-mono">Market Catalogues</span>
        <h1 class="h2 fw-extrabold text-slate-900 font-display mb-0">Browse Listed Township Products</h1>
    </div>

    <!-- Active Search Filter Panel Controls -->
    <div class="bg-white border rounded-4 p-4 shadow-xs mb-5">
        <form action="products.php" method="GET">
            <div class="row g-3">
                <!-- Search term input group -->
                <div class="col-md-5">
                    <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Search Title / Spec keywords</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                            <i data-lucide="search" style="width:16px; height:16px;"></i>
                        </span>
                        <input type="text" name="search" class="form-control auth-form-control border-start-0" placeholder="e.g. Fridge, Galaxy, Huawei..." value="<?php echo htmlspecialchars($search); ?>" style="border-radius:0 12px 12px 0;">
                    </div>
                </div>

                <!-- Category selector selection option -->
                <div class="col-md-4">
                    <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Filter by Category</label>
                    <select name="category" class="form-select auth-form-control" style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e\"); background-size: 11px; background-position: right 1rem center; background-repeat: no-repeat;">
                        <option value="0">All Trading Groups</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $category_id === intval($cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Run Filters Button -->
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 w-100 text-white fw-bold py-3.5 rounded-3 border-0 transition-all text-xs uppercase tracking-wide">
                        Filter Directory
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Grid catalog display of products -->
    <div class="row g-4">
        <?php if ($p_res && mysqli_num_rows($p_res) > 0): ?>
            <?php while ($prod = mysqli_fetch_assoc($p_res)): ?>
                <div class="col-6 col-md-3">
                    <div class="product-grid-card" onclick="window.location.href='view_product.php?id=<?php echo $prod['id']; ?>'">
                        <div class="product-image-wrapper">
                            <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="item image" class="product-image-actual">
                            <span class="condition-badge <?php echo $prod['item_condition'] === 'new' ? 'badge-new' : 'badge-used'; ?>">
                                <?php echo strtoupper($prod['item_condition']); ?>
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
                <i data-lucide="info" class="mx-auto mb-2 text-slate-400 d-block" style="width:32px; height:32px;"></i>
                <p class="mb-0 fw-semibold text-slate-700">No active products match your filter parameters.</p>
                <a href="products.php" class="text-xs text-emerald-700 fw-bold hover:underline d-inline-block mt-2">Reset directory search &rarr;</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
