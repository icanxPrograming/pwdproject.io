<?php
require_once __DIR__ . '/Koneksi.php';

class AppSession extends Koneksi
{
  protected $conn;

  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function createSession($userData)
  {
    $_SESSION['id_pengguna'] = $userData['id_pengguna'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['level'] = $userData['level'];
  }

  public function destroySession()
  {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }

    session_destroy();
  }

  public function isLoggedIn()
  {
    return isset($_SESSION['id_pengguna']);
  }

  public function isAdmin()
  {
    return isset($_SESSION['level']) && $_SESSION['level'] == 0;
  }

  public function getUserData()
  {
    if (!$this->isLoggedIn()) {
      return null;
    }

    return [
      'id_pengguna' => $_SESSION['id_pengguna'],
      'username' => $_SESSION['username'],
      'level' => $_SESSION['level']
    ];
  }
}
