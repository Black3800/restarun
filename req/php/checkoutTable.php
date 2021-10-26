<?php

require_once "db.php";
$query = "UPDATE receipts SET receipt_status=1 WHERE receipt_id=:rid";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "rid" => $_POST["receipt_id"]
]);

?>
