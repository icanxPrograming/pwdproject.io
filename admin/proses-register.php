<?php
session_start();
require_once("../model/Auth.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $username = $_POST["username"];
  $password = $_POST["password"];

  $auth = new Auth();
  $register = $auth->register($email, $username, $password);

  if ($register) {
    header("Location: login.php");
    exit;
  } else {
    $_SESSION['error'] = "Username atau Email sudah terdaftar.";
    header("Location: register.php");
    exit;
  }
} else {
  header("Location: register.php");
  exit;
}
