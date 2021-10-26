<?php

error_reporting(4);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rest-a-run</title>
    <!-- Required -->
    <link rel="stylesheet" href="req/css/global.css" />
    <link rel="stylesheet" href="req/css/index.css" />
    <link rel="stylesheet" href="req/css/register.css" />
    <link rel="stylesheet" href="req/css/manager.css" />
    <link rel="stylesheet" href="req/css/waiter.css" />
    <link rel="stylesheet" href="req/css/cook.css" />
    <link rel="stylesheet" href="req/css/modal.css" />
    <link rel="stylesheet" href="lib/datatables/datatables.min.css" />
    <script src="req/js/global.js"></script>
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="lib/notifyjs/notify.min.js"></script>
    <script src="lib/datatables/datatables.min.js"></script>
    <script src="req/js/modal.js"></script>
    <script src="req/js/spa-api.js"></script>
    <!-- End required-->
  </head>
  <body>
    <div id="content">
      <?php

      session_start();
      $loggedout = '<h1 class="index_content-heading1">
        Login
      </h1>
      <form id="index_login-form">
        <div class="index_login">
          <input type="number" name="shopid" id="index_login-input-shopid" placeholder="Shop ID" class="index_login-input index_login-input-shopid" />
          <input type="text" name="usr" id="index_login-input-usr" placeholder="Username" class="index_login-input index_login-input-usr" />
          <input type="password" name="pwd" id="index_login-input-pwd" placeholder="Password" class="index_login-input index_login-input-pwd" />
          <div class="index_login-submit" onclick="login.submit();">Login</div>
          <div class="index_register" onclick="spa.eventBind(this, ' . "'register.php');" . '">
            <span id="index_register-text">New shop?</span>
          </div>
        </div>
      </form>
      <script class="spa_js_import" data_src="req/js/login.js"></script>';
      $loggedin = 'Logged in';
      if(empty($_SESSION["loggedin"]))
      {
        echo $loggedout;
      }
      else
      {
        echo "<script>let loggedin = true;</script>";
        switch($_SESSION["user_info"]["u_type"])
        {
          case 0:
            echo "<script class='spa_js_import' data_src='req/js/getManager.js' src='req/js/getManager.js'></script>";
            break;
          case 1:
            echo "<script class='spa_js_import' data_src='req/js/getWaiter.js' src='req/js/getWaiter.js'></script>";
            break;
          case 2:
            echo "<script class='spa_js_import' data_src='req/js/getCook.js' src='req/js/getCook.js'></script>";
            break;
          case 3:
            echo "<script class='spa_js_import' data_src='req/js/getWaiter.js' src='req/js/getWaiter.js'></script>";
            break;
          default:
            echo "<script>console.warn('error on get page')</script>";
        }
      }

      ?>

    </div>
    <div id="modal" class="modal">
      <div class="modal-dialog">
        <div class="modal-dialog-title">
          Title
        </div>
        <div class="modal-dialog-content">
          Content
        </div>
        <div class="modal-dialog-btn-group">
          <div class="modal-dialog-btn modal-dialog-btn-ok" onclick="modal.close(true)">OK</div>
          <div class="modal-dialog-btn modal-dialog-btn-cancel" onclick="modal.close(false)">Cancel</div>
        </div>
      </div>
    </div>
    <script>
    spa.loadScript("req/js/login.js", ()=>{
      if(typeof loggedin !== 'undefined')
      {
        window.removeEventListener("keypress", onkeypressHandler);
      }
    });
    </script>
  </body>
</html>
