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
    currency = $(`[name="currency"]`).val();
  }

  const trTotalComponent = (totals) => {
    return `<tr>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-end fw-bold fst-italic" >${totals.entry ?  number_format(+totals.entry,2,",",".",0) : "0,00"}</td>
        </tr>`;
  }

  const trComponent = (d,i) => {
    return `<tr data-id="${d.remote_id}">
              <th>${i}</th>
              <td><a class="link" href="/invoices/sales?start_date=${d.operation_date}&end_date=${d.operation_date}" target="_blank" rel="nofollow noreferer">${d.operation_date ? date_format(d.operation_date,"d-m-y") : ""}</a></td>
              <td class="text-end">${d.entry_amount ? number_format(d.entry_amount,2,",",".") : "0,00"}</td>
              <td class="text-end">${d.entry_amount_azn ? number_format(d.entry_amount_azn,2,",","") : "0,00"}</td>
            </tr>`;
  }

  let interval = null,
      loading = false,
      excel_export = 0;

  const getSales = (urlParams, first_time = true) => {
    let totals = [],
    start_time = null,count = 0;

    let {start_date,end_date,brand_code,brand,oem_code,excel_export,warehouse,currency} = urlParams;

    if (excel_export) {
      ModalLoader.start(lang("Loading"));
    }

    if(!excel_export) {

      $(`[data-role="filter-entry-amount"]`).text("0,00");
      $(`[data-role="total-entry"]`).text("0,00");

      filter_url([
        {start_date: (start_date || "")},
        {end_date: (end_date || "")},
        {brand_code: (brand_code || "")},
        {brand: (brand || "")},
        {oem_code: (oem_code || "")},
        {warehouse: (warehouse || "")},
        {currency: (currency || "")},
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
      url: `/invoices/sales/daily-list-live`,
      headers,
      data: urlParams,
      success: function (d) {
        if (excel_export) {
          // console.log(d);
          if (d.code === 200) {
            url = d.data.url;
            location.href = url;
            excel_export = 0;
            getSales({start_date,end_date,brand_code,brand,oem_code,excel_export,warehouse,currency});
          } else {
            Swal.fire(lang("Error"),"","warning");
          }
        } else {
          if (d.code === 200) {
            $(`[data-role="excel-export"]`).removeClass("avh-disable")
            count = d.data && d.data.list.length ? d.data.list.length : 0;
            let content_data = d.data && d.data.list ? d.data.list : [];
            totals = d.data && d.data.totals ? d.data.totals : [];

            $(`[data-role="total-entry"]`).text(number_format(d.data.totals.entry_amount,2,",","."));

            $(`[data-role="content-result-count"]`).html(count);

            html = content_data.map((v, i) => trComponent(v, ++i) ).join("");


          } else if (d.code === 204) {
            html = warningComponent(d.message);
            $(`[data-role="excel-export"]`).addClass("avh-disable")
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
          clearInterval(interval);
          $(`[data-role="content-result-time"]`).html(
            ((new Date().getTime() - start_time) / 1000)
          );


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
  getSales({start_date,end_date,brand_code,brand,oem_code,warehouse,currency});


  $(document).on("keypress",`[name="start_date"],[name="end_date"],[name="brand_code"],[name="brand"],[name="oem_code"]`, function(e){
    getParams();
    if (e.which === 13) {
      getSales({start_date,end_date,brand_code,brand,oem_code,warehouse,currency,excel_export});
    }
  });

  $(document).on("click", `[data-role="search"]`, function(e){
    getParams();
    getSales({start_date,end_date,brand_code,brand,oem_code,warehouse,currency,excel_export});
  });



  $(document).on("click",`[data-role="excel-export"]`,function(){
    if(
      !start_date.trim() &&
      !end_date.trim() &&
      !brand_code.trim() &&
      !warehouse.trim() &&
      !currency.trim() &&
      !oem_code.trim()
      ) {
      Swal.fire("", lang("minimum_one_parameter"), "warning");
      return;
    }

    let tableList = $("#sales_list_tbody > tr").length;
    if (tableList.length <= 1) {
      Swal.fire("", lang("no result to excel export"), "error");
      return;
    }

    getSales({start_date,end_date,brand_code,brand,oem_code,warehouse,currency,excel_export: 1});
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
            h += `<option value="${v.name}">${v.name ?? ""}</option>`;
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

});
