<?php
include "rfc822.php";
require_once "config.php";
require_once "common.php";

$errors = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_REQUEST['email'];
  $pass = $_REQUEST['pass'];
  $passconf = $_REQUEST['passconf'];
  $textcha = $_REQUEST['textcha'];
  
  if ($textcha != '365')
    $errors .= "Please enter the number of days in a year.<br/>";
  if (strlen($pass) < 6)
    $errors .= "Password must be at least six letters.<br/>";
  if ($pass != $passconf)
    $errors .= "Passwords do not match.<br/>";
  if (!is_valid_email_address($email))
    $errors .= "Please supply a valid email address.\n";
  else {
    $mysqli = dbconnect();
    $query = $mysqli->query("select email from users where email = '$email'") or die ("Error querying database: $mysqli->error()");
    if ($query->num_rows > 0)
      $errors .= "Email address already registered<br/>";
  }

  if ($errors == "") {
    $q = "insert into users (email, pass) values ('$email', '".crypt($pass, $email)."')";
    $mysqli->query($q) or die("Error inserting user: ".$mysqli->error);
    $result = $mysqli->query("select id from users where email = '$email'");
  $r = $result->fetch_assoc();
session_start();
  $_SESSION['user'] = $r['id'];
    header("Location: index.php");
    exit();
  }
} else {
  $email = "";
  $textcha = "";
}

myheader("Create an account", 0);
if ($errors != "") {
  echo("<em>Please correct the following errors:</em><br/>" . $errors . "<hr/>");
}
?>

<form id="newuser" action="adduser.php" method="post">
<label for="email">Email address:</label>
<input type="text" name="email"><?php echo $email?></input><br/>
<label for="pass">Password:</label>
<input type="password" name="pass"/><br/>
<label for="passconf">Please confirm your password:</label>
<input type="password" name="passconf"/><br/>
<label for="textcha">To demonstrate that you are (probably) a human being, please enter the number of days in a (non-leap) year.</label>
<input type="text" name="textcha" size="20"><?php echo $textcha;?></input><br/>
<input type="submit"/>
<?php myfooter()?>
