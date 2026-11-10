<?php
// BlessTrade Township Marketplace sell.php
require_once 'includes/config.php';

// Safe check: enforce login session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch Categories for dropdown selection
$cats_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");
$categories = [];
while ($row = mysqli_fetch_assoc($cats_res)) {
    $categories[] = $row;
}

$error_msg = '';
$success_msg = '';

if ($user_role === 'seller' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim(mysqli_real_escape_string($conn, $_POST['title']));
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $item_condition = trim(mysqli_real_escape_string($conn, $_POST['item_condition']));
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $image_url = trim(mysqli_real_escape_string($conn, $_POST['image_url']));

    // Server-side validations checks
    if (empty($title) || $category_id <= 0 || $price <= 0 || empty($item_condition) || empty($description)) {
        $error_msg = 'Please fill in all mandatory product description fields.';
    } else {
        // Build image path. If URL blank, default to a robust placeholder.
        $img_path = !empty($image_url) ? $image_url : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80';
        
        $insert_query = "INSERT INTO products (title, description, price, category_id, item_condition, seller_id, image, status) 
                         VALUES ('$title', '$description', $price, $category_id, '$item_condition', $user_id, '$img_path', 'available')";

        if (mysqli_query($conn, $insert_query)) {
            $success_msg = 'Success! Your township trade item has been listed successfully.';
            echo "<script>
                    setTimeout(function(){
                        window.location.href = 'dashboard.php';
                    }, 2200);
                  </script>";
        } else {
            $error_msg = 'SQL Server failed to log trade listing to database.';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container my-5 text-start animate-in fade-in" style="max-width: 650px;">
    
    <!-- Title Section -->
    <div class="border-bottom pb-3 mb-4">
        <span class="text-xs text-emerald-700 uppercase fw-bold tracking-widest font-mono">Trader Services</span>
        <h1 class="h2 fw-extrabold text-slate-900 font-display mb-0">Post Free Trade Listing</h1>
        <p class="text-xs text-slate-500 mb-0 font-sans mt-1">List household items, smartphone specs, or apparel. BlessTrade collects zero broker fees!</p>
    </div>

    <?php if ($user_role !== 'seller'): ?>
        
        <!-- Upgrade Warning Notice: Replace hard block with useful interactive elements as per instructions -->
        <div class="card border border-warning rounded-4 p-4 p-md-5 bg-white text-center shadow-xs">
            <div class="bg-amber-100 text-amber-800 p-3.5 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; line-height: 1;">
                <i data-lucide="lock" style="width:28px; height:28px; vertical-align:middle; display:inline-block;"></i>
            </div>
            <h4 class="fw-extrabold text-slate-900 font-display">Upgrade to Seller Profile Needed</h4>
            <p class="text-slate-500 font-sans leading-relaxed text-sm max-width-md mx-auto mb-4" style="max-width:480px;">
                Your account is currently designated as a **Buyer**. In order to publish active listings and earn solid cash on BlessTrade:
            </p>

            <div class="bg-slate-50 border p-3 rounded-4 text-start mx-auto mb-4" style="max-width: 440px; font-size: 0.8rem; line-height: 1.6;">
                <span class="fw-bold text-slate-800 d-block mb-1.5 label text-xs uppercase font-mono text-center">Standard Seller Onboarding Checklist</span>
                <li class="list-unstyled mb-1">💡 Register items with multiple clear physical images</li>
                <li class="list-unstyled mb-1">🛡️ Answer buyer verification messages honestly</li>
                <li class="list-unstyled">💵 Agree to complete physical meetups at busy local malls</li>
            </div>

            <div class="d-flex gap-2 justify-content-center">
                <a href="contact.php" class="btn btn-warning text-dark font-display fw-bold text-xs py-2.5 px-3.5 rounded-3">
                    <i data-lucide="phone-call" class="me-1" style="width:14px; height:14px; vertical-align:middle;"></i> Request Role Switch
                </a>
                <a href="index.php" class="btn btn-emerald-50 text-emerald-800 border font-display text-xs fw-bold py-2.5 px-3.5 rounded-3">
                    Back to Portal
                </a>
            </div>
        </div>

    <?php else: ?>

        <!-- Active Seller listing form -->
        <div class="bg-white border rounded-4 p-4 p-md-5 shadow-xs">
            
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-4 text-start font-sans" style="font-size:11px; font-weight:700;">
                    ⚠️ <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success py-2.5 px-3 rounded-3 mb-4 text-start font-sans" style="font-size:12px; font-weight:700;">
                    🎉 <?php echo htmlspecialchars($success_msg); ?>
                </div>
            <?php endif; ?>

            <form action="sell.php" method="POST" class="space-y-4">
                <!-- Title -->
                <div class="mb-3 text-start">
                    <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Listing Item Title</label>
                    <input type="text" name="title" required placeholder="e.g. Samsung Galaxy S21 Ultra" class="form-control auth-form-control" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                </div>

                <div class="row">
                    <!-- Category Selection -->
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Trading Group Category</label>
                        <select name="category_id" required class="form-select auth-form-control" style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e\"); background-size: 11px; background-position: right 1rem center; background-repeat: no-repeat;">
                            <option value="0">Choose selection</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_POST['category_id']) && intval($_POST['category_id']) === intval($cat['id'])) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Asking Price (ZAR)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0; font-weight:700;">R</span>
                            <input type="number" name="price" required step="0.01" placeholder="2450.00" class="form-control auth-form-control border-start-0" style="border-radius:0 12px 12px 0;" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Condition selection -->
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Item Physical Condition</label>
                        <select name="item_condition" required class="form-select auth-form-control" style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e\"); background-size: 11px; background-position: right 1rem center; background-repeat: no-repeat;">
                            <option value="used" <?php echo (isset($_POST['item_condition']) && $_POST['item_condition'] === 'used') ? 'selected' : ''; ?>>Secondhand / Used (Good working state)</option>
                            <option value="new" <?php echo (isset($_POST['item_condition']) && $_POST['item_condition'] === 'new') ? 'selected' : ''; ?>>Brand New / Inbox Untouched</option>
                        </select>
                    </div>

                    <!-- Image URL -->
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Direct Image URL Link (Optional)</label>
                        <input type="url" name="image_url" placeholder="https://unsplash.com/photo-..." class="form-control auth-form-control" value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : ''; ?>">
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4 text-start">
                    <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Full Specifications Detail Description</label>
                    <textarea name="description" required rows="4" placeholder="Detail any cosmetic scratches, accessories included, age of item, and self-pickup parameters in your township area..." class="form-control auth-form-control" style="border-radius:14px;"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 w-100 text-white fw-bold py-3 rounded-3 shadow border-0" style="font-size:0.85rem;">
                    🚀 Publish Free Listing
                </button>
            </form>

        </div>

    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
