"use-strict";
$(function(){
  const trComponent = (d, i, offset) => {
    return `<tr
              data-id="${d.id ?? ""}"
              data-remote-id="${d.remote_id ?? ""}"
              data-resource="${d.resource ?? ""}"
              data-in-cart="${d.in_cart ? 1 : 0}"
              data-name="${d.name ?? ""}"
              data-brand-name="${d.brand.name ?? ""}"
              data-brand-code="${d.brand.code ?? ""}"
              data-description="${d.description ?? ""}"
              data-model="${d.model ?? ""}"
              data-oem="${d.OEM ?? ""}"
              data-sale-price="${d.sale_price ?? ""}"
              data-main-price="${d.main_price ?? ""}"
              data-currency="${d.currency ?? ""}"
              data-final-currency="${d.final_currency ?? ""}"
              class="${d.resource === '0x002' || d.resource === '0x003' ? "bg-ultra-blue" : ""}"
              >
      <td>${i + offset}</td>

      <td>${keyword && d.brand && d.brand.name ? highlightLabel(d.brand.name,keyword) : (d.brand && d.brand.name ? d.brand.name : "")}</td>
      <td>
        <b>
          <span data-search="code" class="link" data-role="brand-code" data-code="${d.brand.cleaned_code}">
            ${keyword && d.brand && d.brand.code ? highlightLabel(d.brand.code,keyword) : (d.brand && d.brand.code ? d.brand.code : "")}
          </span>
        </b>
      </td>
      <td>
        <div class="d-flex" >
        ${d.OEM ? `<a class="camera-link me-2" data-role="window-img-search" href="javascript:void(0)">
          <i class="fa fa-camera" aria-hidden="true"></i>
        </a>` : ""}
        <div class="d-flex flex-column">
          ${d.OEM && Array.isArray(d.OEM) && d.OEM.length ? d.OEM.map((o) => {
            return `  <span data-role="oem" data-search="code" class="link me-1" data-code="${o}" >${keyword && o ? highlightLabel(o,keyword) : (o ?? "")}</span>`;
          }).join(" ") : ""}
        </div>

        </div>
      </td>
      <td>${keyword && d.name ? highlightLabel(d.name,keyword) : (d.name ?? "")}</td>
      <td>${d.description ?? ""}</td>
      <td>${d.buying_price ?? ""}</td>
      <td>${d.model ?? ""}</td>
      <td>
       <div class="d-flex justify-content-end">
         <button
           data-toggle="tooltip"
           data-placement="top"
           title="${lang("Comment")}"
           data-bs-toggle="modal"
           data-bs-target="#addComment"
           type="button"
           data-role="comment"
           data-product-id="${d.id ?? ""}"
           class="btn btn-primary ms-2"
         >
           <i class="fas fa-comment"></i>
         </button>
       </div>
     </td>
      <td >
        ${d.stock_baku ?? "0"}
      </td>
      <td >
        ${d.stock_baku_2 ?? "0"}
      </td>
      <td >
      ${ d.stock_ganja ?? "0" }
      </td>
      <td>
        <div
            class="spec-popover"

            data-bs-container="body"
            data-bs-toggle="popover"
            data-bs-placement="left"
            data-bs-html="true"
            data-bs-content="${lang("Is new from warehouse")}
                                ${d.new_from_warehouse_start_date && d.is_new_from_warehouse ? "<br>" + lang("Start date")+": " + d.new_from_warehouse_start_date : ""}
                                ${d.new_from_warehouse_end_date && d.is_new_from_warehouse ? "<br>" + lang("End date")+": " + d.new_from_warehouse_end_date : ""}
                              "
            >
          <label class="form-check-label text-muted">
          <input
              style="width:18px;height:18px;"
              type="checkbox"
              data-role="is-new-from-warehouse"
              data-start-date="${d.new_from_warehouse_start_date || ""}"
              data-end-date="${d.new_from_warehouse_end_date || ""}"
              name="is_new_from_warehouse" ${+d.is_new_from_warehouse ? "checked" : " "}
          >
        </div>
      </td>
      <td>${d.delivery_time ?? ""}</td>
      <td>
        <div data-toggle="tooltip" data-placement="top" title="${lang("Hide the price")}">
          <label class="form-check-label text-muted">
          <input style="width:18px;height:18px;" type="checkbox" data-role="is-b4b-price-hidden" name="is_b4b_price_hidden" ${+d.is_b4b_price_hidden ? "checked" : " "}>
        </div>
      </td>
      <td
        data-role="sale-price-td"
        style="text-align:right;"
      >
        ${ number_format(d.sale_price || 0, 2,",",".",0) } ${ d.currency ?? "" }
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text rounded-0">
              <span >${ (d.main_currency ? d.main_currency + " " : "") +  number_format(d.main_sale_price || 0, 2,",",".",0) }</span>
            </div>
            <input
              style="min-width:50px;max-width:50px;padding: 5px;"
              class="form-control rounded-0 rounded-end"
              data-role="price"
              data-name="custom-converted-price"
              data-price="${+d.custom_main_sale_price ?? ""}"
              value="${d.custom_main_sale_price ? +d.custom_main_sale_price : ""}"
            >
          </div>
        </div>
        </td>
        <td>
         <div class="d-flex justify-content-end">
           <button
             data-placement="top"
             data-bs-toggle="modal"
             data-bs-target="#addPriceOffer"
             type="button"
             data-role="price-offers"
             data-product-id="${d.id ?? ""}"
             class="btn btn-primary ms-2"
           >
             <i class="fas fa-tags"></i>
           </button>
         </div>
       </td>
        <td>
          <div class="input-group flex-nowrap mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text bg-transparent pr-2 border" >
                <div class="form-check p-0">
                  <label class="form-check-label text-muted">
                    <input style="width:18px;height:18px;" type="checkbox" data-role="is-discount" name="is_discount" ${+d.has_discount ? "checked" : " "}>
                  </label>
                </div>
              </span>
            </div>
            <input
              style="min-width:50px;max-width:50px;padding: 5px;"
              type="number"
              class="form-control"
              data-role="discount-price"
              value="${+d.discount_price}"
            >
          </div>
        </td>
        <td class="text-nowrap">${d.last_sale_operation_date || ""}</td>
    </tr>`;
    // d.last_sale_operation.date ?? ""
  };

  // <td style="text-align:right;" >
  //     ${ number_format(d.sale_price || 0, 2,",",".",0) }
  // </td>
  // <td style="text-align:right;" >
  //   <div class="input-group-div">
  //     <span>${ number_format(d.main_sale_price || 0, 2,",",".",0) }</span>
  //     <input
  //       style="padding:5px;"
  //       class="form-control"
  //       data-role="price"
  //       data-name="custom-converted-price"
  //       data-price="${+d.custom_main_sale_price ?? ""}"
  //       value="${d.custom_main_sale_price ? +d.custom_main_sale_price : ""}"
  //     >
  //   </div>
  // </td>

  const crossCodeTrComponent = (d,i) => {
    let html = d[1].map((v,i) => {
                return `<tr>
                    ${i === 0 ? `<td rowspan="${d[1].length}" >${v.oembrand ?? ""}</td>` : ""}
                  <td>
                    <div class="d-flex" >
                      <a class="camera-link me-2" data-role="window-img-search" href="javascript:void(0)">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                      </a>
                      <span data-role="oem">${v.oemnumber ?? ""}</span>
                    </div>
                  </td>
                  <td>${v.number ?? ""}</td>
                  <td>
                    <div class="d-flex" >
                      <a class="camera-link me-2" data-role="window-img-search" href="javascript:void(0)">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                      </a>
                      <span data-role="oem">${v.brand ?? ""}</span>
                    </div>
                  </td>
                  <td>${v.group ?? ""}</td>
                  <td>${v.product ?? ""}</td>
                </tr>`;
              }).join("");

    return html;
  };

  const warningComponent = (message) => {
    return `<tr>
              <td style="padding:0;margin:0;" colspan="200">
                <div class="d-flex justify-content-center" >
                  <div style="margin:0.3rem 0.8rem; width:80%;text-align:center;color: #676689!important;" role="alert">
                      <strong>${message ?? ""}</strong>
                  </div>
                </div>
              </td>
            </tr>`;
  };

  const commentComponent = (d,i) => {
    return `<div class="card d-inline-block mb-3 float-end me-2 bg-primary max-w-p80 p-2">
    <div class="card-body p-0">
      <div class="d-flex justify-content-end flex-row pb-2">
        <div class="min-width-zero m-2">
          <p class="mb-0 fs-16 text-end">${d.company_name ?? ""}</p>
        </div>
      </div>
      <div class="chat-text-start">
        <p class="text-end text-semi-muted">${d.entry_product_comment ?? ""}</p>
      </div>
    </div>
    <div class="pt-1">
      <span class="text-extra-small text-muted-custom">${d.operation_date ?? ""}</span>
    </div>
    <div class="clearfix"></div>
  </div>`;
  };

  const offeredPriceComponent = (d,i) => {
    return `<div class="card d-inline-block mb-3 float-end me-2 bg-primary max-w-p80 p-2">
    <div class="card-body p-0">
      <div class="d-flex justify-content-end flex-row pb-2">
        <div class="min-width-zero m-2">
          <p class="mb-0 fs-16 text-end">${d.entry_name ?? ""}</p>
        </div>
      </div>
      <div class="chat-text-start">
        <p class="text-end text-semi-muted">${lang("Company name")} : ${d.company_name ?? ""}</p>
        <p class="text-end text-semi-muted">${lang("Price offer")} :  ${d.price}</p>
      </div>
    </div>
    <div class="pt-1">
      <span class="text-extra-small text-muted-custom">${d.operation_date ?? ""}</span>
    </div>
    <div class="clearfix"></div>
  </div>`;
  };

  const initializeParameters = () => {
    keyword = $(`[data-role="search-keyword"]`).val().trim();
    // car_brand = $(`[data-role="select-car-brand"]`).val().trim();
    search_type = $(`[data-role="search-type"]:checked`).val();
    brand = $(`[data-role="select-brand"]`).val().trim();
    car_brand = $(`[data-role="select-car-brand"]`).val().trim();
    product_resource = $(`[data-role="select-product-resource"]`).val().trim();
    in_stock = $(`[data-role="check-in-stock"]`).is(":checked") ? "1" : "0";

    only_warehouse = $(`[data-role="only-warehouse"]`).is(":checked") ? "1" : "0";
    warehouse_id = $(`[data-role="select-warehouse"]`).val().trim();
    min_search_quantity = $(`[name="min_search_quantity"]`).val().trim();
    max_search_quantity = $(`[name="max_search_quantity"]`).val().trim();

    // in_details = $(`[data-role="in-details"]`).is(":checked") ? "1" : "0";
    is_dead_stock = $(`[data-role="is-dead-stock"]`).is(":checked") ? "1" : "0";
    is_deacreasing_stock = $(`[data-role="is-deacreasing-stock"]`).is(":checked") ? "1" : "0";

    if(is_dead_stock === "1") {
      dead_stock = $(`[data-role="dead-stock"]`).val().trim();
      if(!dead_stock.trim()) {
        dead_stock = 180;
        $(`[data-role="dead-stock"]`).val(dead_stock);
      }
    } else {
      dead_stock = "";
    }

    excel_export = 0;
  };
  let excel_export = 0;

  const clearTable = ({keyword, brand, in_stock, search_type, offset}) => {
    keyword = "";
    brand = "";
    car_brand = "";

    $(`[data-role="table-list"]`).html(warningComponent(lang("enter_filter_parameter")));
    $(`[data-role="content-result-count"]`).html(" 0 ");
    $(`[data-role="content-result-time"]`).html(" 0 ");
    $(`[data-role="table-loader"]`).addClass("d-none");

    $(`[data-role="apply-discount"]`).addClass("disabled");
    $(`[data-role="hide-price"]`).addClass("disabled");
  };



  let interval = null,
  loading = false,
  offset = 0;

  const getContent = (urlParams,first_time = true,cr_search = true) => {
    let html = "",
    start_time = null,
    count = 0;

    let {keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, discount_package_id, is_dead_stock, dead_stock, product_resource} = urlParams;

    if (excel_export) {
      ModalLoader.start(lang("Loading"));
    }
    if(first_time && !excel_export) {
      // console.log("here");
      offset = 0;
      $(`[data-role="content-result-count"]`).html("0");
      $(`[data-role="content-result-time"]`).html("0");

      $(`[data-role="tecdoc-crosses"]`).data("load", "1");

      filter_url([
        {keyword: (keyword || "")},
        {brand: (brand || "")},
        {car_brand: (car_brand || "")},
        {product_resource: (product_resource || "")},
        {search_type: (search_type || "")},
        // {filter: (filter || "")},
        {in_stock: +in_stock ? (in_stock || "") : ""},
        {only_warehouse: +only_warehouse ? (only_warehouse || "") : ""},
        {warehouse_id: (warehouse_id || "")},
        {max_search_quantity: (max_search_quantity || "")},
        {min_search_quantity: (min_search_quantity || "")},
        {discount_package_id: (discount_package_id || "")},
        {dead_stock: (dead_stock || "")},
        {is_dead_stock: +is_dead_stock ? (is_dead_stock || "") : ""},
      ]);

      if(typeof keyword === "number") {
        keyword = keyword.toString();
      }

      if(
        // (!keyword || !keyword.trim() || keyword.trim().length < 2) &&
        !keyword.trim() &&
        !brand.trim() &&
        !car_brand.trim() &&
        !product_resource.trim() &&
        !discount_package_id &&
        (!min_search_quantity && !max_search_quantity) &&
        (!warehouse_id.trim()) &&
        (is_dead_stock !== "1" && !dead_stock)
        ) {
        clearTable({keyword, brand, car_brand, offset});
        // Swal.fire("", keyword.trim().length < 2 ? lang("minimum_two_keyword_symbol") : lang("minimum_one_parameter"), "warning");
        Swal.fire("", lang("minimum_one_parameter"), "warning");
        // $(`[data-role="apply-discount"]`).addClass("disabled");
        return;
      }

      if((only_warehouse === "1" && !warehouse_id.trim())){
        clearTable({keyword, brand, car_brand, offset});
        Swal.fire("", lang("choose_a_warehouse"), "warning");
        return;
      }

      if(warehouse_id && (!min_search_quantity) ){
        clearTable({keyword, brand, car_brand, offset});
        Swal.fire("", lang("min_max_should_chooesed_for_warehouse"), "warning");
        // $(`[data-role="apply-discount"]`).addClass("disabled");
        return;
      }

      // if((max_search_quantity && !min_search_quantity) || (min_search_quantity && !max_search_quantity)) {
      //   clearTable({keyword, brand, car_brand, offset});
      //   Swal.fire("", lang("min_max_should_chooesed_for_quantity_search"), "warning");
      //   // $(`[data-role="apply-discount"]`).addClass("disabled");
      //   return;
      // }

      start_time = new Date().getTime();
      clearInterval(interval);
      interval = setInterval(function () {
        $(`[data-role="content-result-time"]`).html(
          (new Date().getTime() - start_time) / 1000
        );
      }, 100);

      ModalLoader.start(lang("Loading"));
    };


    cr_search = cr_search ? "1" : "0";
    $.get({
      url: `/products/list-live`,
      headers,
      data: {keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, discount_package_id,is_dead_stock, dead_stock, cr_search, product_resource},
      success: function (d) {
        // console.log(d);
        if (excel_export) {
          // console.log(d);
          if (d.code === 200) {
            url = d.data.url;
            location.href = url;
            excel_export = 0;
            getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, discount_package_id,is_dead_stock, dead_stock, product_resource});
          } else {
            Swal.fire(lang("Error"),"","warning");
          }
        } else {
        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;

          html = content_data.map((v, i) => trComponent(v, ++i, offset)).join("");
          if(first_time) {
            $(`[data-role="content-result-count"]`).html(d.data.count);
          }

          if(count > $(`[data-role="table-list"] tr`).length) {
            $(`[data-role="load-more-container"]`).removeClass("d-none");
            $(`[data-role="load-more-container"]`).addClass("loading");
          }
          $(`[data-role="apply-discount"]`).removeClass("disabled");
          $(`[data-role="hide-price"]`).removeClass("disabled");
        }
        else if (d.code === 204) {
          html = warningComponent(d.message);
          $(`[data-role="apply-discount"]`).addClass("disabled");
          $(`[data-role="hide-price"]`).addClass("disabled");
        }
        else {
          html = warningComponent(d.message);
          $(`[data-role="apply-discount"]`).addClass("disabled");
          $(`[data-role="hide-price"]`).addClass("disabled");
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
        // cartCount();

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

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find("button").on("blur", function() {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find('.spec-popover').each(function () {
          var $this = $(this);
            $this.popover({
                trigger: 'hover',
                content: 'Content Here',
                container: $this
            })
        });

        ModalLoader.end();
      },
    });
  };

  let filter_icon = $(`[data-role="filter-col-header"]`).find(`[data-role="filter-icon"]`),
  filter_name = filter_icon.closest(`[data-role="filter-col"]`).data("name"),
  filter_icon_class = filter_icon.attr("class");

  if(filter_icon_class && filter_icon_class.includes("up")) {
    filter_name = filter_name + "_asc";
  } else {
    filter_name = filter_name + "_down";
  }


  let keyword = getUrlParameter("keyword") || "",
        brand = getUrlParameter("brand") || "",
      car_brand = getUrlParameter("car_brand") || "",
      product_resource = getUrlParameter("product_resource") || "",
      in_stock = getUrlParameter("in_stock") || "",
      only_warehouse = getUrlParameter("only_warehouse") || "",
      warehouse_id = getUrlParameter("warehouse_id") || "",
      min_search_quantity = getUrlParameter("min_search_quantity") || "",
      max_search_quantity = getUrlParameter("max_search_quantity") || "",
      discount_package_id = getUrlParameter("discount_package_id") || "",
      is_dead_stock = getUrlParameter("is_dead_stock") || "",
      dead_stock = getUrlParameter("dead_stock") || "",
      filter = filter_name,
      search_type = getUrlParameter("search_type") || $(`[data-role="search-type"]:checked`).val();

  $(`
    [data-role="search-keyword"],
    [name="min_search_quantity"],
    [name="max_search_quantity"],
    [name="dead_stock"]
  `).on("keypress", function(e){
    if (e.which === 13) {
        initializeParameters();
        getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource});
      }
  });

  $(`[data-role="search-filter"]`).on("click", function(e){
    initializeParameters();
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource});
  });

  if (
      keyword.trim().length > 1 ||
      brand.trim() ||
      discount_package_id ||
      car_brand.trim() ||
      (min_search_quantity && max_search_quantity) ||
      (warehouse_id.trim() && min_search_quantity.trim() && max_search_quantity.trim())
    ) {
      getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource});
  };

  $(document).on("click", `[data-role="add-to-cart"]`, function(event) {

    let parent = $(this).parents("tr"),
    product_quantity = +parent.find(`[data-role="product-cart-count"]`).val(),
    product_id = parent.data("id"),
    product_resource = parent.data("resource"),
    product_sale_price = parent.data("sale-price"),
    product_name = parent.data("name"),
    product_brand_name = parent.data("brand-name"),
    product_brand_code = parent.data("brand-code"),
    product_OEM = parent.data("oem"),
    product_description = parent.data("description"),
    final_currency = parent.data("final-currency"),
    product_currency = parent.data("currency"),
    quant_input = parent.find(`[data-role="product-cart-count"]`),
    in_cart = parent.data("in-cart");

    let btn_id = $(this).attr("id"),
    btn = $(this);

    let data = {
      product_quantity,
      product_id,
      product_resource,
      product_sale_price,
      product_name,
      product_brand_name,
      product_brand_code,
      product_OEM,
      product_description,
      final_currency,
      product_currency,
    };

    AwesomeBtn.spin(btn);
    $.post({
      url: `/cart/add`,
      headers,
      data,
      // headers,
      success: function (d) {
        if (d.code === 202 || d.code === 201) {
          // cartCount();
          AwesomeBtn.success(btn);
          notify(d.code === 202 ? "warning" : "success", d.message);

          if(!quant_input.hasClass("product-in-cart")){
              quant_input.addClass("product-in-cart");
          }
          parent.data("in-cart", 1);
        } else {
          notify("warning", d.message);
          AwesomeBtn.warning(btn);
        }
      },
      error: function(d) {
        console.log(d);
        // Swal.fire("Error","","error");
        AwesomeBtn.error(btn);
      },
      complete: function(d) {
        AwesomeBtn.end(btn,2);
      }
    });
  });

  $(document).on("click", `[data-role="price-offers"]`, function(event) {
    let product_id = $(this).data("product-id");
    $("#addPriceOffer [name='product_id']").val(product_id);
    $(`[data-role="price-offer-list"]`).addClass("loading-box");
    deletePriceOffers();
    getOfferedPrices(product_id);
  })

  $(document).on("click", `[data-role="comment"]`, function(event) {
    let product_id = $(this).data("product-id");
    $("#addComment [name='product_id']").val(product_id);
    $(`[data-role="comment-list"]`).addClass("loading-box");
    deleteComments();
    getComments(product_id);
  })

  const deletePriceOffers = () => {
  $(`[data-role="price-offer-list"]`).html(""); // Clear the HTML content
  };

  const getOfferedPrices = (product_id) => {
    // $(`[data-role="comment-list"]`).addClass("loading-box");
    let html = "";
    $.get({
      url: `/products/search/offered-price-list`,
        headers,
        data: { product_id: product_id },
        success: function (d) {
          if (d.code === 200) {
              let content_data = d.data && d.data.list ? d.data.list : [];
              html = content_data.map((v, i) => offeredPriceComponent(v, ++i)).join("");
          } else if (d.code === 204) {
            html = "";
          }
          else {
            html = `<table class="wh-100"><tbody>${warningComponent(d.message)}</tbody></table>`;
          }
          $(`[data-role="price-offer-list"]`).html(html);
          $(`[data-role="price-offer-list"]`).removeClass("loading-box");
          $(`[data-role="price-offer-list"]`).scrollTop($(`[data-role="price-offer-list"]`)[0].scrollHeight);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
          // ModalLoader.end();
        },
      });
    };

  const deleteComments = () => {
    $(`[data-role="comment-list"]`).html(""); // Clear the HTML content
    };

  const getComments = (product_id) => {
      // $(`[data-role="comment-list"]`).addClass("loading-box");
      let html = "";
      $.get({
        url: `/products/search/comment-list`,
          headers,
          data: { product_id: product_id },
          success: function (d) {
            if (d.code === 200) {
                let content_data = d.data && d.data.list ? d.data.list : [];
                html = content_data.map((v, i) => commentComponent(v, ++i)).join("");
            } else if (d.code === 204) {
              html = "";
            }
            else {
              html = `<table class="wh-100"><tbody>${warningComponent(d.message)}</tbody></table>`;
            }
            $(`[data-role="comment-list"]`).html(html);
            $(`[data-role="comment-list"]`).removeClass("loading-box");
            $(`[data-role="comment-list"]`).scrollTop($(`[data-role="comment-list"]`)[0].scrollHeight);
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            // ModalLoader.end();
          },
        });
      };

  const getBrands = () => {
    // if (getStorage("product_brands")) {
    //   let content_data = JSON.parse(getStorage("bpm_product_brands"));
    //   let html = content_data.map((v, i) => `<option value="${v.name}" ${brand === v.name ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
    //   $(`[name="brands"]`).attr("disabled",false);
    //   $(`[name="brands"]`).html(`<option value="">${lang("All brands")}</option>`+html);
    //   return;
    // }
    $(`[name="brands"]`).parents(".form-group").addClass("loader");
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
            html = content_data.map((v, i) => `<option value="${v.name}" ${brand === v.name ? " selected" : ""}>${(v.name ? v.name : lang("without_brand"))}</option>` ).join("");
            $(`[name="brands"]`).attr("disabled",false);
          } else if (d.code === 204) {
            html = "";
          }
          else {
            html = "";
            Swal.fire("", d.message, "warning");
          }
          $(`[name="brands"]`).html(`<option value="">${lang("All brands")}</option>`+html);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
          $(`[name="brands"]`).parents(".form-group").removeClass("loader");
        },
      });
    };
    getBrands();

  const getCarBrands = () => {
    if (getStorage("product_car_brands")) {
      let content_data = JSON.parse(getStorage("bpm_product_car_brands"));
      let html = content_data.map((v, i) => `<option value="${v.id}" ${car_brand === v.id ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
      $(`[name="car_brands"]`).attr("disabled",false);
      $(`[name="car_brands"]`).html(`<option value="">${lang("All car brands")}</option>`+html);
      return;
    }
    $(`[name="car_brands"]`).parents(".form-group").addClass("loader");

    let html = "";
    $.get({
        url: `/products/properties/car-brands`,
        headers,
        success: function (d) {
          if (d.code === 200) {
            let content_data = d.data.list ?? [];
            if(content_data.length){
              setStorage("bpm_product_car_brands", JSON.stringify(content_data), 60 * 60 * 12);
            }
            html = content_data.map((v, i) => `<option value="${v.id}" ${car_brand === v.id ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
            $(`[name="car_brands"]`).attr("disabled",false);
          }
          else if (d.code === 204) {
            html = "";
          }
          else {
            html = "";
            Swal.fire("", d.message, "warning");
          }
          $(`[name="car_brands"]`).html(`<option value="">${lang("All car brands")}</option>`+html);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
          $(`[name="car_brands"]`).parents(".form-group").removeClass("loader");
        },
      });
  };
  getCarBrands();

  const getWarehouses = () => {
  // if (getStorage("product_warehouses_list")) {
  //     let content_data = JSON.parse(getStorage("product_warehouses_list"));
  //     let html = content_data.map((v, i) => `<option value="${v.id}"${warehouse_id === v.id ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
  //     $(`[name="warehouse_id"]`).attr("disabled",false);
  //     $(`[name="warehouse_id"]`).html(`<option value="">${lang("all_warehouses")}</option>`+html);
  //     return;
  //   }

    $(`[name="warehouse_id"]`).parents(".form-group").addClass("loader");
    $.get({
      url: `/warehouses`,
      headers,
      success: function (d) {
        if (d.code === 200) {
          let content_data = d.data ?? [];
          if(content_data.length){
            setStorage("product_warehouses_list", JSON.stringify(content_data), 60 * 60 * 12);
          }

          html = content_data.map((v, i) => `<option value="${v.id}"${warehouse_id === v.id ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
          $(`[name="warehouse_id"]`).attr("disabled",false);
        }
        else if (d.code === 204) {
          html = "";
        }
        else {
          html = "";
          Swal.fire("", d.message, "warning");
        }
        $(`[name="warehouse_id"]`).html(`<option value="">${lang("all_warehouses")}</option>`+html);
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        $(`[name="warehouse_id"]`).parents(".form-group").removeClass("loader");
      },
    });
  };

  getWarehouses();

  const searchMinMaxQuantityValidations = () => {
    let min_search_quantity = $(`[name="min_search_quantity"]`).val(),
     max_search_quantity = $(`[name="max_search_quantity"]`).val();

    if($(`[data-role="select-warehouse"]`).val() && !min_search_quantity.trim() && !max_search_quantity.trim()){
      $(`[name="min_search_quantity"]`).addClass("is-invalid");
      $(`[name="max_search_quantity"]`).addClass("is-invalid");
    } else {
      $(`[name="min_search_quantity"]`).removeClass("is-invalid");
      $(`[name="max_search_quantity"]`).removeClass("is-invalid");
    }
  }
  searchMinMaxQuantityValidations();

  $(`[data-role="select-warehouse"]`).on("change", function(){
      searchMinMaxQuantityValidations();
    });

    $(`[name="min_search_quantity"]`).on("change", function(){
    let min_search_quantity = $(`[name="min_search_quantity"]`).val(),
     max_search_quantity = $(`[name="max_search_quantity"]`).val();

    // if(min_search_quantity.trim() && !max_search_quantity.trim()){
    //   $(`[name="max_search_quantity"]`).val(min_search_quantity);
    // }

    searchMinMaxQuantityValidations();
  });

  $(`[name="max_search_quantity"]`).on("keyup", function(){
    let max_search_quantity = $(`[name="max_search_quantity"]`).val(),
     min_search_quantity = $(`[name="min_search_quantity"]`).val();

    if(max_search_quantity.trim() && !min_search_quantity.trim()){
      $(`[name="min_search_quantity"]`).val("0");
    }

    searchMinMaxQuantityValidations();
  });

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

  const updateSearchType = () => {
    let search_type = $(`[data-role="search-type"]:checked`).val();
    filter_url([
      {search_type: (search_type || "")},
    ]);

    $.ajax({
        type: "put",
        url: `/products/search/update/type`,
        data: JSON.stringify({search_type}),
        headers,
        success: function(d){
          // if (d.code === 202) {
          //
          // }
        },
        error: function(e){
          console.error(e)
        },
        complete: function(){

        }
      });
  }

  $(document).on("change",`[name="search_type"]`,function(){
      updateSearchType();
  });

  $(`[data-role="header-search-input"]`).on("keypress", function(e) {

    if (e.which === 13) {
      e.preventDefault();
      initializeParameters();
      keyword = $(`[data-role="header-search-input"]`).val();
      getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource});
    }
  });

  $(document).on("scroll", function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource},false);
  });

  $(document).on("click",`[data-role="load-more-container"]`,function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource},false);
  });

  $(document).on("click", `[data-search="code"]`, function(){
    // clearValues({keyword, brand, in_stock, search_type, offset});
    keyword = $(this).data("code");
    brand = "";
    $(`[data-role="search-keyword"]`).val(keyword);
    filter_url([
      {keyword: (keyword || "")},
      {brand: (brand || "")},
    ]);
    initializeParameters();
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource});
  });

  $(document).on("click",`[data-role="excel-export"]`,function(){
    if(
      !keyword.trim() &&
      !brand.trim() &&
      !car_brand.trim() &&
      !product_resource.trim() &&
      !discount_package_id &&
      (!min_search_quantity && !max_search_quantity) &&
      (!warehouse_id.trim()) &&
      (is_dead_stock !== "1" && !dead_stock)
      ) {
      Swal.fire("", lang("minimum_one_parameter"), "warning");
      return;
    }

    if((only_warehouse === "1" && !warehouse_id.trim())){
      clearTable({keyword, brand, car_brand, offset});
      Swal.fire("", lang("choose_a_warehouse"), "warning");
      return;
    }

    if(warehouse_id && (!min_search_quantity) ){
      clearTable({keyword, brand, car_brand, offset});
      Swal.fire("", lang("min_max_should_chooesed_for_warehouse"), "warning");
      return;
    }

    initializeParameters();
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset: 0, excel_export: 1, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, discount_package_id, product_resource});
  });

  const flipFilterIcon = (icon) => {
    let icon_class = icon.attr("class");

    let filter = icon.closest(`[data-role="filter-col"]`).data("name");
    if(icon_class.includes("up")) {
      icon_class = "fa-solid fa-arrow-down-wide-short sort";
      icon.attr("class",  icon_class)
      filter = filter + "_desc";
    } else {
      icon_class ="fa-solid fa-arrow-up-short-wide sort";
      icon.attr("class",  icon_class);
      filter = filter + "_asc";
    }

    return filter;
  };

  $(`[data-role="filter-col"]`).on("click", function(){
    let icon = $(this).find(`[data-role="filter-icon"]`);
    if(icon.length){
      let filter = flipFilterIcon(icon);
      getContent({keyword, brand, car_brand, in_stock, search_type, filter});
      return;
    }

    $(document).find(`[data-role="filter-icon"]`).remove();
    let filter = $(this).data("name");

    filter = filter + "_desc";
    $(this).append(`
      <i data-role="filter-icon" class="fa-solid fa-arrow-down-wide-short sort"></i>
    `);
    console.log(filter);
    getContent({keyword, brand, car_brand, in_stock, search_type, filter});
  });


  $(document).on("change", `[data-role="price"]`, function() {
    let el = $(this),
        id = el.parents("tr").data("id"),
        previous_custom_main_sale_price = el.data("price"),
        custom_main_sale_price = el.val();
        // custom_sale_price = null,
        // custom_main_sale_price = null,
        // eur_value = $(`[data-role="header-currency"]`).find(`[data-name="EUR"]`).data("val");

    // if(el.data("name") === "custom-sale-price") {
    //   custom_main_sale_price = price * eur_value;
    //   el.parents("tr").find(`[data-name="custom-converted-price"]`).val(custom_main_sale_price);
    // }
    // if(el.data("name") === "custom-converted-price") {
    //   custom_sale_price = price / eur_value;
    //   el.parents("tr").find(`[data-name="custom-sale-price"]`).val(custom_sale_price);
    // }

    let data = {id, custom_main_sale_price};

    disableAll(true);
    $.ajax({
      url: `/products/${id}/edit-price`,
      method: "PUT",
      headers,
      data: JSON.stringify(data),
      success: function(d) {
        // console.log(d);
        if(d.code === 202) {
          el.val(custom_main_sale_price);
          el.data("price", custom_main_sale_price);
          notify("success", d.message);
        } else {
          el.val(previous_custom_main_sale_price);
          el.data("price", previous_custom_main_sale_price);
          notify("warning", d.message);
        }
      },
      error: function(d) {
        el.val(previous_custom_main_sale_price);
        el.data("price", previous_custom_main_sale_price)
      },
      complete: function() {
        disableAll(false);
      }
    });
  });

  $(document).on("change", `[data-role="is-discount"]`, function(){
    let id = $(this).parents("tr").data("id"),
        discount_price = $(this).parents("tr").find(`[data-role="discount-price"]`).val(),
        has_discount = $(this).is(":checked") ? 1 : 0;

    disableAll(true);
    $.ajax({
      url: `/products/${id}/discount-price`,
      method: "PUT",
      headers,
      data: JSON.stringify({discount_price, has_discount}),
      success: function(d) {
        console.log(d);
        if(d.code === 202) {
          notify("success", d.message);
        } else {
          notify("warning", d.message);
        }
      },
      error: function(d) {
        console.log(d);
      },
      complete: function() {
        disableAll(false);
      }
    });
  });

  $(document).on("click", `[data-role="apply-discount"]`, function(){
    let data = {
      "filter": filter,
      "brand": brand,
      "keyword":keyword,
      "car_brand": car_brand,
      "in_stock": in_stock,
      "search_type": search_type,
      "offset": offset,
      "excel_export": excel_export,
      "only_warehouse": only_warehouse,
      "warehouse_id": warehouse_id,
      "min_search_quantity": min_search_quantity,
      "max_search_quantity": max_search_quantity,
      "is_dead_stock": is_dead_stock,
      "dead_stock": dead_stock,
      "product_resource": product_resource,
      "apply_discount" : "1"
    };

    Swal.fire({
      title: lang("Apply discount"),
      html: `<div class="input-group">
              <input autocomplete="off" type="text" data-role="name" class="form-control" placeholder="${lang("Discount package name")}" value="">
             </div>`,
      showCancelButton: true,
      confirmButtonText: lang("Save"),
      cancelButtonText: lang("Cancel"),
      confirmButtonColor: "#399CE3",
      reverseButtons: true
    }).then((res) => {
      if (res.isConfirmed) {
        data.name = $(`[data-role="name"]`).val();
        ModalLoader.start(lang("Loading") + "...");
        disableAll(true);
        $.ajax({
          method: "PUT",
          url: `/products/apply-discount`,
          data: JSON.stringify(data),
          headers,
          success: function(d) {
            // console.log(d);
            if(d.code === 202) {
              notify("success", d.message);
              getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, product_resource});
            } else {
              notify("warning", d.message);
            }
          },
          error: function(d) {
            console.log(d);
          },
          complete: function() {
            disableAll(false);
            ModalLoader.end();
          }
        });
      }
    })
  });

  let discount_price_in = 0;
  $(document).on("focusin",`[data-role="discount-price"]`, function(){
      discount_price_in = $(this).val().trim();
  });

  $(document).on("focusout", `[data-role="discount-price"]`, function() {
    if(discount_price_in === $(this).val().trim()) return;

    let id = $(this).parents("tr").data("id"),
        has_discount = $(this).parents("tr").find(`[data-role="is-discount"]`).is(":checked") ? 1 : 0;
        discount_price = $(this).val().trim();

    if($(this).parents("tr").find(`[data-role="is-discount"]`).is(":checked")){
      disableAll(true);
      $.ajax({
        method: "PUT",
        url: `/products/${id}/discount-price`,
        data: JSON.stringify({discount_price, has_discount}),
        headers,
        success: function(d){
          // console.log(d)
          if(d.code === 202){
            notify("success", d.message);
          } else{
            console.log(d);
            notify("warning", d.message);
          }
        },
        error: function(d){
          console.error(d);
        },
        complete: function(){
          disableAll(false);
        }
      });
    }
  });

  $(document).on("change", `[data-role="is-b4b-price-hidden"]`, function(){
    let id = $(this).parents("tr").data("id"),
        is_b4b_price_hidden = $(this).is(":checked") ? 1 : 0;

    disableAll(true);
    $.ajax({
      url: `/products/${id}/hide-price`,
      method: "PUT",
      headers,
      data: JSON.stringify({is_b4b_price_hidden}),
      success: function(d) {
        // console.log(d);
        if(d.code === 202) {
          notify("success", d.message);
        } else {
          notify("warning", d.message);
        }
      },
      error: function(d) {
        console.log(d);
      },
      complete: function() {
        disableAll(false);
      }
    });
  });


  $(document).on("click", `[data-role="hide-price"]`, function(){
    let data = {
      "filter": filter,
      "brand": brand,
      "keyword":keyword,
      "car_brand": car_brand,
      "in_stock": in_stock,
      "search_type": search_type,
      "offset": offset,
      "excel_export": excel_export,
      "only_warehouse": only_warehouse,
      "warehouse_id": warehouse_id,
      "min_search_quantity": min_search_quantity,
      "max_search_quantity": max_search_quantity,
      "is_dead_stock": is_dead_stock,
      "dead_stock": dead_stock,
      "product_resource": product_resource,
      "hide_price" : "1"
    };
    disableAll(true);
    Swal.fire({
      title: lang("Are you sure to hide prices?"),
      showCancelButton: true,
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      confirmButtonColor: "#399CE3",
      reverseButtons: true
    }).then((res) => {
      if (res.isConfirmed) {
        data.name = $(`[data-role="name"]`).val();
        ModalLoader.start(lang("Loading") + "...");
        disableAll(true);
        $.ajax({
          method: "PUT",
          url: `/products/hide-prices`,
          data: JSON.stringify(data),
          headers,
          success: function(d) {
            // console.log(d);
            if(d.code === 202) {
              notify("success", d.message);
              getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, product_resource});
            } else {
              notify("warning", d.message);
            }
          },
          error: function(d) {
            console.log(d);
          },
          complete: function() {
            disableAll(false);
            ModalLoader.end();
          }
        });
      }
    })
  });

  let add_modal = '[data-role="is-new-from-warehouse-parent"]';

  $(document).on("click", `[data-role="is-new-from-warehouse"]`, function() {
    let el = $(this),
        id = el.parents("tr").data("id");

    let is_new_from_warehouse_val = $(this).is(":checked") ? "1" : "0";

    let date = new Date(),
          currentDate = date.toISOString().substring(0,10),
          nextDate = new Date(date.getFullYear(), date.getMonth() + 1, date.getDay()).toISOString().substring(0,10);

    if(el.data("start-date").trim()) {
      currentDate = el.data("start-date");
    }


    if(el.data("end-date").trim()) {
      nextDate = el.data("end-date");
    }

    Swal.fire({
      title: lang('Are you sure edit is new from warehouse product'),
      icon: "info",
      html:
      `<div data-role="is-new-from-warehouse-parent" >
      <div class="form-group d-flex align-items-center">
          <label class="me-2">${lang("Is new from warehouse")}</label>
          <input
          style="width:23px;height:23px;"
          type="checkbox"
          data-role="is-new-from-warehouse-val"
          name="is_new_from_warehouse" ${is_new_from_warehouse_val ? "checked" : ""}
           >
        </div>
        <div class="form-group">
          <label>${lang("Start date")}</label>
          <input autocomplete="off" type="date" name="new_from_warehouse_start_date" class="form-control" value="${currentDate}">
        </div>
        <div class="form-group">
          <label>${lang("End date")}</label>
          <input autocomplete="off" type="date" name="new_from_warehouse_end_date" class="form-control" value="${nextDate}">
        </div>
        <div class="form-group">
        <label>${lang("Image upload")}</label>
        <input type="file" name="new_from_warehouse_image" class="form-control" data-value="">
        </div>
        </div>
        `,
      showCancelButton: true,
      focusConfirm: false,
      reverseButtons: true,
      confirmButtonText: lang("Confirm"),
      cancelButtonText: lang("Cancel"),
    }).then((d) => {
      if(d.isConfirmed) {
        let data = {
          is_new_from_warehouse: $(document).find(`[data-role="is-new-from-warehouse-parent"]`).find(`[name="is_new_from_warehouse"]`).is(":checked") ? "1" : "0",
          new_from_warehouse_start_date: $(document).find(`[data-role="is-new-from-warehouse-parent"]`).find(`[name="new_from_warehouse_start_date"]`).val(),
          new_from_warehouse_end_date: $(document).find(`[data-role="is-new-from-warehouse-parent"]`).find(`[name="new_from_warehouse_end_date"]`).val(),
          new_from_warehouse_image: $(document).find(`[data-role="is-new-from-warehouse-parent"]`).find(`[name="new_from_warehouse_image"]`).attr("data-value"),
        };

        ModalLoader.start(lang("Loading"));

        $.ajax({
          url: `/products/${id}/edit-is-new-from-warehouse`,
          data: JSON.stringify(data),
          headers,
          method: "PUT",
          success: function(d) {
            if(d.code === 202) {
              Swal.fire(d.message, "", "success");
              getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, only_warehouse, warehouse_id, min_search_quantity, max_search_quantity, is_dead_stock, dead_stock, product_resource});
            } else {
              Swal.fire("", d.message, "warning");
            }
          },
          error: function(d){
            console.error(d);
          },
          complete: function(d) {
            ModalLoader.end();
            // ModalCacheLoader.end();
          }
        });

      } else {
        el.prop("checked",!(+is_new_from_warehouse_val));
      }
    });
  });

  let modals = [add_modal];
  modals.map(v => {
    $(document).on("change",`${v} [name="new_from_warehouse_image"]`,function(e){
      let th = $(this);
      let input = e.target;
      th.parents(".form-group").addClass("loader");
      let val = getBase64(input.files[0]).then(function(res){
        $(`${v} [name="new_from_warehouse_image"]`).attr("data-value",res);
      }).finally(function(){
        th.parents(".form-group").removeClass("loader");
      });
    });
  });

  const getProductResources = () => {
    $(`[name="product_resources"]`).parents(".form-group").addClass("loader");

    let html = "";
    $.get({
        url: `/products/properties/product-resources`,
        headers,
        success: function (d) {
          // console.log(d);
          if (d.code === 200) {
            let content_data = d.data ?? [];
            html = content_data.map((v, i) => `<option value="${v}" ${product_resource === v ? " selected" : ""}>${(v ?? "")}</option>` ).join("");
            $(`[name="product_resources"]`).attr("disabled",false);
          }
          else if (d.code === 204) {
            html = "";
          }
          else {
            html = "";
            Swal.fire("", d.message, "warning");
          }
          $(`[name="product_resources"]`).html(`<option value="">${lang("All product resources")}</option>`+html);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
          $(`[name="product_resources"]`).parents(".form-group").removeClass("loader");
        },
      });
  };
  getProductResources();

});
