<?php

require_once "db.php";
$query = "DELETE FROM user WHERE uid=:uid";
$resignStmt = $pdo->prepare($query);
$resignStmt->execute([
  "uid" => $_POST["uid"]
]);
echo "1";

?>
