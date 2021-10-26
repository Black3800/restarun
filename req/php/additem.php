<?php

session_start();
require_once "db.php";

$query = "INSERT INTO items VALUES (0, :shop_id, :name, :price, :timeused)";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"],
  "name" => $_POST["item_name"],
  "price" => $_POST["item_price"],
  "timeused" => $_POST["item_time"]
]);

?>
