<?php

include __DIR__ . '/../logic/registerLogic.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <h3 class="card-title text-center mb-4">Create Account</h3>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label for="username" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
              <label for="id" class="form-label">user id</label>
              <input type="text" class="form-control" id="id" name="id" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>

          <p class="text-center mt-3">
            Already have an account? <a href="index.php">Login here</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>