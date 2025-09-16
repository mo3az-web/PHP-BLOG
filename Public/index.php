<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Includes/jsonUtils.php';
// here we include the login logic to handle form submission
require_once '../logic/login.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom stylesheet -->
  <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 25rem; border-radius: 1.5rem;">
      <h3 class="text-center mb-4">Login</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input 
            type="email" 
            class="form-control" 
            id="email" 
            name="email" 
            placeholder="name@example.com"
            value="<?= isset($email) ? htmlspecialchars($email) : (isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '') ?>"
            required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input 
            type="password" 
            class="form-control" 
            id="password" 
            name="password" 
            placeholder="password" 
            required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
          </div>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>

        <p class="text-center mt-3 small text-muted">
          Don't have an account? <a href="register.php">Sign up</a>
        </p>
      </form>
    </div>
  </div>

</body>
</html>
