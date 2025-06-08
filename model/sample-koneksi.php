<?php

class Sample_Koneksi
{
  private $host = 'Your Host';
  private $user = 'Your User Database';
  private $password = 'Your Password Database';
  private $dbname = 'Your DB NAME';

  private $conn;

  public function __construct()
  {
    $this->conn = new mysqli('YOUR HOST', 'YOUR USER', 'YOUR PASS', 'YOUR DB');

    if ($this->conn->connect_error) {
      die('Tidak terhubung ke database ' . $this->conn->connect_error);
    }
  }

  public function getConnection()
  {
    return $this->conn;
  }
}
