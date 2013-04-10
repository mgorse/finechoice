<?php
include "rfc822.php";
require_once "config.php";
require_once "common.php";

$mysqli = dbconnect();

if (isset($_REQUEST['id']))
  $id = $_REQUEST['id'];
else
  $id = 0;
myheader($id == 0 ? "Create a budget": "Edit budget");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_REQUEST['name'];
  $name_escaped = $mysqli->escape_string($name);
  $amount = $_REQUEST['amount'];
  $newitems = $_REQUEST['newitem'];
  $newcosts = $_REQUEST['newcost'];
  $newemails = $_REQUEST['newemail'];
  if ($id == 0) {
    $mysqli->query("insert into budgets (name, amount) values ('$name_escaped', $amount)");
    $id = $mysqli->insert_id;
    $mysqli->query("insert into budgetauth (user, budget, admin) values (".$_SESSION['user'].", $id, true)");
  }

  foreach ($newitems as $i => $item) {
    if ($item != "") {
      $cost = $newcosts[$i];
      $item_escaped = $mysqli->escape_string($item);
      $mysqli->query("insert into items (budget, name, mincost, maxcost) values ($id, '$item_escaped', $cost, $cost)");
    }
  }

  foreach ($newemails as $email) {
    if ($email == "")
      continue;
    $email_escaped = $mysqli->escape_string($email);
    $result = $mysqli->query("select id from users where email = '$email_escaped'");
    if ($result->num_rows > 0) {
      $r = $result->fetch_assoc();
      $user = $r['id'];
    } else {
      echo("insert into users (email) values ('$email_escaped')");
      $mysqli->query("insert into users (email) values ('$email_escaped')");
      $user = $mysqli->insert_id;
    }
    $result->free_result();
    $mysqli->query("insert into budgetauth (user, budget, admin) values ($user, $id, false)");
  }
}
?>

<form id="budgetadmin" action="edit.php" method="post">
<input type="hidden" name="id" value="<?php echo $id;?>"/>
<label for="name">Name:</label>
<input type="text" name="name" size="50"/>
<label for="amount">Amount:</label>
<input type="text" name="amount" size="10"/>
<table>
<th>
<td>Item name</td>.
<td>Cost</td>
</th>
<?php
for ($i = 0; $i < 10; $i++) {
  echo "<tr>\n";
  echo "<td><input type=\"text\" name=\"newitem[$i]\"size=\"50\"/></td>\n";
  echo "<td><input type=\"text\" name=\"newcost[$i]\"size=\"10\"/></td>\n";
  echo "</tr>\n";
}
?>
</table>
<p>Invite participants (enter email addresses):</p>
<?php
for ($i = 0; $i < 10; $i++)
  echo "<input type=\"text\" name=\"newemail[$i]\" size=\"50\"//><br/>\n";
?>
<input type="submit"/>
</form>

<?php myfooter()?>
