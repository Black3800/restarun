<?php

require_once "db.php";
$query = "UPDATE orders SET order_status=2 WHERE order_id=:oid";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "oid" => $_POST["order_id"]
]);

?>
