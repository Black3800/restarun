function update()
{
  $.ajax({
    url: "req/php/cookUpdate.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    success: (data)=>{
      data = JSON.parse(data);
      $(".cook_content-inside-orders").html(data.orders);
      $(".cook_content-inside-item:has(.blue)").toggleClass("blue");
      $(".cook_content-inside-item-done").on("click", (e)=>{
        orderDone(e.target.getAttribute("data-oid"));
      });
    }
  });
}

function orderDone(oid)
{
  $.ajax({
    url: "req/php/orderDone.php",
    method: "post",
    contentType: "application/x-www-form-urlencoded",
    data: {
      order_id: oid
    },
    success: update
  });
  $.notify("Waiter was notified", "success");
}

const updateInterval = setInterval(update, 500);
$(".cook_content-inside-item:has(.blue)").toggleClass("blue");
