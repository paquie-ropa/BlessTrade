<?php
require_once 'includes/config.php';

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
  if (!empty($email)) {
    $res = mysqli_query($conn, "SELECT id, fullname FROM users WHERE email = '$email' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
      // Simulate sending reset email
      $msg = 'Password reset instructions have been sent to ' . htmlspecialchars($email) . '. Check your inbox (simulation only — use your existing password to log in).';
      $msg_type = 'success';
    } else {
      $msg = 'No BlessTrade account found with that email address. Please register a new account.';
      $msg_type = 'danger';
    }
  } else {
    $msg = 'Please enter your email address.';
    $msg_type = 'warning';
  }
}

require_once 'includes/header.php';
?>
<div class="container my-5" style="max-width:460px;">
  <div class="text-center mb-4">
    <div class="bg-amber-500 p-3 rounded-4 text-white shadow mx-auto mb-3" style="width:56px;height:56px;line-height:1;background-color:#f59e0b;">
      <i data-lucide="key-round" style="width:30px;height:30px;color:white;"></i>
    </div>
    <h2 class="fw-bold font-display text-slate-900">Reset Your Password</h2>
    <p class="text-xs text-muted">Enter your email and we'll send reset instructions</p>
  </div>
  <div class="auth-panel-card p-4 p-md-5">
    <?php if (!empty($msg)): ?>
      <div class="alert alert-<?php echo $msg_type; ?> py-2.5 px-3 rounded-3 mb-4 text-start" style="font-size:11px;font-weight:700;">
        <?php echo $msg; ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="forgot_password.php">
      <div class="mb-4 text-start">
        <label class="form-label text-slate-500 fw-semibold mb-1" style="font-size:0.75rem;">Registered Email Address</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius:12px 0 0 12px;border-color:#e2e8f0;">
            <i data-lucide="mail" style="width:16px;height:16px;"></i>
          </span>
          <input type="email" name="email" required placeholder="email@example.com"
            class="form-control auth-form-control border-start-0"
            style="border-radius:0 12px 12px 0;"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
      </div>
      <button type="submit" class="btn w-100 text-white fw-bold py-3 rounded-3 border-0 shadow"
        style="background-color:#d97706;font-size:0.85rem;">
        Send Reset Instructions
      </button>
    </form>
    <p class="text-center text-muted text-xs mt-4 mb-0">
      Remember your password? <a href="login.php" class="text-emerald-700 fw-bold">Sign in here</a>
    </p>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
