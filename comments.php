<?php
require_once "config.php";
require_once "common.php";

if (!isset($_REQUEST['id'])) {
  header("Location: index.php");
  exit();
}

$mysqli = dbconnect();

// TODO: Query for the name of the item and display it
$id = $_REQUEST['id'];

myheader("View comments");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $comment = $_REQUEST['comment'];
  if ($comment != "") {
    $comment_escaped = $mysqli->escape_string($comment);
    $mysqli->query("insert into comments (user, item, time, comment) values (".$_SESSION['user'].", $id, current_timestamp(), '".$comment_escaped."')");
  }
}

$q = $mysqli->query("select users.email, time, comment from comments join users on users.id = comments.user where comments.item = $id");
for ($i = 0; $i < $q->num_rows; $i++) {
  $q->data_seek($i);
  $r = $q->fetch_assoc();
  echo "Comment #".$i + 1 ."(".$r['email']."): ".$r['time']."<br>\n";
  echo $r['comment']."<br/>\n";
}
?>

<form id="comment" action="comments.php" method="post">
<input type="hidden" name="id" value="<?php echo $id;?>"/>
<label for="comment">Enter a new comment:</label>
<textarea name="comment" rows="10" cols="80"></textarea>
<input type="submit"/>
</form>

<?php myfooter()?>
