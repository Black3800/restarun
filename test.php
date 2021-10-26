<?php

require "req/php/db.php";
$query = "SELECT * FROM user WHERE usr_auth='anak'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$fetched = $stmt->fetch(PDO::FETCH_ASSOC);

$income = [
  "2018" => 15063.22
];

echo openssl_encrypt(json_encode($income), "AES-256-CBC", "1234", 0, $fetched["iv_auth"]);

?>
