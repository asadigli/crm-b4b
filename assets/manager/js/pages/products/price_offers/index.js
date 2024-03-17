"use-strict";
$(function(){

  const clearTable = ({end_date, start_date, brand, keyword, entry}) => {
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0'),
    mm = String(today.getMonth() + 1).padStart(2, '0'),
    yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;

    end_date = today;
    start_date = today;

    $(`[data-role="select-end-date"]`).val(end_date);
    $(`[data-role="select-start-date"]`).val(start_date);

    $(`[data-role="table-list"]`).html("");
    $(`[data-role="content-result-count"]`).html(" 0 ");
    $(`[data-role="content-result-time"]`).html(" 0 ");
    $(`[data-role="table-loader"]`).addClass("d-none");

    getContent({end_date, start_date, brand, keyword});
  };

  const initializeHeaders = () => {
    start_date = $(`[data-role="select-start-date"]`).val().trim();
    end_date = $(`[data-role="select-end-date"]`).val().trim();
    brand = $(`[data-role="select-brands"]`).val().trim();
    entry = $(`[data-role="select-entries"]`).val().trim();
    keyword = $(`[data-role="search-keyword"]`).val().trim();
  };

  const trComponent = (d, i) => {
    return `
      <tr data-id="${d.id}">
        <td>${i}</td>
        <td>${d.operation_date ?? ""}</td>
        <td>${d.entry_name ?? ""}</td>
        <td>${d.customer_name ?? ""}</td>
        <td>${d.brand_name ? highlightLabel(d.brand_name) : ""}</td>
        <td>
          <b>
            <span data-search="code" class="link" data-role="brand-code" data-code="${d.brand_code}">
              ${d.brand_code ? highlightLabel(d.brand_code) : ""}
            </span>
          </b>
        </td>
        <td>
          <div class="d-flex">
            ${d.OEM ? `
              <a class="camera-link me-2" data-role="window-img-search" href="javascript:void(0)">
                <i class="fa fa-camera" aria-hidden="true"></i>
              </a>` : ""}
            <div class="d-flex flex-column">
              ${d.OEM ? `<span data-role="oem" data-search="code" class="link me-1" data-code="${d.OEM}">
                ${highlightLabel(d.OEM)}
              </span>` : ""}
            </div>
          </div>
        </td>
        <td>${d.products_name ?? ""}</td>
        <td>${d.company_name ?? ""}</td>
        <td>${d.price ?? ""}</td>
        <td data-role="sale-price-td"  style="text-align:right;" >
          ${ d.has_discount ? (d.sale_price ? `<div class="d-flex align-items-center justify-content-end">
                                <s class="text-danger me-1">${number_format(d.sale_price || 0, 2,",",".",0) +(d.currency ?? "")}</s>`
                                + number_format(d.discount_price || 0, 2,",",".",0) + (d.currency ?? "")
                                + "</div>" : (d.sale_price_description || "")) :
                              (d.sale_price ? `<div class="d-flex align-items-center justify-content-end">`
                                                + number_format(d.sale_price || 0, 2,",",".",0) +(d.currency ?? "")
                                                + "</div>" : (d.sale_price_description || ""))}

          ${ d.has_discount ? (d.sale_price ?
                                    (d.currency !== "AZN" ?
                                      `<div class="d-flex align-items-center justify-content-end">
                                        <span>
                                          <s class="text-danger me-1">${number_format(d.converted_sale_price || 0, 2,",",".",0) + (d.converted_currency ?? "")}</s>
                                        </span>
                                        <span style="display: block;font-size: 12px;color: #585858;" >
                                            ${number_format(d.converted_discount_price || 0, 2, ",", ".",0) + " " + d.converted_currency}
                                        </span>
                                      </div>` : "") : "") :
                              (d.sale_price ?
                                    (d.currency !== "AZN" ?
                                      `<div class="d-flex align-items-center justify-content-end">
                                          <span style="display: block;font-size: 12px;color: #585858;" >
                                            ${number_format(d.converted_sale_price || 0, 2,",",".",0) + (d.converted_currency ?? "")}
                                          </span>
                                      </div>` : "") : "")}
        </td>
      </tr>`;
  };

  let interval = null,
  loading = false,
  offset = 0;

  const getContent = (urlParams) => {

    let html = "",
    start_time = null,
    count = 0;

    offset = 0;
    $(`[data-role="content-result-count"]`).html("0");
    $(`[data-role="content-result-time"]`).html("0");

    let {end_date, start_date, brand, keyword, entry} = urlParams;

    filter_url([
      {start_date: (start_date || "")},
      {end_date: (end_date || "")},
      {brand: (brand || "")},
      {entry: (entry || "")},
      {keyword: (keyword || "")},
    ]);

    if (!(start_date && end_date)) {
      Swal.fire("", lang("minimum_date_should_selected_parameter"), "warning");
      return;
    }

    if(keyword){
      $(`[data-role="search-keyword"]`).val(keyword);
    }

    start_time = new Date().getTime();
    clearInterval(interval);
    interval = setInterval(function () {
      $(`[data-role="content-result-time"]`).html(
        (new Date().getTime() - start_time) / 1000
      );
    }, 100);

    $(`[data-role="table-loader"]`).removeClass("d-none");
    $(`.table-responsive`).addClass("load");



    ModalLoader.start(lang("Loading"));
    $.get({
      url: `/products/price-offers/list-live`,
      headers,
      data: {end_date, start_date, brand_id: brand, keyword, entry_id: entry},
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;
          html = content_data.map((v, i) => trComponent(v, ++i)).join("");
          $(`[data-role="table-list"]`).html(html);
        }
        else if (d.code === 204) {
          html = warningComponent(d.message);
        }
        else {
          html = warningComponent(d.message);
          Swal.fire("", d.message, "warning");
          console.log(d);
        }

        $(`[data-role="content-result-count"]`).html(count);
        $(`[data-role="table-list"]`).html(html);
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){
        loading = false;
          clearInterval(interval);
          $(`[data-role="content-result-time"]`).html(
            ((new Date().getTime() - start_time) / 1000)
          );

        $(`[data-role="table-loader"]`).addClass("d-none");
        $(`.table-responsive`).removeClass("load");
        ModalLoader.end();
        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find("button").on("blur", function() {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    });
  }

  let start_date = getUrlParameter("start_date") || $(`[data-role="select-start-date"]`).val(),
    end_date = getUrlParameter("end_date") || $(`[data-role="select-end-date"]`).val(),
    brand = getUrlParameter("brand") || $(`[data-role="select-brands"]`).val(),
    entry = getUrlParameter("entry") || $(`[data-role="select-entries"]`).val(),
    keyword = getUrlParameter("keyword") || $(`[data-role="search-keyword"]`).val();

  getContent({end_date, start_date, brand, entry});

  $(`[data-role="search-keyword"], [data-role="select-start-date"], [data-role="select-end-date"]`).on("keypress", function(e){
    if (e.which === 13) {
      initializeHeaders();
        getContent({end_date, start_date, brand, keyword, entry});
      }
  });

  $(document).on("click", `[data-role="search-filter"]`, function() {
    initializeHeaders();
    getContent({end_date, start_date, brand, keyword, entry});
  });

  $(document).on("click", `[data-role="clear-filter"]`, function() {
    clearTable({end_date, start_date, brand, keyword, entry});
  });

  const getBrands = () => {

    $(`[data-role="select-brands"]`).parents(".form-group").addClass("loader");
    let html = "";
    $.get({
        url: `/products/properties/brands`,
        headers,
        cache: true,
        success: function (d) {
          if (d.code === 200) {
            let content_data = d.data.list ?? [];
            if(content_data.length){
              setStorage("bpm_product_brands", JSON.stringify(content_data), 60 * 60 * 12);
            }
            html = content_data.map((v, i) => `<option value="${v.id}" ${brand === v.id ? " selected" : ""}>${(v.name ? v.name : lang("without_brand"))}</option>` ).join("");
            $(`[data-role="select-brands"]`).attr("disabled",false);
          } else if (d.code === 204) {
            html = "";
          }
          else {
            html = "";
            Swal.fire("", d.message, "warning");
          }
          $(`[data-role="select-brands"]`).html(`<option value="">${lang("All brands")}</option>`+html);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
          $(`[data-role="select-brands"]`).parents(".form-group").removeClass("loader");
        },
      });
    };
    getBrands();

    const getEntries = () => {

      $(`[data-role="select-entries"]`).parents(".form-group").addClass("loader");
      let html = "";
      $.get({
          url: `/products/comments/entries-list`,
          headers,
          cache: true,
          success: function (d) {
            if (d.code === 200) {
              let content_data = d.data.list ?? [];
              if(content_data.length){
                setStorage("b4b_entries_list", JSON.stringify(content_data), 60 * 60 * 12);
              }
              html = content_data.map((v, i) => `<option value="${v.id}" ${entry === v.id ? " selected" : ""}>${(v.name ? v.name : "")}</option>` ).join("");
              $(`[data-role="select-entries"]`).attr("disabled",false);
            } else if (d.code === 204) {
              html = "";
            }
            else {
              html = "";
              Swal.fire("", d.message, "warning");
            }
            $(`[data-role="select-entries"]`).html(`<option value="">${lang("All entries")}</option>`+html);
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            $(`[data-role="select-entries"]`).parents(".form-group").removeClass("loader");
          },
        });
      };
      getEntries();

    $(document).on("click", `[data-role="window-img-search"]`, function(e) {
      window.event.preventDefault();
      let code = $(this).parents("td").find(`[data-role="oem"]`).text().trim();
      if(!code.trim()) {
        code = $(`[data-role="brand-code"]`).data("code");
      }
      this.newWindow = window.open(
        `https://www.google.com/images?q=${code}&url=`+escape(document.location.pathname)+'&referrer='+escape(document.referrer), 'webim', `toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=800,height=500,top=20, left=400,resizable=1`);
      this.newWindow.focus();
      this.newWindow.opener=window;
      return false;
    });

    $(document).on("click", `[data-search="code"]`, function(){
      // clearValues({keyword, brand, in_stock, search_type, offset});
      keyword = $(this).data("code");
      brand = "";
      entry = "";
      $(`[data-role="search-keyword"]`).val(keyword);
      filter_url([
        {start_date: (start_date || "")},
        {end_date: (end_date || "")},
        {brand: (brand || "")},
        {entry: (entry || "")},
        {keyword: (keyword || "")},
      ]);
      getContent({end_date, start_date, brand, keyword, entry});
    });

});
