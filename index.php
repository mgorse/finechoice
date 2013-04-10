<?php
include "rfc822.php";
require_once "config.php";
require_once "common.php";

myheader("Home");

$mysqli = dbconnect();
$q = $mysqli->query("select * from budgetauth where user = '".$_SESSION['user']."'");
if ($q->num_rows == 0)
  echo("<p>You have no budgets at present.</p>");
else {
  echo "<ul>\n";
  $nr = $q->num_rows;
  for ($i = 0;$i < $nr; $i++) {
    echo $i;
    $q->data_seek($i);
    $r = $q->fetch_assoc();
    $id = $r['budget'];
    echo "<li><a href=\"survey.php?id=$id\">";
    echo budgetname($id);
    echo "</a> | <a href=\"results.php?id=$id\">view results</a>";
    if ($r['admin'])
      echo " | <a href=\"edit.php?id=$id\">edit</a>";
    echo "</li>\n";
  }
  echo "</ul\n";
}
?>
<a href="edit.php">Create budget</a>
<?php myfooter()?>
