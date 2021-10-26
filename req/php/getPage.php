<?php

$requestedData = json_decode($_POST["requestData"],true);
$requestedUrl = $requestedData["requestedUrl"];
$requestedParams = $requestedData["requestedParams"];
if(!empty($requestedUrl))
{
  if(!file_exists("re/" . $requestedUrl))
  {
    header("HTTP/1.1 402 Bad Request");
    echo "Specified URL was not found on server";
    exit;
  }
  ob_start();
  require_once("re/". $requestedUrl);
  $pageContent = ob_get_clean();
  ob_end_flush();
  echo $pageContent;
}
else
{
  header("HTTP/1.1 402 Bad Request");
  echo "Missing URL";
  exit;
}

?>
