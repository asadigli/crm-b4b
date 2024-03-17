'use strict';
const headers = {headerkey: $("body").data("acceskey")};
$("body").removeAttr("data-acceskey");

$('.custom-select').select2({
  minimumResultsForSearch: 20,
  language: {
    noResults: function() {
    return lang("no_results");
    },
  }
});


let oldXHR = window.XMLHttpRequest;
function newXHR() {
    var realXHR = new oldXHR();
    realXHR.addEventListener("readystatechange", function() {
        if(realXHR.readyState == 4){
          // console.log("ok");
          try {
            // console.log(realXHR.response);
            JSON.parse(realXHR.response);
            if (typeof JSON.parse(realXHR.response).code !== "undefined" && JSON.parse(realXHR.response).code === 401) {
              // location.reload();
            }
          } catch (e) {
            // console.error("INVALID JSON");
          }

        }
    }, false);
    return realXHR;
}
window.XMLHttpRequest = newXHR;
