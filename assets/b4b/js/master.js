"use strict";

$('.custom-select').select2({
  minimumResultsForSearch: 20,
  language: {
    noResults: function() {
    return lang("no_results");
    },
  }
});

$(`[type="date"]`).attr("max","2100-01-01").attr("min","1900-01-01");

const headers = {headerkey: $("body").data("acceskey")};
$("body").removeAttr("data-acceskey");

let oldXHR = window.XMLHttpRequest;
function newXHR() {
    var realXHR = new oldXHR();
    realXHR.addEventListener("readystatechange", function() {
        if(realXHR.readyState == 4){
          try {
            JSON.parse(realXHR.response);
            if (typeof JSON.parse(realXHR.response).code !== "undefined" && JSON.parse(realXHR.response).code === 401) {
              location.reload();
            }

            if (typeof JSON.parse(realXHR.response).code !== "undefined" && JSON.parse(realXHR.response).code === 423) {
              window.location = "/auth/logout";
            }

          } catch (e) {
            // console.error("INVALID JSON");
          }

        }
    }, false);
    return realXHR;
}
window.XMLHttpRequest = newXHR;
// const checkStatus = () => {
//   $.get({
//       url: `${location.origin}/assets/updates/${$(`body`).data("token")}.status_check.json?`,// v=` + (Math.random() + '').replace(".",""),
//       success: function(d) {
//         try {
//             d = JSON.parse(d);
//             if (typeof d.status === "undefined" || d.status === 302) {
//               $.post({
//                 url: `/check-entry-updates`,
//                 headers,
//                 success: function(d) {
//                   if (d.code === 401) {
//                     location.reload();
//                   }
//                 },
//               });
//             }
//         } catch (e) {
//         }
//       },
//       complete: function() {
//         setInterval(() => {
//           checkStatus();
//         },1000*5);
//       }
//   });
// }
// checkStatus();
// setInterval(() => {
//   checkStatus();
// },1000*2);
// console.log(UrlExists(`${location.origin}/assets1/qWVBMDekn8rhrFHiFnRKfIht.status_check.json`));;
// $.get(`/assets1/qWVBMDekn8rhrFHiFnRKfIht.status_check.json`)
// .done(function() {
// }).fail(function() {
// })


const onlines = () => {
  $.post({
    url: `/online`,
    headers,
    data: {url_path : location.pathname},
    success: function(d) {
      if (d.code === 401) {
        location.reload();
      }
    },
    error: function(e){
      console.error(e)
    },
    complete: function() {

    }
  });
}
onlines();
setInterval(() => {
  onlines();
},1000*60);


// $(function() {
//   const onlRequest = () => {
//     $.post({
//       url: `/onlines/add`,
//       headers,
//       success: function(d){
//       },
//       error: function(d){
//         console.error(d);
//       }
//     });
//   }
//   onlRequest();
//   setInterval(onlRequest,60000);
//
//   const notificationComponent = (d,i) => {
//     return `<div class="dropdown-divider"></div>
//               <a data-id="${d.id}" data-role="order-not-link" class="dropdown-item preview-item" target="_blank" href="/orders/${d.description.order_code ?? ""}/details" >
//                 <div class="preview-thumbnail">
//                   <div class="preview-icon bg-${d.description.order_status === "pending" ? "info" :
//                                             (d.description.order_status === "confirmed" ? "confirmed" :
//                                             (d.description.order_status === "shipped" ? "success" :
//                                             (d.description.order_status === "canceled" ? "danger" :
//                                             (d.description.order_status === "on_the_way" ? "byorder" :
//                                             (d.description.order_status === "accepted" ? "returned" :
//                                             (d.description.order_status === "partially_shipped" ? "warning" : "primary"))))))}">
//                     <i class="mdi mdi-information mx-0"></i>
//                   </div>
//                 </div>
//                 <div class="preview-item-content">
//                   <h6 class="preview-subject font-weight-medium">${d.title ?? ""}</h6>
//                   <p class="font-weight-light small-text mb-0">
//                     ${global_words["your_order_" + (d.description.order_status ?? "")]}
//                   </p>
//                 </div>
//               </a>`;
//   }
//
//   const notificationHeadComponent = (count) => {
//     return `<a target="_blank" href="/notifications" class="dropdown-item">
//               <p class="mb-0 font-weight-normal float-left">${count + " " + global_words["new_notifications"]}
//               </p>
//               <span class="badge badge-pill badge-warning float-right">${global_words["view_all"]}</span>
//             </a>`;
//   }
//   const headerNotifications = () => {
//     let html = "";
//     $.get({
//       url: `/notifications/header-list`,
//       headers,
//       success: function(d){
//         if(d.code === 200) {
//           let content_data = d.data && d.data.list ? d.data.list : [],
//           count = d.data && d.data.count ? d.data.count : 0;
//
//           if(content_data.length) {
//             $(`[data-role="header-notifications"]`).find(`[data-role="indicator"]`).addClass("count-indicator");
//             $(`[data-role="not-header"]`).html(notificationHeadComponent(count));
//
//             html += content_data.map((v,i) => notificationComponent(v,i)).join("");
//             $(`[data-role="not-body"]`).html(html);
//           }
//         }
//       },
//       error: function(d){
//         console.error(d);
//       },
//       complete: function(d) {
//
//       }
//     });
//   }
//
//   headerNotifications();
//   setInterval(headerNotifications,60000 * 3);
//
//   $(document).on("click", `[data-role="order-not-link"]`, function() {
//     let id = $(this).data("id");
//
//     $.ajax({
//       url: `/notifications/${id}/edit-seen`,
//       method: "PUT",
//       headers,
//       success: function(d) {
//
//       },
//       error: function(d) {
//         console.log(d);
//       },
//       complete: function(d) {
//
//       }
//     });
//   });
// });
//
//
const cartCount = () => {
    const current_account_currency = $(`[data-role="current-account-currency"]`).data("currency");
  $.get({
    url: `/cart/count-live`,
    headers,
    success: function(d){
      if(d.code === 200) {
        if(!(+d.data.count)) {
          if($(document).find(`[data-role="nav-cart-count"]`).length) {
            $(document).find(`[data-role="nav-cart-count"]`).remove();
          }
          if($(document).find(`[data-role="nav-cart-amount"]`).length) {
            $(document).find(`[data-role="nav-cart-amount"]`).text(number_format(0,2,",",".",0) + " EUR");
          }
          return;
        }
        if($(document).find(`[data-role="nav-cart-count"]`).length || $(document).find(`[data-role="nav-cart-amount"]`).length) {
          if(!$(document).find(`[data-role="nav-cart-count"]`).length) {
            $(`[data-role="nav-cart-item"]`).find("div").append(`
              <span data-role="nav-cart-count" class="text-white badge badge-secondary bg-danger ml-1">${d.data.count}</span>`
            );
          }

          $(document).find(`[data-role="nav-cart-count"]`).html(d.data.count);
          $(document).find(`[data-role="nav-cart-amount"]`).html(number_format(d.data.sale_price,2,",",".",0) + " " + current_account_currency);
          return;
        }
        $(`[data-role="nav-cart-item"]`).append(`
          <div class="d-flex" >
          <span data-role="nav-cart-amount" class="count">${number_format(d.data.sale_price,2,",",".",0)} EUR</span>
          <span data-role="nav-cart-count" class="text-white badge badge-secondary bg-danger ml-1">${d.data.count}</span>
          </div>
          `);
      }
    },
    error: function(d){
      console.error(d);
    }
  });
}
cartCount();


$(document).on("change",`[data-role="change-entry-customer"]`,function(){
  ModalLoader.start(lang("Loading") + "...");
  $.post({
    url: `/auth/switch-customer`,
    headers,
    data: {
      customer_id: $(this).val()
    },
    success: function(d) {
      if (d.code === 200) {
        location.reload();
      }
    },
    error: function(d){
      console.error(d);
    },
    complete: function() {
      ModalLoader.end();
    }
  })
});
