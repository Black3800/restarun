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

foreach($orders as $o)
{
  $oid = $o["order_id"];
  $table = $o["order_table"];
  $name = $o["item_name"];
  $price = $o["item_price"];
  $time = $o["item_time"];
  $ordersHtml .= "<div class='cook_content-inside-item' data-oid='$oid'>
    <div class='cook_content-inside-item-table'>$table</div>
    <div class='cook_content-inside-item-name'>$name</div>
    <div class='cook_content-inside-item-price'>$price</div>
    <div class='cook_content-inside-item-time'>$time</div>";
  if($o["order_status"] == 0)
  {
    $ordersHtml .= "<div class='cook_content-inside-item-done' data-oid='$oid'>Done</div>";
  }
  else if($o["order_status"] == 1)
  {
    $ordersHtml .= "<div class='cook_content-inside-item-status blue' data-oid='$oid'>Serving...</div>";
  }

  $ordersHtml .= "</div>";
}
echo json_encode([
  "orders" => $ordersHtml
]);

?>
