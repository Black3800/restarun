<?php

session_start();
require_once "db.php";

$shop_id = $_SESSION["user_info"]["shop_id"];
$uid = $_SESSION["user_info"]["uid"];
$order_table = $_POST["table"];
$ordered = json_decode($_POST["ordered"]);
$receipt = intval($_POST["receipt"]);

$query = "SELECT order_item_all,total FROM receipts WHERE receipt_id=:receipt_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "receipt_id" => $receipt
]);
$fetched = $stmt->fetch(PDO::FETCH_ASSOC);
$order_item_all = json_decode($fetched["order_item_all"]);
$total = floatval($fetched["total"]);

$itempriceQuery = "SELECT item_id,item_price FROM items WHERE shop_id=?";
$itempriceStmt = $pdo->prepare($itempriceQuery);
$itempriceStmt->execute([$shop_id]);
$itemprice = $itempriceStmt->fetchAll(PDO::FETCH_ASSOC);
$item_prices = [];

foreach ($itemprice as $ip)
{
  $item_prices[$ip["item_id"]] = floatval($ip["item_price"]);
}

$query = "INSERT INTO orders VALUES ";
$params = [];
foreach($ordered as $order)
{
  $order_item = $order;
  $query .= "(0, ?, ?, 0, ?, ?, ?, 0) , ";
  $total += $item_prices[$order_item];
  array_push($params, intval($shop_id), intval($uid), $order_item, date("c"), $order_table);
  array_push($order_item_all, $order_item);
}
$query = substr($query, 0, strlen($query) - 2);

echo $query;

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$query = "UPDATE receipts SET order_item_all=:order_all,order_item_all_count=:item_count,total=:total WHERE receipt_id=:receipt_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "order_all" => json_encode($order_item_all),
  "item_count" => count($order_item_all),
  "total" => $total,
  "receipt_id" => $receipt
]);

?>
