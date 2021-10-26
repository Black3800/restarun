var login = {};

login.submit = function(){
  $.ajax({
    url: "req/php/login_submit.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      shopid: $("#index_login-input-shopid").val(),
      usr: $("#index_login-input-usr").val(),
      pwd: $("#index_login-input-pwd").val()
    },
    success: function(login_xhr) {
      if(login_xhr == "1")
      {
        $.notify("Login successful", "success");
        window.location.href = "index.php";
      }
      else
      {
        $.notify("Login failed", "error");
        $.notify("Please check your shop ID, username and password", "info");
      }
    }
  });
};

var onkeypressHandler = (e) => {
  if(e.keyCode==13)
  {
    login.submit();
  }
};
window.addEventListener("keypress", onkeypressHandler);
