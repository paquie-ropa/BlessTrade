<?php
// BlessTrade Township Marketplace register.php
require_once 'includes/config.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim(mysqli_real_escape_string($conn, $_POST['fullname']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);
    $role = trim(mysqli_real_escape_string($conn, $_POST['role']));
    $phone = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $location = trim(mysqli_real_escape_string($conn, $_POST['location']));

    // Server-side validations checks
    if (empty($fullname) || empty($email) || empty($password) || empty($phone) || empty("location")) {
        $error_msg = 'All listing fields are mandatory to secure trading circles.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please input a valid secure email address.';
    } elseif (strlen($password) < 6) {
        $error_msg = 'Secure passwords must be at least 6 characters in length.';
    } else {
        // Enforce unique email check
        $check_res = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
        if ($check_res && mysqli_num_rows($check_res) > 0) {
            $error_msg = 'An account already exists using that email address.';
        } else {
            // Hash password securely via BCrypt standard algorithm
            $hashedPass = password_hash($password, PASSWORD_BCRYPT);
            $insert_query = "INSERT INTO users (fullname, email, password, phone, location, role) 
                             VALUES ('$fullname', '$email', '$hashedPass', '$phone', '$location', '$role')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success_msg = 'Account registered successfully! Redirecting to login portal...';
                echo "<script>
                        setTimeout(function(){
                            window.location.href = 'login.php';
                        }, 2200);
                      </script>";
            } else {
                $error_msg = 'SQL Server failed to record registration profile parameters.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container my-5 max-width-md" style="max-width: 530px;">
    
    <div class="text-center mb-4">
        <div class="bg-emerald-600 p-3 rounded-4 text-white shadow-lg mx-auto mb-3" style="width:58px; height:58px; line-height:1;">
            <i data-lucide="shopping-bag" style="width:32px; height:32px;"></i>
        </div>
        <h2 class="fw-bold tracking-tight font-display text-slate-950">Bless<span class="text-emerald-600">Trade</span> Marketplace</h2>
        <p class="text-xs text-muted">Join the township commerce circle and trade safely offline</p>
    </div>

    <div class="auth-panel-card p-4 p-md-5">
        
        <h4 class="text-center font-display fw-bold text-slate-900 mb-4 h5">Create Trading Profile</h4>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger py-2.5 px-3 rounded-3 text-start mb-4" style="font-size: 11px; font-weight:700;">
                ⚠️ <?php echo htmlspecialchars($error_msg); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success py-2.5 px-3 rounded-3 text-start mb-4" style="font-size: 11px; font-weight:700;">
                🎉 <?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="space-y-3.5">
            <!-- Full Name -->
            <div class="mb-3 text-start">
                <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Full Trading Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                        <i data-lucide="user" style="width:16px; height:16px;"></i>
                    </span>
                    <input type="text" name="fullname" required placeholder="e.g. Lindiwe Dlamini" class="form-control auth-form-control border-start-0" style="border-radius:0 12px 12px 0;" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3 text-start">
                <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Secure Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                        <i data-lucide="mail" style="width:16px; height:16px;"></i>
                    </span>
                    <input type="email" name="email" required placeholder="lindiwe@domain.co.za" class="form-control auth-form-control border-start-0" style="border-radius:0 12px 12px 0;" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>

            <!-- Role Selector -->
            <div class="mb-3 text-start">
                <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Primary Platform Role</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                        <i data-lucide="badge-percent" style="width:16px; height:16px;"></i>
                    </span>
                    <select name="role" required class="form-select auth-form-control border-start-0 text-slate-700" style="border-radius:0 12px 12px 0; background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e\"); background-size: 11px; background-position: right 1rem center; background-repeat: no-repeat;">
                        <option value="buyer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'buyer') ? 'selected' : ''; ?>>Buyer - Want to purchase items safely</option>
                        <option value="seller" <?php echo (isset($_POST['role']) && $_POST['role'] === 'seller') ? 'selected' : ''; ?>>Seller - Want to post trade items easily</option>
                    </select>
                </div>
            </div>

            <!-- Contact Cell -->
            <div class="mb-3 text-start">
                <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Cellular Number (WhatsApp-enabled preferred)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                        <i data-lucide="phone" style="width:16px; height:16px;"></i>
                    </span>
                    <input type="text" name="phone" required placeholder="e.g. +27 81 234 5678" class="form-control auth-form-control border-start-0" style="border-radius:0 12px 12px 0;" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
            </div>

            <!-- Coordinate Location -->
            <div class="mb-3 text-start">
                <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Trading Location / Neighborhood Township</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                        <i data-lucide="map-pin" style="width:16px; height:16px;"></i>
                    </span>
                    <input type="text" name="location" required placeholder="e.g. Orlando, Soweto" class="form-control auth-form-control border-start-0" style="border-radius:0 12px 12px 0;" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4 text-start">
                <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Account Password Signature</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px; border-color:#e2e8f0;">
                        <i data-lucide="lock" style="width:16px; height:16px;"></i>
                    </span>
                    <input type="password" name="password" required placeholder="Min 6 characters" class="form-control auth-form-control border-start-0" style="border-radius:0 12px 12px 0;">
                </div>
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn btn-emerald-600 bg-emerald-600 hover:bg-emerald-700 w-100 text-white fw-bold py-3 rounded-3 shadow border-0" style="font-size:0.85rem;">
                Create Account Profile
            </button>
        </form>

        <p class="text-center text-muted text-xs mt-4 mb-0" style="font-size:0.78rem;">
            Already have a township account? <a href="login.php" class="text-emerald-700 fw-bold hover:underline">Log in here</a>
        </p>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
