<?php
require_once "config.php";
require_once "common.php";
require "score.php";

if (!isset($_REQUEST['id'])) {
  header("Location: index.php");
  exit();
}

$mysqli = dbconnect();

$id = $_REQUEST['id'];
/// tODO: print name of budget
myheader("View results");
$q = $mysqli->query("select * from selections join items on items.id = selections.item where items.budget = $id");

$counts = array();
$amounts = array();
$costs = array();
$names = array();
$itemcount = 0;
$i2d = array();
$d2i = array();
for ($i = 0; $i < $q->num_rows; $i++) {
  $q->data_seek($i);
  $r = $q->fetch_assoc();
  $item = $r['item'];
  if (!isset($d2i[$item])) {
    $d2i[$item] = $itemcount;
    $i2d[$itemcount] = $item;
    $index = $itemcount++;
    $counts[$index] = 1;
    $names[$index] = $r['name'];
    $amounts[$index] = 0;
  } else {
    $index = $d2i[$item];
    $counts[$index]++;
  }
  $amounts[$index] += $r['amount'];
  $costs[$index] = $r['mincost'];
}
$q->free_result();

$q = $mysqli->query("select amount from budgets where id = $id");
$r = $q->fetch_assoc();
$limit = $r['amount'];
$q->free_result();

$items = score($counts, $amounts, $costs, $limit);

  $count = count($counts);
for ($i = 0; $i < $count; $i++)
  if ($items[$i])
    echo $names[$i].': $'.$costs[$i]."<br/>";

?>
<?php myfooter()?>
