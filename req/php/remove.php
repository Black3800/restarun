<?php

require_once "db.php";
$query = "DELETE FROM items WHERE item_id=:itemid";
$resignStmt = $pdo->prepare($query);
$resignStmt->execute([
  "itemid" => $_POST["item_id"]
]);
echo "1";

?>
