"use-strict";
$(function(){
  const current_account_currency = $(`[data-role="current-account-currency"]`).data("currency");

  const getParams = () => {
    start_date = $(`[name="start_date"]`).val();
    end_date = $(`[name="end_date"]`).val();
    brand_code = $(`[name="brand_code"]`).val();
    brand = $(`[name="brand"]`).val();
    oem_code = $(`[name="oem_code"]`).val();
  }

  const trComponent = (d,i,offset) => {
    return `<tr data-id="${d.remote_id}">
              <th>${i + offset}</th>
              <td>${d.operation_date ? date_format(d.operation_date,"d-m-y") : ""}</td>
              <td>${d.invoice_code ? (
                +d.is_invoice ?
                `<a class="link" target="_blank" rel="nofollow noreferer" href="/account/${d.invoice_code}/details" >${d.invoice_code}</a>`
                : d.invoice_code) : ""}</td>
              <td>${d.type || ""}</td>
              <td>${d.payment_type || ""}</td>
              <td>${d.warehouse || ""}</td>
            ${current_account_currency !== "AZN" ? `<td class="text-end">${d.currency_rate ? number_format(d.currency_rate,2,".",",") : ""}</td>` : ""}
              <td class="text-end text-danger">${d.entry_amount ? number_format(d.entry_amount,1,".",",") : ""}</td>
              <td class="text-end text-success">${d.exit_amount ? number_format(d.exit_amount,1,".",",") : ""}</td>
              <td class="text-end">${d.left_amount ? number_format(d.left_amount,1,".",",") : ""}</td>
            </tr>`;
  }

  let offset = 0,
      limit = 100,
			loading = false,
			loading_completed = false,
			total_shown_product_count = 0,
      interval = null,
      is_excel_export = "0";

  const getAccount = (data,loadmore = false) => {
    let html = "",
        count = 0,
        start_time = new Date().getTime();

    filter_url([
      {start_date: (start_date || "")},
      {end_date: (end_date || "")},
      {brand_code: (brand_code || "")},
      {brand: (brand || "")},
      {oem_code: (oem_code || "")},
    ]);


    if(!loadmore) {
      ModalLoader.start(lang("Loading"));
    }

    if (!loadmore && is_excel_export === "0") {
      $(`[data-role="content-result-count"]`).html("0");
      $(`[data-role="content-result-time"]`).html("0");

      total_shown_product_count = 0;
      loading_completed = false;
      offset = 0;

      clearInterval(interval);
      interval = setInterval(function () {
        $(`[data-role="content-result-time"]`).html(
          (new Date().getTime() - start_time) / 1000
        );
      }, 100);
    }



    data["offset"] = offset;
    $.get({
      url: `/account/live`,
      headers,
      data,
      success: function (d) {

        if(d.code === 226) {
          let url = d.data && d.data.excel_path ? d.data.excel_path : "";
          location.href = url;

          setTimeout(function() {
            $.ajax({
              url: "account/delete-excel-file",
              method: "DELETE",
              headers,
              data: JSON.stringify({excel_path: url}),
              success: function(d){

              }
            });
          }, 3000);

          return;
        }

        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;

          length = d.data.list.length;
					total_shown_product_count += length;

          if (d.data.count > limit) {
            if ($(`[data-role="table-list"] > tr`).length >= d.data.count) {
              $(`[data-role="load-more"]`).closest("div").addClass("d-none");
            } else {
              $(`[data-role="load-more"]`).closest("div").removeClass("d-none");
            }

            $(`#load_more_div`).removeClass("loading");
          } else {
            $(`[data-role="load-more"]`).closest("div").addClass("d-none");
          }
          html = content_data.map((v, i) => trComponent(v, ++i, offset) ).join("");

          loading_completed = total_shown_product_count >= d.data.count;

        } else if (d.code === 204) {
          html = warningComponent(d.message);
        }
        else {
          loading_completed = true;
          html = warningComponent(d.message);
        }

        if (loadmore) {
          if (!loading_completed) {
            $("#load_more_div").removeClass("d-none");
          } else {
            $("#load_more_div").addClass("d-none");
          }
          if (html) {
            $(`[data-role="table-list"]`).append(html);
          }
        }else{

          $(`[data-role="total-left"]`).html(typeof d.data !== "undefined" && typeof d.data.total_sum !== "undefined" ? number_format(d.data.total_sum,1,".",",") : 0);
          $(`[data-role="total-entry"]`).html(typeof d.data !== "undefined" && typeof d.data.total_entry !== "undefined" ? number_format(d.data.total_entry,1,".",",") : 0);
          $(`[data-role="total-exit"]`).html(typeof d.data !== "undefined" && typeof d.data.total_exit !== "undefined" ? number_format(d.data.total_exit,1,".",",") : 0);

          $(`[data-role="content-result-count"]`).html(count);
          $(`[data-role="total-left-amount"]`).html(typeof d.data !== "undefined" && typeof d.data.total_left_amount !== "undefined" ? number_format(d.data.total_left_amount,1,".",",") : 0);

          $(`[data-role="table-list"]`).html(html);
        }
        // $(`[data-role="table-list"]`).html(html);
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        loading = false;

        $(`[data-role="table-loader"]`).addClass("d-none");
        $(`.table-responsive`).removeClass("load");

        if(!loadmore) {
          clearInterval(interval);
          $(`[data-role="content-result-time"]`).html(
            ((new Date().getTime() - start_time) / 1000)
          );
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tooltip"]').click(function () {
          $('[data-toggle="tooltip"]').tooltip("hide");
        });

        $("button").on("blur", function() {
          $('[data-toggle="tooltip"]').tooltip("hide");
        });
        ModalLoader.end();
      },
    });
  }
  getParams();
  getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export});


  $(document).on("keypress",`[name="start_date"],[name="end_date"],[name="brand_code"],[name="brand"],[name="oem_code"]`, function(e){
    getParams();
    if (e.which === 13) {
      getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export});
    }
  });

  $(document).on("click", `[data-role="search"]`, function(e){
    getParams();
    getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export});
  });

  $(document).on("scroll", function(){
    if (!$("#load_more_div").isInViewport() || loading_completed || loading) return false;
		$("#load_more_div").addClass("loading");
		offset = $(`[data-role="table-list"] > tr`).length;
    console.log(offset);
		loading = true;
    getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export},true);
  });

	$(document).on("click",`[data-role="load-more"]`,function(){
		if (loading_completed || loading) return false;
		$("#load_more_div").addClass("loading");
		offset = $(`[data-role="table-list"] > tr`).length;
		loading = true;
    getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export},true);
	});

  const getBrands = () => {
    // if (getStorage("product_brands")) {
    //   let content_data = JSON.parse(getStorage("product_brands")),
    //       h = `<option value="">${lang("Choose brand")}</option>`;
    //   content_data.map(v => {
    //     h += `<option value="${v.name}">${v.name}</option>`;
    //   });
    //   $(`[name="brand"]`).html(h);
    //   return;
    // }
    $.get({
      url: `/products/properties/brands`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose brand")}</option>`;
          // setStorage("product_brands", JSON.stringify(d.data.list), 60 * 60 * 12);

          d.data.list.map(v => {
            h += `<option value="${v.name}">${v.name}</option>`;
          });
          $(`[name="brand"]`).html(h);
        }
      },
      error: function(d){
        console.error(d);
      },
      // complete: function(){
      //
      // }
    });
  }
  getBrands();


  $(`[data-role="excel-export"]`).on("click", function() {
    is_excel_export = "1";
    getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export});
    is_excel_export = "0";
  });
});
