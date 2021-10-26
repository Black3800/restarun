<?php

$dat = [];
$iv = bin2hex(openssl_random_pseudo_bytes(8));
$dat["name"] = openssl_encrypt($_POST["entname"], "AES-256-CBC", $_POST["pwd"], 0, $iv);
$dat["usr"] = openssl_encrypt($_POST["usr"], "AES-256-CBC", $_POST["pwd"], 0, $iv);
$dat["null"] = openssl_encrypt("0", "AES-256-CBC", $_POST["pwd"], 0, $iv);
$dat["start"] = openssl_encrypt(date("Y-m-d H:i:s"), "AES-256-CBC", $_POST["pwd"], 0, $iv);
$dat["pwd"] = hash("sha256", $_POST["pwd"]);

require_once "db.php";

$query = "INSERT INTO user VALUES (0, 0, :usr_auth, :pwd_auth, :iv_auth, '', :u_name_p, :u_name_s, :u_salary_p, :u_salary_s, :u_work_p, :u_work_s, :u_start_p, :u_start_s,  0)";
$userInsertStmt = $pdo->prepare($query);
$userInsertStmt->execute([
  "usr_auth" => $_POST["usr"],
  "pwd_auth" => $dat["pwd"],
  "iv_auth" => $iv,
  "u_name_p" => $dat["name"],
  "u_name_s" => $dat["name"],
  "u_salary_p" => $dat["null"],
  "u_salary_s" => $dat["null"],
  "u_work_p" => $dat["null"],
  "u_work_s" => $dat["null"],
  "u_start_p" => $dat["start"],
  "u_start_s" => $dat["start"]
]);
$uid = $pdo->lastInsertId();
$emptyJson = [];

$query = "INSERT INTO shop VALUES (0, :uid, :shopname, :w, :c)";
$shopInsertStmt = $pdo->prepare($query);
$shopInsertStmt->execute([
  "uid" => $uid,
  "shopname" => $_POST["shopname"],
  "w" => json_encode($emptyJson, JSON_FORCE_OBJECT),
  "c" => json_encode($emptyJson, JSON_FORCE_OBJECT)
]);
$shopid = $pdo->lastInsertId();

$query = "UPDATE user SET shop_id=:shopid WHERE uid=:uid";
$shopidUpdateStmt = $pdo->prepare($query);
$shopidUpdateStmt->execute([
  "shopid" => $shopid,
  "uid" => $uid
]);

echo "<b>Important: Your shop ID is " . $shopid . " (Please keep this for logging in)</b><br/>Click OK to go to the login page.";

?>
