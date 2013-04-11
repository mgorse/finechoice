<?php
require_once "config.php";
require_once "common.php";

if (!isset($_REQUEST['id'])) {
  header("Location: index.php");
  exit();
}

$mysqli = dbconnect();

$id = $_REQUEST['id'];
$q = $mysqli->query("select name, amount from budgets where id = $id");
$r = $q->fetch_assoc();
$bname = $r['name'];
$bamount = $r['amount'];
$q->free_result();
myheader($bname);
$uid = $_SESSION['user'];

$q = $mysqli->query("select id, name, mincost, maxcost from items where budget = $id");
for ($i = 0; $i < $q->num_rows; $i++) {
  $q->data_seek($i);
  $r = $q->fetch_assoc();
  $iid = $r['id'];
  $names[$iid] = $r['name'];
  $mincosts[$iid] = $r['mincost'];
  $maxcosts[$iid] = $r['mincost'];
}
$q->free_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_REQUEST['amount']))
    $amounts = $_REQUEST['amount'];
  else
    $amounts = array();
  $tot = 0;
  foreach ($amounts as $amount)
    $tot += $amount;

  if ($tot > $bamount)
    $error = "<b>Total cannot exceed $bamount.<br/>";
  else {
    foreach ($names as $iid => $name) {
      $mysqli->query("delete from selections where user = $uid and item = $iid");
      if (isset($amounts[$iid]))
        $mysqli->query("insert into selections (user, item, amount) values ($uid, $iid, $amounts[$iid])");
    }
    echo "<p><em>Fine choice!</em></p>\n";
  }
}

if (isset($error))
  echo $error;
?>

<form id="survey" action="survey.php" method="post">
<input type="hidden" name="id" value="<?php echo $id;?>"/>
<?php
foreach ($names as $iid => $name) {
  echo '<input type="checkbox" name="amount['.$iid.']" value="'.$mincosts[$iid].'">'.$name.'</input>';
  echo ' ($'.$mincosts[$iid].')';
  // TODO: open in a new window
  echo ' | <a href="comments.php?id='.$iid.'">view comments</a>';
  echo "<br>\n";
}
?>
<input type="submit"/>
</form>

<?php myfooter()?>
