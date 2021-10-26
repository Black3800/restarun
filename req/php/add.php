<?php

session_start();
require_once "db.php";

$iv = bin2hex(openssl_random_pseudo_bytes(8));
$query = "INSERT INTO user VALUES (0, :shop_id, :usr_auth, :pwd_auth, :iv_auth, '', :u_name_p, :u_name_s, :u_salary_p, :u_salary_s, :u_work_p, :u_work_s, :u_start_p, :u_start_s, :u_type)";
$stmt = $pdo->prepare($query);
$stmt->execute([
  "shop_id" => $_SESSION["user_info"]["shop_id"],
  "usr_auth" => $_POST["usr"],
  "pwd_auth" => hash("sha256", $_POST["pwd"]),
  "iv_auth" => $iv,
  "u_name_p" => openssl_encrypt($_POST["name"], "AES-256-CBC", $_POST["pwd"], 0, $iv),
  "u_name_s" => openssl_encrypt($_POST["name"], "AES-256-CBC", $_POST["manpwd"], 0, $_SESSION["iv"]),
  "u_salary_p" => openssl_encrypt($_POST["salary"], "AES-256-CBC", $_POST["pwd"], 0, $iv),
  "u_salary_s" => openssl_encrypt($_POST["salary"], "AES-256-CBC", $_POST["manpwd"], 0, $_SESSION["iv"]),
  "u_work_p" => openssl_encrypt("0", "AES-256-CBC", $_POST["pwd"], 0, $iv),
  "u_work_s" => openssl_encrypt("0", "AES-256-CBC", $_POST["manpwd"], 0, $_SESSION["iv"]),
  "u_start_p" => openssl_encrypt(date("Y-m-d H:i:s"), "AES-256-CBC", $_POST["pwd"], 0, $iv),
  "u_start_s" => openssl_encrypt(date("Y:m:d H:i:s"), "AES-256-CBC", $_POST["manpwd"], 0, $_SESSION["iv"]),
  "u_type" => $_POST["type"]
]);

?>
