var manager = {};

manager.navScrollTo = function(n) {
  switch(n)
  {
    case 0:
      window.scrollTo({
        top: $("#manager_content-stats").offset().top - 100,
        behavior: "smooth"
      });
      break;
    case 1:
      window.scrollTo({
        top: $("#manager_content-manemp").offset().top - 100,
        behavior: "smooth"
      });
      break;
    case 2:
      window.scrollTo({
        top: $("#manager_content-manitem").offset().top - 100,
        behavior: "smooth"
      });
      break;
  }
};

manager.resign = function(uid) {
  let text = ["Are you sure?", "Resigning " + $(".manager_employee-table-type[data-uid='"+uid+"']").html() + " " + $(".manager_employee-table-name[data-uid='"+uid+"']").html() + "?"];
  modal.call(text, ()=>{
    $.ajax({
      url: "req/php/resign.php",
      method: "post",
      contentType: "application/x-www-form-urlencoded",
      data: {
        uid: uid
      },
      success: (xhr) => {
        spa.getPage("manager.php", {});
      }
    });
  }, true);
};

manager.remove = function(itemid) {
  let text = ["Are you sure?", "Removing item " + $(".manager_item-table-name[data-itemid='"+itemid+"']").html() + "?"];
  modal.call(text, ()=>{
    $.ajax({
      url: "req/php/remove.php",
      method: "post",
      contentType: "application/x-www-form-urlencoded",
      data: {
        item_id: itemid
      },
      success: (xhr) => {
        spa.getPage("manager.php", {});
      }
    });
  }, true);
};

manager.add = function() {
  var html = "<form><br/>Name: <input type='text' id='new-name'/><br/>Username: <input type='text' id='new-usr'/><br/>Password: <input type='password' id='new-pwd'/><br/>Salary: <input type='number' id='new-salary'/><br/>Employ type: <select id='new-type'><option value='1'>Waiter</option><option value='2'>Cook</option><option value='3'>Part-time</option></select><br/>Manager's Password: <input type='password' id='new-manpwd'/></form>";
  modal.call(["Add new employee", html], ()=>{
    $.ajax({
      url: 'req/php/add.php',
      method: 'post',
      contentType: 'application/x-www-form-urlencoded',
      data: {
        name: $("#new-name").val(),
        usr: $("#new-usr").val(),
        pwd: $("#new-pwd").val(),
        salary: $("#new-salary").val(),
        type: $("#new-type").val(),
        manpwd: $("#new-manpwd").val()
      },
      success: (a)=>{
        spa.getPage("manager.php", {});
      }
    });
  }, true);
};

manager.additem = function(){
  var html = "<form><br/>Item name: <input type='text' id='new-name'/><br/>Item price: <input type='number' id='new-price'/><br/>Cooking time: <input type='number' id='new-time'/><br/></form>";
  modal.call(["Add new item", html], ()=>{
    $.ajax({
      url: 'req/php/additem.php',
      method: 'post',
      contentType: 'application/x-www-form-urlencoded',
      data: {
        item_name: $("#new-name").val(),
        item_price: $("#new-price").val(),
        item_time: $("#new-time").val()
      },
      success: (a)=>{
        spa.getPage("manager.php", {});
      }
    });
  }, true);
}

$(document).ready(function() {
    $('#employee-table').DataTable();
    $('.manager_resign-btn').each((n,elem)=>{
      let uid = elem.getAttribute("data-uid");
      elem.addEventListener("click", ()=>{
        manager.resign(uid);
      });
    });
    $("#manager_employee-add-btn").on("click", ()=>{
      manager.add();
    });

    $('#item-table').DataTable();
    $('.manager_remove-btn').each((n,elem)=>{
      let itemid = elem.getAttribute("data-itemid");
      elem.addEventListener("click", ()=>{
        manager.remove(itemid);
      });
    });
    $("#manager_item-add-btn").on("click", ()=>{
      manager.additem();
    });
});
