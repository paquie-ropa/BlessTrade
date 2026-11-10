<?php
// BlessTrade Township Marketplace checkout.php
require_once 'includes/config.php';

// Check login session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: products.php');
    exit;
}

// Fetch single product joining category and seller details
$query = "SELECT p.*, u.fullname AS seller_name FROM products p LEFT JOIN users u ON p.seller_id = u.id WHERE p.id = $product_id AND p.status = 'available' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: products.php');
    exit;
}

$product = mysqli_fetch_assoc($result);

$buyer_id = $_SESSION['user_id'];
$buyer_phone = $_SESSION['user_phone'] ?: '+27 81 000 0000';
$buyer_location = $_SESSION['user_location'] ?: 'Johannesburg, GP';

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardholder = trim(mysqli_real_escape_string($conn, $_POST['cardholder']));
    $cardnumber = trim($_POST['cardnumber']);
    $expiry = trim($_POST['expiry']);
    $cvv = trim($_POST['cvv']);
    
    // Core simulated card validation
    if (empty($cardholder) || empty($cardnumber) || empty($expiry) || empty($cvv)) {
        $error_msg = 'Please fill in all credit card payment details.';
    } elseif (strlen(str_replace(' ', '', $cardnumber)) < 16) {
        $error_msg = 'Invalid Credit Card Number (must be exactly 16 digits).';
    } elseif (strlen($cvv) < 3) {
        $error_msg = 'Invalid CVV code (must be 3 or 4 digits on back).';
    } else {
        // Validation complete: Initiate database records insertion
        mysqli_begin_transaction($conn);
        try {
            // 1. Create simulated order table insert
            $seller_id = $product['seller_id'];
            $amount = $product['price'];
            $insert_query = "INSERT INTO orders (product_id, buyer_id, seller_id, amount, buyer_phone, buyer_location, status) 
                             VALUES ($product_id, $buyer_id, $seller_id, $amount, '$buyer_phone', '$buyer_location', 'pending')";
            mysqli_query($conn, $insert_query);

            // 2. Adjust products table, mark as sold
            $update_query = "UPDATE products SET status = 'sold' WHERE id = $product_id";
            mysqli_query($conn, $update_query);

            mysqli_commit($conn);
            $success_msg = 'Payment complete or COD booked! You have successfully reserved the item.';
            // Set cookie/session indicator and auto redirect in a few seconds
            echo "<script>
                    setTimeout(function(){
                        window.location.href = 'dashboard.php';
                    }, 2500);
                  </script>";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error_msg = 'Database transaction failed. Please retry your card checkout.';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container my-5 text-start animate-in fade-in">
    
    <div class="row g-4 justify-content-center">
        <!-- Col 1: Product summary card -->
        <div class="col-md-5">
            <div class="bg-white border rounded-4 p-4 shadow-xs h-100">
                <h5 class="fw-bold text-slate-900 border-b pb-2 mb-3 font-display">🛒 Checkout Order Summary</h5>
                
                <div class="d-flex align-items-center gap-3 p-2 bg-slate-50 rounded-3 mb-4">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Selected Listing Preview" class="img-fluid rounded border-1" style="height:70px; width:70px; object-fit:contain;">
                    <div class="text-start">
                        <span class="text-[9px] text-muted uppercase tracking-widest fw-bold font-mono">Simulated Buy Reservation</span>
                        <h6 class="fw-bold text-slate-800 text-truncate mb-0.5" style="max-width: 200px; font-size: 0.88rem;"><?php echo htmlspecialchars($product['title']); ?></h6>
                        <span class="text-xs text-muted d-block">Seller Merchant: <?php echo htmlspecialchars($product['seller_name']); ?></span>
                    </div>
                </div>

                <!-- Logistics Delivery details -->
                <div class="space-y-3 font-sans text-xs text-slate-600">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Listed Price ZAR:</span>
                        <span class="fw-bold">R <?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Commission Fee:</span>
                        <span class="text-success fw-bold">R 0.00 (Free listing)</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Trading Location:</span>
                        <span class="fw-semibold"><?php echo htmlspecialchars($buyer_location); ?></span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between text-base text-slate-900 fw-bold" style="font-size: 0.95rem;">
                        <span>Total Due Reserve:</span>
                        <span class="text-emerald-600">R <?php echo number_format($product['price'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 2: PayFast Simulated Payment panel -->
        <div class="col-md-6">
            <div class="bg-white border rounded-4 p-4 p-md-5 shadow-sm h-100">
                
                <div class="d-flex align-items-center justify-content-between border-b pb-2.5 mb-4">
                    <h5 class="fw-bold text-slate-950 font-display mb-0">🔒 PayFast Secured Check</h5>
                    <!-- PayFast badge mockup -->
                    <span class="badge bg-danger text-white uppercase font-mono px-2 py-1" style="font-size:0.65rem; border-radius:4px;">PayFast Simulated</span>
                </div>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger py-2 px-3 rounded-3 mb-4" style="font-size: 11px; font-weight:700;">
                        ⚠️ <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success py-2.5 px-3 rounded-3 mb-4" style="font-size: 11px; font-weight:700;">
                        🎉 <?php echo htmlspecialchars($success_msg); ?> 
                        <br><span class="text-xs text-gray-400 font-sans">Redirecting to My Dashboard...</span>
                    </div>
                <?php endif; ?>

                <form action="checkout.php?id=<?php echo $product_id; ?>" method="POST" class="space-y-3.5">
                    <!-- Holder Name -->
                    <div class="mb-3 text-start">
                        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size: 0.75rem;">Cardholder Name</label>
                        <input type="text" name="cardholder" required class="form-control auth-form-control" placeholder="e.g. Lindiwe Dlamini" value="<?php echo isset($_POST['cardholder']) ? htmlspecialchars($_POST['cardholder']) : htmlspecialchars($_SESSION['user_fullname']); ?>">
                    </div>

                    <!-- Card Number -->
                    <div class="mb-3 text-start">
                        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size: 0.75rem;">Credit Card Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                                <i data-lucide="credit-card" style="width:16px; height:16px;"></i>
                            </span>
                            <input type="text" name="cardnumber" required minlength="16" maxlength="19" class="form-control auth-form-control border-start-0" placeholder="4000 1234 5678 9010" style="border-radius:0 12px 12px 0;" value="<?php echo isset($_POST['cardnumber']) ? htmlspecialchars($_POST['cardnumber']) : ''; ?>">
                        </div>
                    </div>

                    <!-- Expiration and CVV -->
                    <div class="row mb-4">
                        <div class="col-6 text-start">
                            <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size: 0.75rem;">Expiry Date</label>
                            <input type="text" name="expiry" required placeholder="MM/YY" maxlength="5" class="form-control auth-form-control" value="<?php echo isset($_POST['expiry']) ? htmlspecialchars($_POST['expiry']) : ''; ?>">
                        </div>
                        <div class="col-6 text-start">
                            <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size: 0.75rem;">Security Code (CVV)</label>
                            <input type="password" name="cvv" required placeholder="123" maxlength="4" class="form-control auth-form-control" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 w-100 text-white fw-bold py-3.5 mt-2 rounded-3 border-0 transition-all font-sans text-sm">
                        🏦 Verify and Pay R <?php echo number_format($product['price'], 2); ?>
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
