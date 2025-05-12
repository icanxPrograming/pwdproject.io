<?php
session_start();
require_once("../model/Auth.php");

$auth = new Auth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $login = $auth->login($email, $password);
  if ($login) {
    header("location:dashboard.php");
  } else {
    header("location:login.php");
  }
} else {
  header("location:login.php");
}
