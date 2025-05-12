<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Alter-Ex</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .login-container {
      max-width: 500px;
      margin: 100px auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: white;
    }

    .login-logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-logo img {
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

    .btn-login {
      background-color: #f60;
      border: none;
      padding: 10px;
      font-weight: 600;
    }

    .btn-login:hover {
      background-color: #e55b00;
    }

    .login-footer {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body style="background-color: #f8f9fa;">
  <div class="container">
    <div class="login-container">
      <div class="login-logo">
        <img src="../asset/Logo Alter-Ex.png" alt="Alter-Ex Logo">
      </div>
      <form action="proses-login.php" method="POST">
        <div class="mb-4 position-relative">
          <i class="fas fa-envelope input-icon"></i>
          <div class="form-floating">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            <label for="email">Email</label>
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
          <button type="submit" name="login" class="btn btn-primary btn-login py-3">LOGIN</button>
        </div>

        <div class="d-grid mb-3">
          <a href="../index.php" name="batal" class="btn btn-secondary w-100 py-3">BATAL</a>
        </div>

        <div class="d-flex justify-content-between mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
          </div>
          <a href="forgot-password.php" class="text-decoration-none">Lupa password?</a>
        </div>
      </form>

      <div class="login-footer">
        <p class="mb-3">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar sekarang</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>