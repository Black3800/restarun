<?php

session_start();
require_once "db.php";

$query = "SELECT * FROM items WHERE shop_id=:shop_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"]
]);
$fetched = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($fetched);

?>
