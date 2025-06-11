<?php
require_once __DIR__ . '/Koneksi.php';

class Auth extends Koneksi
{
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();
  }

  /**
   * Fungsi untuk login pengguna
   *
   * @param string $username Nama pengguna
   * @param string $password Kata sandi
   * @return array{id_pengguna: int, username: string, level: int}|false Data pengguna jika login berhasil, false jika gagal
   */
  public function login($email, $password)
  {
    $sql = "SELECT * FROM auth WHERE email=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if (password_verify($password, $row['password'])) {
        return [
          'id_pengguna' => $row['id_pengguna'],
          'email' => $row['email'],
          'level' => $row['level']
        ];
      }
    }
    return false;
  }

  public function register($email, $username, $password)
  {
    $cek = $this->conn->prepare("SELECT * FROM auth WHERE username = ? OR email = ?");
    $cek->bind_param("ss", $username, $email);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
      return false;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $level = 1;

    $stmt = $this->conn->prepare("INSERT INTO auth (email, username, password, level) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $email, $username, $passwordHash, $level);
    return $stmt->execute();
  }
}
