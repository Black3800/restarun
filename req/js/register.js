var register = {};

register.validate = function() {
  var status = 1;
  if(document.getElementById("register_form-input-shopname").value == "")
  {
    $.notify("Please enter shop name", "warn");
    status = 0;
  }
  if(document.getElementById("register_form-input-entname").value == "")
  {
    $.notify("Please enter your name", "warn");
    status = 0;
  }
  if(document.getElementById("register_form-input-usr").value == "")
  {
    $.notify("Please enter username", "warn");
    status = 0;
  }
  if(document.getElementById("register_form-input-pwd").value == "")
  {
    $.notify("Please enter password", "warn");
    status = 0;
  }
  if(document.getElementById("register_form-input-pwdcheck").value !==
     document.getElementById("register_form-input-pwd").value)
  {
    $.notify("Password do not match", "error");
    status = 0;
  }
  return status === 0 ? false : true;
};

register.submit = function() {
  if(register.validate())
  {
    $.ajax({
      url: "req/php/register_submit.php",
      method: "post",
      contentType: "application/x-www-form-urlencoded",
      data: {
        shopname: document.getElementById("register_form-input-shopname").value,
        entname: document.getElementById("register_form-input-entname").value,
        usr: document.getElementById("register_form-input-usr").value,
        pwd: document.getElementById("register_form-input-pwd").value
      },
      success: (register_submit_xhr) => {
        modal.call(["Registration successful", register_submit_xhr], ()=>{
          window.location.href="index.php";
        });
      }
    });
  }
};

var onkeypressHandler = (e) => {
  if(e.keyCode==13)
  {
    register.submit();
  }
};
window.addEventListener("keypress", onkeypressHandler);
