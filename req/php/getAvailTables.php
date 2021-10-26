<?php

session_start();
require_once "db.php";

$query = "SELECT * FROM receipts WHERE shop_id=:shop_id AND receipt_status=0";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"]
]);
$fetched = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($fetched);

?>
