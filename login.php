<?php
require_once "config.php";
require_once "common.php";

$email = $_REQUEST['email'];
$pass = crypt($_REQUEST['password'], $email);

$mysqli = dbconnect();
$result = $mysqli->query("select id, email, pass from users where email = '$email'");
if ($result->num_rows == 0)
  $error = "Invalid username.<br/>\n";
else {
  $r = $result->fetch_assoc();
  if ($r['pass'] != $pass)
    #$error = "Incorrect password.<br/>\n";
    $error = "Incorrect password." . $pass . " / " . $r['pass'] . "<br/>\n";
  }

if (isset($error)) {
  myheader("login", 0);
  echo $error;
  showlogin($email);
  myfooter();
  exit();
}

session_start();
$_SESSION['user'] = $r['id'];
header("Location: index.php");
