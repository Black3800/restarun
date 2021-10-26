<?php

error_reporting(E_WARNING);
session_start();
require_once "db.php";

$query = "SELECT * FROM `orders` INNER JOIN items ON orders.order_item=items.item_id WHERE (orders.shop_id=:shop_id AND orders.order_status!=2) ORDER BY orders.order_id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"]
]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ordersHtml = "";
$tablesHtml = "";

foreach($orders as $o)
{
  $oid = $o["order_id"];
  $table = $o["order_table"];
  $name = $o["item_name"];
  $price = $o["item_price"];
  $time = $o["item_time"];
  $ordersHtml .= "<div class='waiter_content-inside-item' data-oid='$oid'>
    <div class='waiter_content-inside-item-table'>$table</div>
    <div class='waiter_content-inside-item-name'>$name</div>
    <div class='waiter_content-inside-item-price'>$price</div>
    <div class='waiter_content-inside-item-time'>$time</div>
    <div class='waiter_content-inside-item-cancel' data-oid='$oid'>Cancel</div>";
  if($o["order_status"] == 0)
  {
    $ordersHtml .= "<div class='waiter_content-inside-item-status' data-oid='$oid'>Cooking...</div>";
  }
  else if($o["order_status"] == 1)
  {
    $ordersHtml .= "<div class='waiter_content-inside-item-status blue' data-oid='$oid'>Ready</div>";
    $ordersHtml .= "<div class='waiter_content-inside-item-served' data-oid='$oid'>Served</div>";
  }
  $ordersHtml .= "</div>";
}

$query = "SELECT * FROM receipts WHERE shop_id=:shop_id AND receipt_status=0";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"]
]);
$receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($receipts as $bill)
{
  $rid = $bill["receipt_id"];
  $table = $bill["order_table"];
  $total = $bill["total"];
  $tablesHtml .= "<div class='waiter_content-inside-item'>
    <div class='waiter_content-inside-item-table'>$table</div>
    <div class='waiter_content-inside-item-price'>$total</div>
    <div class='waiter_content-inside-item-checkout' data-rid='$rid'>Check out</div>
  </div>";
}

echo json_encode([
  "orders" => $ordersHtml,
  "tables" => $tablesHtml,
]);

?>
