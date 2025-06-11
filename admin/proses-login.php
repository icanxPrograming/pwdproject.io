<?php
require_once '../model/Auth.php';
require_once '../model/Session.php';

$auth = new Auth();
$session = new AppSession();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $user = $auth->login($email, $password);

  if ($user !== false) {
    $session->createSession($user);

    if ($session->isAdmin()) {
      header("Location: dashboard.php");
    } else {
      header("Location: ../index.php");
    }
    exit();
  } else {
    header("Location: login.php?error=invalid");
    exit();
  }
}
