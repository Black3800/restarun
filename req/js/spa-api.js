var spa = {};

spa.ajax = function(spa_url, spa_method, spa_data, spa_success) {

  var spa_ajaxCall_errorHandler;
  if(arguments[4])
  {
    spa_ajaxCall_errorHandler = arguments[4];
  }
  else
  {
    spa_ajaxCall_errorHandler = function(){
      console.log("errorHandler executed");
    };
  }

  var spa_ajaxCall = $.ajax({
    url: spa_url,
    method: spa_method,
    contentType: "application/x-www-form-urlencoded",
    data: {
      requestData: JSON.stringify(spa_data)
    },
    success: (spa_ajaxCall_xhr) => {
      var spa_ajaxCall_responseHandler = spa_success;
      spa_ajaxCall_responseHandler(spa_ajaxCall_xhr);
    },
    error: (spa_ajaxCall_xhr_err) => {
      spa_ajaxCall_errorHandler();
    }
  });
};

spa.loadScript = function (url, callback) {
    jQuery.ajax({
        url: url,
        dataType: 'script',
        success: callback,
        async: true
    });
};

spa.writePage = function(spa_writePage_pageContent) {
  if(typeof onkeypressHandler !== 'undefined')
  {
    window.removeEventListener("keypress", onkeypressHandler);
  }
  document.getElementById("content").innerHTML = spa_writePage_pageContent;
  $(".spa_js_import").each( (spa_js_import_n, spa_js_import_elem) => {
    spa.loadScript(spa_js_import_elem.getAttribute("data_src"), ()=>{});
  });
};

spa.getPage = function(spa_getPage_url, spa_getPage_params) {
  var spa_getPage_escapeHtml = function(spa_getPage_escapeHtml_text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return spa_getPage_escapeHtml_text.replace( /[&<>"']/g,
                                                function(m) {
                                                  return map[m];
                                                });
  };

  var spa_getPage_responseHandler = function(spa_getPage_ajaxCall_responseText) {
    spa.writePage(spa_getPage_ajaxCall_responseText);
  };

  var spa_getPage_errorHandler = function() {
    // error
  };

  var spa_getPage_ajaxCall = spa.ajax("req/php/getPage.php",
                                      "post",
                                      { requestedUrl: spa_getPage_url,
                                        requestedParams: spa_getPage_params },
                                      spa_getPage_responseHandler,
                                      spa_getPage_errorHandler);
};

spa.eventBind = function(spa_eventBind_element, spa_eventBind_url) {
  if(spa_eventBind_element.getAttribute("data_spa_eventBind"))
  {
    return false;
  }
  spa_eventBind_element.addEventListener("click", function(){
    spa.getPage(spa_eventBind_url, {});
  });
  spa_eventBind_element.setAttribute("data_spa_eventBind", "true");
  spa.getPage(spa_eventBind_url, {});

  return false;
};
