"use-strict";
$(function(){
  const account_currency_name = $(`[data-role="account-currency-name"]`).data("name");
  const getParams = () => {
    start_date = $(`[name="start_date"]`).val();
    end_date = $(`[name="end_date"]`).val();
    brand_code = $(`[name="brand_code"]`).val();
    brand = $(`[name="brand"]`).val();
    oem_code = $(`[name="oem_code"]`).val();
    warehouse = $(`[name="warehouse"]`).val();
    company = $(`[name="company"]`).val();
  }

  const trTotalComponent = (totals) => {
    return `<tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-end fw-bold fst-italic" >${totals.exit ?  number_format(+totals.exit,2,",",".",0) : "0,00"}</td>
        </tr>`;
  }

  const trComponent = (d,i,offset) => {
    return `<tr data-id="${d.remote_id}">
              <th>${i + offset}</th>
              <td>${d.operation_date ? date_format(d.operation_date,"d-m-y") : ""}</td>
              <td>${d.invoice_code ? (
                +d.is_invoice ?
                `<a class="link" target="_blank" rel="nofollow noreferer" href="/invoices/purchases/${d.invoice_code}/details" >${d.invoice_code}</a>`
                : d.invoice_code) : ""}</td>
              <td><a class="link" ${d.customer_id ? `href="/customers/${d.customer_id}/account" target="_blank"` : `href="javascript:void(0)"`}">${d.customer_name || ""}</a></td>
              <td><a class="link" ${d.customer_id ? `href="/customers/${d.customer_id}/account" target="_blank"` : `href="javascript:void(0)"`}">${d.customer_code || ""}</a></td>
              <td>${d.order_number || ""}</td>
              <td>${d.source_index || ""}</td>
              <td>${d.comment || ""}</td>
              <td>${d.warehouse || ""}</td>
              ${account_currency_name !== "AZN" ? `<td class="text-end">${d.currency_rate ? number_format(d.currency_rate,2,",",".") : ""}</td>` : ""}
              <td class="text-end text-danger">${d.exit_amount ? number_format(d.exit_amount,2,",",".") : ""}</td>
            </tr>`;
  }

  let interval = null,
      excel_export = 0,
      loading = false,
      offset = 0;

  const getPurchases = (urlParams, first_time = true) => {
    let totals = [],
    start_time = null,
    count  = 0;

    let {start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset,excel_export} = urlParams;

    if (excel_export) {
      ModalLoader.start(lang("Loading"));
    }

    if(first_time && !excel_export) {
      offset = 0;

      $(`[data-role="content-result-count"]`).html("0");


      $(`[data-role="filter-exit-amount"]`).text("0,00");
      $(`[data-role="total-exit"]`).text("0,00");

      filter_url([
        {start_date: (start_date || "")},
        {end_date: (end_date || "")},
        {brand_code: (brand_code || "")},
        {brand: (brand || "")},
        {oem_code: (oem_code || "")},
        {warehouse: (warehouse || "")},
        {company: (company || "")},
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
      url: `/invoices/purchases/list-live`,
      headers,
      data: urlParams,
      success: function (d) {
        if (excel_export) {
          // console.log(d);
          if (d.code === 200) {
            url = d.data.url;
            location.href = url;
            excel_export = 0;
            getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset});
          } else {
            Swal.fire(lang("Error"),"","warning");
          }
        } else {
        if (d.code === 200) {
          $(`[data-role="excel-export"]`).removeClass("avh-disable")
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;

          totals = d.data && d.data.totals ? d.data.totals : [];

          if(first_time) {
            $(`[data-role="content-result-count"]`).html(typeof d.data !== "undefined" && typeof d.data.count !== "undefined" ? d.data.count : 0);
            $(`[data-role="filter-exit-amount"]`).text(totals.exit ?  number_format(+totals.exit,2,",",".",0) : "0,00");
            $(`[data-role="total-exit"]`).text(totals.exit ?  number_format(+totals.exit,2,",",".",0) : "0,00");
          }

          if(count > $(`[data-role="table-list"] tr`).length) {

            $(`[data-role="load-more-container"]`).removeClass("d-none").addClass("loading");
          }

          html = content_data.map((v, i) => trComponent(v, ++i, offset) ).join("");


        } else if (d.code === 204) {
          $(`[data-role="excel-export"]`).addClass("avh-disable")
          html = warningComponent(d.message);
        }
        else {
          $(`[data-role="excel-export"]`).addClass("avh-disable")
          html = warningComponent(d.message);
        }


        if(first_time) {
          $(`[data-role="table-list"]`).html(html);
        } else {
          $(`[data-role="table-list"]`).append(html);
        }

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
          $(`[data-role="load-more-container"]`).addClass("d-none").removeClass("loading");
        } else {
          $(`[data-role="load-more-container"]`).removeClass("d-none").addClass("loading");
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
  getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset});


  $(document).on("keypress",`[name="start_date"],[name="end_date"],[name="brand_code"],[name="brand"],[name="oem_code"],[name="warehouse"]`, function(e){
    getParams();
    if (e.which === 13) {
      getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset,excel_export});
    }
  });

  $(document).on("click", `[data-role="search"]`, function(e){
    getParams();
    getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset,excel_export});
  });


  $(document).on("scroll", function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset,excel_export},false);
  });

  $(document).on("click",`[data-role="load-more-container"]`,function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset,excel_export},false);
  });

  $(document).on("click",`[data-role="excel-export"]`,function(){
    if(
      !start_date.trim() &&
      !end_date.trim() &&
      !brand.trim() &&
      !brand_code.trim() &&
      !warehouse.trim() &&
      !oem_code.trim()
      ) {
      Swal.fire("", lang("minimum_one_parameter"), "warning");
      return;
    }
    getPurchases({start_date,end_date,brand_code,brand,oem_code,warehouse,company,offset,excel_export: 1});
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


  const getCustomers = () => {
    if (getStorage("companies")) {
      let content_data = JSON.parse(getStorage("companies")),
          h = `<option value="">${lang("Choose company")}</option>`;
      content_data.map(v => {
        h += `<option value="${v.remote_id}"${+company === +v.remote_id ? " selected" : ""}>${v.name}</option>`;
      });
      $(`[name="company"]`).html(h);
      return;
    }
    $.get({
      url: `/customers/simple-list-live`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose company")}</option>`;
          setStorage("companies", JSON.stringify(d.data), 60 * 60 * 12);

          d.data.map(v => {
            h += `<option value="${v.remote_id}"${+company === +v.remote_id ? " selected" : ""}>${v.name}</option>`;
          });
          $(`[name="company"]`).html(h);
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
