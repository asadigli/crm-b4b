"use-strict";
$(function(){
  const customer_id = $(`[data-role="customer-info"]`).data("id");
  const account_currency_name = $(`[data-role="account-currency-name"]`).data("name");

  const getParams = () => {
    start_date = $(`[name="start_date"]`).val();
    end_date = $(`[name="end_date"]`).val();
    brand_code = $(`[name="brand_code"]`).val();
    brand = $(`[name="brand"]`).val();
    oem_code = $(`[name="oem_code"]`).val();
  }

  const trTotalComponent = (totals, whole_totals) => {
    return `<tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-end fw-bold fst-italic" >${totals.entry ?  number_format(+totals.entry,2,",",".",0) : "0,00"}</td>
          <td class="text-end fw-bold fst-italic" >${totals.exit ?  number_format(+totals.exit,2,",",".",0)  : "0,00"}</td>
          <td class="text-end fw-bold fst-italic" >${totals.balance ?  number_format(+totals.balance,2,",",".",0) : "0,00"}</td>
          <td class="text-end fw-bold fst-italic" >${whole_totals.left_amount ?  number_format(+whole_totals.left_amount,2,",",".",0)  : "0,00"}</td>
        </tr>`;
  }

  const trComponent = (d,i,offset) => {
    return `<tr data-id="${d.remote_id}">
              <th>${i + offset}</th>
              <td>${d.operation_date ? date_format(d.operation_date,"d-m-y") : ""}</td>
              <td>${d.invoice_code ? (
                +d.is_invoice ?
                `<a class="link" target="_blank" rel="nofollow noreferer" href="/invoices/sales/${d.invoice_code}/details" >${d.invoice_code}</a>`
                : d.invoice_code) : ""}</td>
              <td>${d.type || ""}</td>
              <td>${d.payment_type || ""}</td>
              <td>${d.warehouse || ""}</td>
              ${account_currency_name !== "AZN" ? `<td class="text-end">${d.currency_rate ? number_format(d.currency_rate,2,",",".") : ""}</td>` : ""}
              <td class="text-end text-danger">${d.entry_amount ? number_format(d.entry_amount,2,",",".") : ""}</td>
              <td class="text-end text-success">${d.exit_amount ? number_format(d.exit_amount,2,",",".") : ""}</td>
              <td class="text-end">${d.balance ? number_format(d.balance,2,",",".") : ""}</td>
              <td class="text-end">${d.left_amount ? number_format(d.left_amount,2,",",".") : ""}</td>
            </tr>`;
  }

  let interval = null,
    loading = false,
    offset = 0;

  const getAccount = (urlParams, first_time = true) => {
    let totals = [],
    whole_totals = [],
    start_time = null,
    count  = 0;

    let {start_date,end_date,brand_code,brand,oem_code,offset} = urlParams;

    if(first_time) {
      offset = 0;

      $(`[data-role="content-result-count"]`).html("0");


      $(`[data-role="filter-entry-amount"]`).text("0,00");
      $(`[data-role="filter-exit-amount"]`).text("0,00");
      $(`[data-role="filter-balance-amount"]`).text("0,00");

      $(`[data-role="total-entry"]`).text("0,00");
      $(`[data-role="total-exit"]`).text("0,00");
      $(`[data-role="total-balance"]`).text("0,00");
      $(`[data-role="total-left"]`).text("0,00");

      filter_url([
        {start_date: (start_date || "")},
        {end_date: (end_date || "")},
        {brand_code: (brand_code || "")},
        {brand: (brand || "")},
        {oem_code: (oem_code || "")},
      ]);

      start_time = new Date().getTime();
      clearInterval(interval);
      interval = setInterval(function () {
        $(`[data-role="content-result-time"]`).html(
          (new Date().getTime() - start_time) / 1000
        );
      }, 100);

      ModalLoader.start(lang("Loading"));
    }

    $.get({
      url: `/customers/${customer_id}/account/list-live`,
      headers,
      data: urlParams,
      success: function (d) {
        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;

          totals = d.data && d.data.totals ? d.data.totals : [];
          whole_totals = d.data && d.data.whole_totals ? d.data.whole_totals : [];

          if(first_time) {
            $(`[data-role="content-result-count"]`).html(typeof d.data !== "undefined" && typeof d.data.count !== "undefined" ? d.data.count : 0);


            $(`[data-role="filter-entry-amount"]`).text(totals.entry ?  number_format(+totals.entry,2,",",".",0) : "0,00");
            $(`[data-role="filter-exit-amount"]`).text(totals.exit ?  number_format(+totals.exit,2,",",".",0) : "0,00");
            $(`[data-role="filter-balance-amount"]`).text(totals.balance ?  number_format(+totals.balance,2,",",".",0) : "0,00");

            $(`[data-role="total-entry"]`).text(totals.entry ?  number_format(+totals.entry,2,",",".",0) : "0,00");
            $(`[data-role="total-exit"]`).text(totals.exit ?  number_format(+totals.exit,2,",",".",0) : "0,00");
            $(`[data-role="total-balance"]`).text(totals.balance ?  number_format(+totals.balance,2,",",".",0) : "0,00");
            $(`[data-role="total-left"]`).text(whole_totals.left_amount ?  number_format(+whole_totals.left_amount,2,",",".",0) : "0,00");

          }

          if(count > $(`[data-role="table-list"] tr`).length) {

            $(`[data-role="load-more-container"]`).removeClass("d-none");
            $(`[data-role="load-more-container"]`).addClass("loading");
          }

          html = content_data.map((v, i) => trComponent(v, ++i, offset) ).join("");


        } else if (d.code === 204) {
          html = warningComponent(d.message);
        }
        else {
          html = warningComponent(d.message);
        }


        if(first_time) {
          $(`[data-role="table-list"]`).html(html);
        } else {
          $(`[data-role="table-list"]`).append(html);
        }


        if(count <= $(`[data-role="table-list"] tr`).length) {
          html = trTotalComponent(totals, whole_totals);
          $(`[data-role="table-list"]`).append(html);
        }
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        loading = false;
        if(first_time) {
          clearInterval(interval);
          $(`[data-role="content-result-time"]`).html(
            ((new Date().getTime() - start_time) / 1000)
          );
        }

        if(+count <= $(`[data-role="table-list"] tr`).length) {
          $(`[data-role="load-more-container"]`).addClass("d-none");
          $(`[data-role="load-more-container"]`).removeClass("loading");
        } else {
          $(`[data-role="load-more-container"]`).removeClass("d-none");
          $(`[data-role="load-more-container"]`).addClass("loading");
        }

        $(`[data-role="table-loader"]`).addClass("d-none");
        $(`.table-responsive`).removeClass("load");


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
  getAccount({start_date,end_date,brand_code,brand,oem_code,offset});


  $(document).on("keypress",`[name="start_date"],[name="end_date"],[name="brand_code"],[name="brand"],[name="oem_code"]`, function(e){
    getParams();
    if (e.which === 13) {
      getAccount({start_date,end_date,brand_code,brand,oem_code,offset});
    }
  });

  $(document).on("click", `[data-role="search"]`, function(e){
    getParams();
    getAccount({start_date,end_date,brand_code,brand,oem_code,offset});
  });


  $(document).on("scroll", function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getAccount({start_date,end_date,brand_code,brand,oem_code,offset},false);
  });

  $(document).on("click",`[data-role="load-more-container"]`,function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getAccount({start_date,end_date,brand_code,brand,oem_code,offset},false);
  });


  const getBrands = () => {
    if (getStorage("product_brands")) {
      let content_data = JSON.parse(getStorage("product_brands")),
          h = `<option value="">${lang("Choose brand")}</option>`;
      content_data.map(v => {
        h += `<option value="${v.name}">${v.name}</option>`;
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

  let max_order_limit_in = "";
  $(`[data-role="max-order-limit"]`).on("focusin", function () {
    max_order_limit_in = $(this).val();
  });

  $(`[data-role="max-order-limit"]`).on("focusout", function(event) {
    let max_order_limit = +$(this).val(),
    el = $(this),
    customer_id = $(`[data-role="customer-info"]`).data("id");

    if (+max_order_limit_in === +max_order_limit) {
      return;
    }

    let data = {max_order_limit, customer_id};

    disableAll(true);
    $.ajax({
      url: `/customers/${customer_id}/edit-max-order-limit`,
      method: "PUT",
      headers,
      data: JSON.stringify(data),
      // headers,
      success: function (d) {
        if (d.code === 202 ) {
            notify("success", d.message);
        } else {
            notify("warning", d.message);
        }
      },
      error: function(d) {
        console.log(d);

      },
      complete: function(d) {
        disableAll(false);
      }
    });
  });


  let max_allowed_order_limit_in = "";
  $(`[data-role="max-allowed-order-limit"]`).on("focusin", function () {
    max_allowed_order_limit_in = $(this).val();
  });

  $(`[data-role="max-allowed-order-limit"]`).on("focusout", function(event) {
    let max_allowed_order_limit = +$(this).val(),
    el = $(this),
    customer_id = $(`[data-role="customer-info"]`).data("id");

    if (+max_allowed_order_limit_in === +max_allowed_order_limit) {
      return;
    }

    let data = {max_allowed_order_limit, customer_id};

    disableAll(true);
    $.ajax({
      url: `/customers/${customer_id}/edit-max-allowed-order-limit`,
      method: "PUT",
      headers,
      data: JSON.stringify(data),
      // headers,
      success: function (d) {
        if (d.code === 202 ) {
            notify("success", d.message);
        } else {
            notify("warning", d.message);
        }
      },
      error: function(d) {
        console.log(d);

      },
      complete: function(d) {
        disableAll(false);
      }
    });
  });

  $(`[data-role="has-order-limit"]`).on("change", function(){
    let has_order_limit = $(this).is(":checked") ? "1" : "0",
    customer_id = $(`[data-role="customer-info"]`).data("id");

    let data = {has_order_limit,customer_id};

    disableAll(true);
    $.ajax({
      url: `/customers/${customer_id}/edit-has-order-limit`,
      method: "PUT",
      headers,
      data: JSON.stringify(data),
      // headers,
      success: function (d) {
        if (d.code === 202 ) {
            notify("success", d.message);
        } else {
            notify("warning", d.message);
        }
      },
      error: function(d) {
        console.log(d);

      },
      complete: function(d) {
        disableAll(false);
      }
    });

  });
});
