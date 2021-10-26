<?php

require_once "db.php";

$query = "SELECT items.item_price,items.item_id,orders.order_table FROM items INNER JOIN orders ON items.item_id=orders.order_item WHERE orders.order_id=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_POST["order_id"]]);
$fetch = $stmt->fetch(PDO::FETCH_ASSOC);
$price = $fetch["item_price"];
$item_id = $fetch["item_id"];
$table = $fetch["order_table"];

$query = "DELETE FROM orders WHERE order_id=:oid";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "oid" => $_POST["order_id"]
]);

$query = "SELECT order_item_all,total FROM receipts WHERE order_table=:table";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "table" => $table
]);
$fetch = $stmt->fetch(PDO::FETCH_ASSOC);
$item_all = json_decode($fetch["order_item_all"]);
$total = $fetch["total"];
$total -= $price;
$item_all = array_diff($item_all, [$item_id]);

$query = "UPDATE receipts SET total=:total, order_item_all=:item WHERE order_table=:table";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "total" => $total,
  "item" => json_encode($item_all),
  "table" => $table
]);

?>
