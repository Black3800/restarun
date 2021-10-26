<?php

session_start();
require_once "db.php";
$query = "INSERT INTO receipts VALUES (0, :shop_id, :dt, '[]', 0, :table, 0, 0, :uid)";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"],
  "dt" => date("c"),
  "table" => $_POST["table"],
  "uid" => $_SESSION["user_info"]["uid"]
]);

?>
