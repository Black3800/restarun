<?php

require_once "db.php";

$loginStmt = $pdo->prepare("SELECT * FROM user WHERE usr_auth=:uauth AND shop_id=:shopid");
$loginStmt->execute([
  "uauth" => $_POST["usr"],
  "shopid" => $_POST["shopid"]
]);
$fetched = $loginStmt->fetch(PDO::FETCH_ASSOC);

if($fetched["pwd_auth"] == hash("sha256", $_POST["pwd"]))
{
  echo "1";
  $data = [
    "uid" => $fetched["uid"],
    "shop_id" => $fetched["shop_id"],
    "usr_auth" => $_POST["usr"],
    "u_img" => $fetched["u_img"],
    "u_name_private" => openssl_decrypt($fetched["u_name_private"], "AES-256-CBC", $_POST["pwd"], 0, $fetched["iv_auth"]),
    "u_salary_private" => openssl_decrypt($fetched["u_salary_private"], "AES-256-CBC", $_POST["pwd"], 0, $fetched["iv_auth"]),
    "u_work_private" => openssl_decrypt($fetched["u_work_private"], "AES-256-CBC", $_POST["pwd"], 0, $fetched["iv_auth"]),
    "u_start_private" => openssl_decrypt($fetched["u_start_private"], "AES-256-CBC", $_POST["pwd"], 0, $fetched["iv_auth"]),
    "u_type" => $fetched["u_type"]
  ];
  session_start();
  $_SESSION["user_info"] = $data;
  $_SESSION["loggedin"] = true;

  $query = "SELECT shop_name FROM shop WHERE shop_id=:shop_id";
  $getShopnameStmt = $pdo->prepare($query);
  $getShopnameStmt->execute([
    "shop_id" => $fetched["shop_id"]
  ]);
  $shopname = $getShopnameStmt->fetch(PDO::FETCH_ASSOC)["shop_name"];
  $_SESSION["shop_name"] = $shopname;

  if($data["u_type"] == 0)
  {
    $_SESSION["pwd"] = $_POST["pwd"];
    $_SESSION["iv"] = $fetched["iv_auth"];
  }
}
else
{
  echo "0";
}

?>
