<?php
require_once __DIR__ . '/../model/Session.php';
$session = new AppSession();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Alter-Ex</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Custom CSS -->
  <style>
    .register-container {
      max-width: 500px;
      margin: 100px auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: white;
    }

    .register-logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .register-logo img {
      width: 100px;
      height: auto;
    }

    .form-floating label {
      padding-left: 40px;
    }

    .form-floating .form-control {
      padding-left: 40px;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      z-index: 5;
    }

    .btn-register {
      background-color: #28a745;
      border: none;
      padding: 10px;
      font-weight: 600;
    }

    .btn-register:hover {
      background-color: #218838;
    }

    .register-footer {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body style="background-color: #f8f9fa;">
  <div class="container">
    <div class="register-container">
      <div class="register-logo">
        <img src="../asset/Logo Alter-Ex.png" alt="Alter-Ex Logo">
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form action="proses-register.php" method="POST">
        <div class="mb-4 position-relative">
          <i class="fas fa-envelope input-icon"></i>
          <div class="form-floating">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            <label for="email">Email</label>
          </div>
        </div>

        <div class="mb-4 position-relative">
          <i class="fas fa-user input-icon"></i>
          <div class="form-floating">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            <label for="username">Username</label>
          </div>
        </div>

        <div class="mb-4 position-relative">
          <i class="fas fa-lock input-icon"></i>
          <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <label for="password">Password</label>
          </div>
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-register text-white py-3">DAFTAR</button>
        </div>

        <div class="d-grid mb-3">
          <a href="login.php" class="btn btn-secondary w-100 py-3">KEMBALI KE LOGIN</a>
        </div>
      </form>

      <div class="register-footer">
        <p class="mb-3">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login disini</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>