"use-strict";
$(function(){

  $(".custom-select").select2({
    minimumResultsForSearch: 15,
    // minimumInputLength: 2,
    language: {
      noResults: function () {
           return lang("no_results");
      },
      inputTooShort: function() {
        return lang("enter_minimum_2_symbols");
      }
    }
  });

  let start_date = getUrlParameter("start_date") || $(`[name="start_date"]`).val(),
      end_date = getUrlParameter("end_date") || $(`[name="end_date"]`).val(),
      brand_code = getUrlParameter("brand_code") || $(`[name="brand_code"]`).val(),
      brand = getUrlParameter("brand") || $(`[name="brand"]`).val(),
      customer = getUrlParameter("customer") || $(`[name="customer"]`).val();

  const getParams = () => {
    start_date = $(`[name="start_date"]`).val();
    end_date = $(`[name="end_date"]`).val();
    brand_code = $(`[name="brand_code"]`).val();
    brand = $(`[name="brand"]`).val();
    customer = $(`[name="customer"]`).val();
  }

  const trComponent = (d,i,offset) => {
    return `<tr data-id="${d.remote_id}">
              <th>${i + offset}</th>
              <td>${d.brand_code ?? ""}</td>
              <td>${d.OEM ?? ""}</td>
              <td>${d.brand ?? ""}</td>
              <td>${d.customer ?? ""}</td>
              <td>${d.description ?? ""}</td>
              <td>${d.quantity ? number_format(d.quantity,0) : ""}</td>
              <td>${d.invoice_code ? (
                +d.tr_code === 33 || +d.tr_code === 38 ?
                `<a class="link" target="_blank" rel="nofollow noreferer" href="/account/${d.invoice_code}/details" >${d.invoice_code}</a>`
                : d.invoice_code) : ""}</td>
              <td class="text-end">${d.amount ? number_format(d.amount,2,",","") : ""}</td>
              <td class="text-end">${d.total_amount ? number_format(d.total_amount,2,",","") : ""}</td>
              <td class="text-end">${d.buying_price ? number_format(d.buying_price,2,",","") : ""}</td>
              <td>${d.operation_date ? date_format(d.operation_date,"d-m-y") : ""}</td>
            </tr>`;
  }

  let offset = 0,
      limit = 100,
			loading = false,
			loading_completed = false,
			total_shown_product_count = 0;

  const getAccount = (data,loadmore = false) => {
    filter_url([
      {start_date: (data.start_date || "")},
      {end_date: (data.end_date || "")},
      {brand_code: (data.brand_code || "")},
      {brand: (data.brand || "")},
      {customer: (data.customer || "")},
    ]);

    let interval = null,
    start_time = new Date().getTime();
    if (!loadmore) {
      clearInterval(interval);
      interval = setInterval(function(){$(`[data-role="content-result-time"]`).html((Math.round((new Date().getTime() - start_time)/100)/10).toFixed(3));},100);
    }

    if (!loadmore) {
      ModalLoader.start(lang("Loading"));
      total_shown_product_count = 0;
      loading_completed = false;
      offset = 0;
    }
    data["offset"] = offset;
    $.get({
      url: `/brand-reports/invoices/live-in-details`,
      headers,
      data,
      success: function (d) {
        // console.log(d);
        if (d.code === 200) {
          let content_data = d.data.list ?? [];

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
          $(`[data-role="content-result-count"]`).html(d.data.count);
          $(`[data-role="content-total-sale-amount"]`).html(d.data.total_sale_amount ? number_format(d.data.total_sale_amount,2,".",",") : 0);
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

        if (!loadmore) {
          clearInterval(interval); // stop the interval
          $(`[data-role="content-result-time"]`).html((((new Date().getTime() - start_time)/100)/10).toFixed(3));
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
  getAccount({start_date,end_date,brand_code,brand,customer});


  $(`[name="start_date"],[name="end_date"],[name="brand_code"]`).on("keypress", function(e){
    getParams();
    if (e.which === 13) {
      getAccount({start_date,end_date,brand_code,brand,customer});
      }
  });

  $(`[data-role="search"]`).on("click", function(e){
    $(`[data-role="load-more"]`).closest("div").addClass("d-none");
    getParams();
    getAccount({start_date,end_date,brand_code,brand,customer});
  });

  $(document).on("scroll", function(){
    if (!$("#load_more_div").isInViewport() || loading_completed || loading) return false;
		$("#load_more_div").addClass("loading");
		offset = $(`[data-role="table-list"] > tr`).length;
		loading = true;
    getAccount({start_date,end_date,brand_code,brand,customer},true);
  });

	$(document).on("click",`[data-role="load-more"]`,function(){
		if (loading_completed || loading) return false;
		$("#load_more_div").addClass("loading");
		offset = $(`[data-role="table-list"] > tr`).length;
		loading = true;
    getAccount({start_date,end_date,brand_code,brand,customer},true);
	});

  const getBrands = () => {
    if (getStorage("product_brands")) {
      let content_data = JSON.parse(getStorage("product_brands")),
          h = `<option value="">${lang("All")}</option>`;
      content_data.map(v => {
        if (brand === v.name) {
          h.replace("selected","");
        }
        h += v.name ? `<option value="${v.name}"${brand === v.name ? " selected" : ""}>${v.name}</option>` : ``;
      });
      $(`[name="brand"]`).html(h);
      return;
    }
    $.get({
      url: `/products/properties/brands`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose brand")}</option>`;
          setStorage("product_brands", JSON.stringify(d.data.list), 60 * 60 * 12);

          d.data.list.map(v => {
            if (brand === v.name) {
              h.replace("selected","");
            }
            h += v.name ? `<option value="${v.name}"${brand === v.name ? " selected" : ""}>${v.name}</option>` : ``;
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

  const getCustomers = () => {
    if (getStorage("customers")) {
      let content_data = JSON.parse(getStorage("customers")),
          h = `<option value="">${lang("Choose customer")}</option>`;
      content_data.map(v => {
        h += `<option value="${v.remote_id}"${+customer === +v.remote_id ? " selected" : ""}>${v.name}</option>`;
      });
      $(`[name="customer"]`).html(h);
      return;
    }
    $.get({
      url: `/customers/simple-list-live`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose customer")}</option>`;
          setStorage("customers", JSON.stringify(d.data), 60 * 60 * 12);

          d.data.map(v => {
            h += `<option value="${v.remote_id}"${+customer === +v.remote_id ? " selected" : ""}>${v.name}</option>`;
          });
          $(`[name="customer"]`).html(h);
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
  getCustomers();

});
