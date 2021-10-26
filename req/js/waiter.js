function update()
{
  $.ajax({
    url: "req/php/waiterUpdate.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    success: (data)=>{
      data = JSON.parse(data);
      $(".waiter_content-inside-orders").html(data.orders);
      $(".waiter_content-inside-tables").html(data.tables);
      $(".waiter_content-inside-item:has(.blue)").toggleClass("blue");
      $(".waiter_content-inside-item-cancel").on("click", (e)=>{
        cancelOrder(e.target.getAttribute("data-oid"));
      });
      $(".waiter_content-inside-item-served").on("click", (e)=>{
        servedOrder(e.target.getAttribute("data-oid"));
      });
      $(".waiter_content-inside-item-checkout").on("click", (e)=>{
        checkoutTable(e.target.getAttribute("data-rid"));
      });
    }
  });
}

function cancelOrder(oid)
{
  $.ajax({
    url: "req/php/cancelOrder.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      order_id: oid
    },
    success: update
  });
  $.notify("Order was canceled", "info");
}

function servedOrder(oid)
{
  $.ajax({
    url: "req/php/servedOrder.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      order_id: oid
    },
    success: update
  });
  $.notify("Order was served", "success");
}

function checkoutTable(rid)
{
  $.ajax({
    url: "req/php/checkoutTable.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      receipt_id: rid
    },
    success: update
  });
  $.notify("Table was checked out", "success");
}

function selectOrders()
{
  $(".waiter_content-inside-orders").css("left","0%");
  $(".waiter_content-inside-tables").css("left","110%");
  if($(".waiter_content-nav-item-orders").hasClass("current"))
  {
    return true;
  }
  $(".waiter_content-nav-item-orders").toggleClass("current");
  $(".waiter_content-nav-item-tables").toggleClass("current");
}

function selectTables()
{
  $(".waiter_content-inside-orders").css("left","-110%");
  $(".waiter_content-inside-tables").css("left","0%");
  if($(".waiter_content-nav-item-tables").hasClass("current"))
  {
    return true;
  }
  $(".waiter_content-nav-item-orders").toggleClass("current");
  $(".waiter_content-nav-item-tables").toggleClass("current");
}

$(".waiter_content-nav-item-orders").on("click", selectOrders);
$(".waiter_content-nav-item-tables").on("click", selectTables);

var allItems;
var it = 0;
var queried = false;
var itemss;
var format = "";
var lastOrder = "";
var availTables = "";
function formatNeworder(items)
{
  lastOrder = "select[name='new-order-" + it + "']";
  var html = (it+1) + ". <select name='new-order-" + it + "'>";
  it++;
  html += "<option>Choose order</option>";
//  if(!queried)
//  {
//
    for (its in items)
    {
      let item_id = items[its].item_id;
      let item_name = items[its].item_name;
      let item_price = items[its].item_price;
      html += "<option value='" + item_id + "'>" + item_name + " - " + item_price + "THB</option>";
      format += "<option value='" + item_id + "'>" + item_name + " - " + item_price + "THB</option>";
    }
//  }
//  else
//  {
//    html += format;
//  }
  html += "</select>";
  return html;
}

function neworderBind()
{
  if($(lastOrder).val() != "Choose order")
  {
    $("#new-order-form").append(formatNeworder(itemss));
    $(lastOrder).on("change", neworderBind);
  }
}

function neworderSubmit()
{
  var ordered = [];
  $("#new-order-form select:not(select[name='new-order-table'])").each((n,el)=>{
    ordered.push(parseInt(el.value));
  });
  ordered.pop();
  var receipt = parseInt($("#new-order-form select[name='new-order-table']").val());
  $.ajax({
    url: "req/php/neworder_submit.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      ordered: JSON.stringify(ordered),
      receipt: receipt,
      table: $("#new-order-form select[name='new-order-table'] option[value=" + receipt + "]").html()
    },
    success: (xhr) => {
      update();
      selectOrders();
    }
  });
}

function newtableSubmit()
{
  $.ajax({
    url: "req/php/newtable_submit.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      table: $("#new-table-form input[name='new-table']").val()
    },
    success: (xhr) => {
      update();
      selectTables();
    }
  });
}

$(".waiter_content-inside-add-order").on("click", ()=>{
  it = 0;
  var order = "";
  if(!queried)
  {
    $.ajax({
        url: "req/php/getAvailTables.php",
        async: false,
        method: "post",
        contentType: "application/x-www-form-urlencoded",
        success: (xhr) => {
          let tables = JSON.parse(xhr);
          availTables += "Table: <select name='new-order-table'>";
          for (t in tables)
          {
            availTables += "<option value='" + tables[t].receipt_id + "'>";
            availTables += tables[t].order_table;
            availTables += "</option>";
          }
          availTables += "</select><hr/>";

          let prepare = function() {
            $.ajax({
                url: "req/php/getItems.php",
                async: false,
                method: "post",
                contentType: "application/x-www-form-urlencoded",
                success: (xhr) => {
                  let items = JSON.parse(xhr);
                  itemss = items;
                  order += "<form id='new-order-form'><br/>";
                  order += availTables;
                  allItems = formatNeworder(itemss);
                  order += allItems;
                  order += "</form>";

                  modal.call(["New order", order], neworderSubmit, true, ()=>{
                    setTimeout(()=>{
                      $(lastOrder).on("change", neworderBind);
                    }, 1000);
                  });
                }
            });
          };
          prepare();
          queried = true;
        }
    });
  }
  else
  {
    $.ajax({
        url: "req/php/getAvailTables.php",
        async: false,
        method: "post",
        contentType: "application/x-www-form-urlencoded",
        success: (xhr) => {
          let tables = JSON.parse(xhr);
          availTables = "Table: <select name='new-order-table'>";
          for (t in tables)
          {
            availTables += "<option value='" + tables[t].receipt_id + "'>";
            availTables += tables[t].order_table;
            availTables += "</option>";
          }
          availTables += "</select><hr/>";

          order += "<form id='new-order-form'><br/>";
          order += availTables;
          order += formatNeworder(itemss);
          order += "</form>";

          modal.call(["New order", order], neworderSubmit, true, ()=>{
            setTimeout(()=>{
              $(lastOrder).on("change", neworderBind);
            }, 1000);
          });
        }
    });
  }
});

$(".waiter_content-inside-add-table").on("click", ()=>{
  let table = "<form id='new-table-form'>Table: <input type='text' name='new-table' maxlength='4'/></form>";
  modal.call(["New table", table], newtableSubmit, true);
});

const updateInterval = setInterval(update, 500);
$(".waiter_content-inside-item:has(.blue)").toggleClass("blue");
