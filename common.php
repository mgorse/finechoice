<?php

require_once "config.php";

function showlogin()
{
  echo <<<EOD
<form id="login" action="login.php" method="post">
<label for="email">Email address:</label>
<input type="text" name="email"/><br/>
<label for="password">Password:</label>
<input type="password" name="password"/><br/>
<input type="submit" value="Log In"/>
</form>
<a href="adduser.php">Register</a>
EOD;
}

// login_mode: 0=don't show, 1=show, 2=force (default)
function myheader($title, $login_mode = 2) {
  session_start();
  echo <<<EOD
<html>
<head>
<title>$title</title>
</head>
<body>
EOD;

  if (!isset($_SESSION['user']))
  {
    if ($login_mode > 0)
      showlogin();
    if ($login_mode == 2) {
      myfooter();
      exit();
    }
  }
}

function myfooter()
{
  echo <<<EOD
</body>
</html>
EOD;
}

function dbconnect() {
  if (!isset($GLOBALS['mysqli'])) {
    $GLOBALS['mysqli'] = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['dbname']);
    if (mysqli_connect_errno())
      die("Error connecting to database: " . mysqli_connect_error());
  }
  return $GLOBALS['mysqli'];
}

function budgetname($id)
{
  $mysqli = dbconnect();
  $q = $mysqli->query("select name from budgets where id = $id");
  $r = $q->fetch_assoc();
  $name = $r['name'];
  $q->free_result();
  return $name;
}

?>
