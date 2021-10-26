<?php

error_reporting(E_WARNING);
session_start();
require_once "db.php";

?>
<div class="global_logout" onclick="window.location.href='logout.php'" style="top: 10px;"><?php
echo $_SESSION["user_info"]["u_name_private"];
?> - Logout</div>
<div class="waiter_heading">Orders - <?php session_start(); echo $_SESSION["shop_name"]; ?></div>
<div class="waiter_content">
  <div class="waiter_content-nav">
    <div class="waiter_content-nav-item waiter_content-nav-item-orders">
      Orders
    </div>
    <div class="waiter_content-nav-item waiter_content-nav-item-tables current">
      Tables
    </div>
  </div>
  <div class="waiter_content-inside-add">
    <div class="waiter_content-inside-add-order">
      New order
    </div>
    <div class="waiter_content-inside-add-table">
      New table
    </div>
  </div>
  <div class="waiter_content-inside">
    <div class="waiter_content-inside-orders">
      <!--div class="waiter_content-inside-item blue">
        <div class="waiter_content-inside-item-table">12A</div>
        <div class="waiter_content-inside-item-name">Ubiquitous French fries</div>
        <div class="waiter_content-inside-item-price">12.39</div>
        <div class="waiter_content-inside-item-time">5</div>
        <div class="waiter_content-inside-item-badge blue">New</div>
        <div class="waiter_content-inside-item-cancel">Cancel</div>
        <div class="waiter_content-inside-item-served">Served</div>
      </div-->
      <?php

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
      echo $ordersHtml;

      ?>
    </div>
    <div class="waiter_content-inside-tables">
      <?php

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
      echo $tablesHtml;

      ?>
    </div>
  </div>
</div>
<script class="spa_js_import" data_src="req/js/waiter.js"></script>
