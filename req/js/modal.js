var modalClose;
const modal = {
  call: function(text, callback) {
    if(arguments[2]) // confirm
    {
      $(".modal-dialog-btn-cancel").css("display", "inline-block");
    }
    else
    {
      $(".modal-dialog-btn-cancel").css("display", "none");
    }
    if(arguments[3])
    {
      var prepareFn = arguments[3];
      prepareFn();
    }
    $(".modal-dialog-title").html(text[0]);
    $(".modal-dialog-content").html(text[1]);
    $(".modal").css("display", "block");
    setTimeout( ()=>{
      $(".modal").css("opacity", "1");
      $(".modal-dialog").css("top", "20%");
    },100);
    modalClose = callback;
  },
  close: function(ok){
    $(".modal").css("opacity", "0");
    $(".modal-dialog").css("top", "22%");
    setTimeout(()=>{
      $(".modal")[0].style.display = "none";
      if(ok)
      {
        modalClose();
      }
    },1000);
  }
}
