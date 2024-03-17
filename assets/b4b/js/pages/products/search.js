"use-strict";
$(function(){
  $(`[data-role="products-carousel"]`).owlCarousel({
      dots: false,
      nav: false,
      loop: false,
      autoplay: true,
      autoplayTimeout: 2500,
      autoplayHoverPause: true,
      responsive: {
        0 : {
          items: 1,
        },
        480 : {
          items: 2,
        },
        768 : {
          items: 3,
        },
        991 : {
          items: 4,
        }
      }
  });

  // const lang = $(`[data-role="main-title"]`).data("lang");
  const current_account_currency = $(`[data-role="current-account-currency"]`).data("currency");

  // if(current_account_currency === "AZN") {
  //   $(`[data-role="filter-col"][data-name="price_eur"]`).addClass("d-none");
  // }

  // <span data-role="oem" data-search="code" class="link me-1" data-code="${d.OEM}" >${keyword && d.OEM ? highlightLabel(d.OEM,keyword) : (d.OEM ?? "")}</span>

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
              data-sale-price="${d.has_discount ? d.discount_price : d.sale_price}"
              data-main-price="${d.main_price ?? ""}"
              data-final-currency="${d.currency ?? ""}"
              data-cart-id="${d.cart_id ?? ""}"
              data-currency="${d.currency ?? ""}"
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
      <td class="text-${d.stock_baku.class}" >${d.stock_baku.icon ?
          `<span class="badge badge-${d.stock_baku.class}">${d.stock_baku.title}</span>` :
          d.stock_baku.quantity ?? "0"}
      </td>
      <td class="text-${d.stock_baku_2.class}" >${d.stock_baku_2.icon ?
          `<span class="badge badge-${d.stock_baku_2.class}">${d.stock_baku_2.title}</span>` :
          d.stock_baku_2.quantity ?? "0"}
      </td>
      <td class="text-${d.stock_ganja.class}" >${d.stock_ganja.icon ?
          `<span class="badge badge-${d.stock_ganja.class}">${d.stock_ganja.title}</span>` :
          d.stock_ganja.quantity ?? "0"}
      </td>
      <td>
        <div class="input-group num-input-parent">
          <input data-role="product-cart-count"
          data-min="1"
          value="${d.in_cart || 1}"
          type="number"
          class="form-control ${d.in_cart > 0 ? 'product-in-cart' : ''} num-input"
          step="1"

          >
          <div class="input-group-prepend">
            <button
                id="add_to_cart_${++i}"
                data-role="add-to-cart"
                data-toggle="tooltip"
                data-placement="left"
                class="btn btn-success btn-icon cart-btn"
                data-original-title="${lang("add_to_cart")}"
                ${(current_account_currency !== d.currency || !d.add_to_cart) ? " disabled readonly" : ""}
                >
              <i class="fa-solid fa-cart-shopping"></i>
            </button>
          </div>
        </div>
      </td>
      <td>${d.delivery_time ?? ""}</td>
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
      <td>
       <div class="d-flex justify-content-end">
         <button
           data-bs-toggle="modal"
           data-bs-target="#addPriceOffer"
           type="button"
           data-role="open-add-modal"
           data-product-id="${d.id ?? ""}"
           class="btn btn-primary ms-2"
         >
           <i class="fas fa-tags"></i>
         </button>
       </div>
     </td>
    </tr>`;
  };

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

  const commentComponent = (d,i) => {
    return `<div class="card d-inline-block mb-3 float-end me-2 bg-primary max-w-p80 p-2">
    <div class="card-body p-0">
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

  const initializeParameters = () => {
    keyword = $(`[data-role="search-keyword"]`).val().trim();
    // car_brand = $(`[data-role="select-car-brand"]`).val().trim();
    search_type = $(`[data-role="search-type"]:checked`).val();
    brand = $(`[data-role="select-brand"]`).val().trim();
    car_brand = $(`[data-role="select-car-brand"]`).val().trim();
    in_stock = $(`[data-role="check-in-stock"]`).is(":checked") ? "1" : "0";
    show_discount = $(`[data-role="show-discount"]`).is(":checked") ? "1" : "0";
    excel_export = 0;
  };
  let excel_export = 0;

  const clearTable = ({keyword, brand, in_stock, search_type, offset, show_discount}) => {
    keyword = "";
    brand = "";
    car_brand = "";

    $(`[data-role="table-list"]`).html(warningComponent(lang("enter_filter_parameter")));
    $(`[data-role="content-result-count"]`).html(" 0 ");
    $(`[data-role="content-result-time"]`).html(" 0 ");
    $(`[data-role="table-loader"]`).addClass("d-none");
  };



  let interval = null,
  loading = false,
  offset = 0;

  const getContent = (urlParams,first_time = true,cr_search = true) => {
    let html = "",
    start_time = null,
    count = 0;

    let {keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount} = urlParams;

    if (!keyword && brand) {
      $(`[data-role="excel-export"]`).removeClass("d-none");
    } else {
      $(`[data-role="excel-export"]`).addClass("d-none");
    }
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
        {search_type: (search_type || "")},
        {filter: (filter || "")},
        {in_stock: +in_stock ? (in_stock || "") : ""},
        {show_discount: (show_discount || "")},
      ]);

      if(typeof keyword === "number") {
        keyword = keyword.toString();
      }
      // console.log(keyword);
      if(( !keyword || !keyword.trim() || keyword.trim().length < 2) && !brand.trim() && !car_brand.trim()) {
        clearTable({keyword, brand, in_stock, search_type, offset});
        Swal.fire("", keyword.trim().length < 2 ? lang("minimum_two_keyword_symbol") : lang("minimum_one_parameter"), "warning");
        return;
      }

      if($(`[data-role="tecdoc-crosses"]`).find(`[data-role="active-link"]`).hasClass("active")) {
        getTecDocCrosses({keyword});
      }

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
      data: {keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, cr_search, show_discount},
      success: function (d) {
        // console.log(d);
        if (excel_export) {
          if (d.code === 200) {
            url = d.data.url;
            location.href = url;
            excel_export = 0;
            getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount});
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
        }
        else if (d.code === 204) {
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
      }
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        cartCount();

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
    in_stock = getUrlParameter("in_stock") || "",
    show_discount = getUrlParameter("show_discount") || "",
    filter = filter_name,
    search_type = getUrlParameter("search_type") || $(`[data-role="search-type"]:checked`).val();


  $(`[data-role="search-keyword"]`).on("keypress", function(e){
    initializeParameters();
    if (e.which === 13) {
        getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount});
      }
  });

  $(`[data-role="search-filter"]`).on("click", function(e){
    initializeParameters();
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount});
  });

  if (keyword.trim().length > 1 || brand.trim() || car_brand.trim()) {
      getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount});
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
    cart_id = parent.data("cart-id"),
    final_currency = parent.data("final-currency"),
    product_currency = parent.data("currency"),
    quant_input = parent.find(`[data-role="product-cart-count"]`),
    in_cart = parent.data("in-cart");

    let btn_id = $(this).attr("id"),
    btn = $(this);

    if(current_account_currency !== final_currency) {
      // Swal.fire("", lang("for_adding_this_product_you_should_choose_" + final_currency), "warning"); return;
    }

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
      product_currency,
      final_currency,
      cart_id,
    };

    AwesomeBtn.spin(btn);
    $.post({
      url: `/cart/add`,
      headers,
      data,
      // headers,
      success: function (d) {
        if (d.code === 202 || d.code === 201) {

          if(d.code === 201) {
            let cart_id = d.data && d.data.cart_id ? d.data.cart_id : NULL;
            parent.data("cart-id", cart_id);
          }
          cartCount();
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

  $(document).on("keyup", `[data-role="comment-input"]`, function(event){
    let comment = $(this).val();
    if (comment && comment.trim()) {
      $('[data-role="add-comment"]').prop('disabled', false);
      $('[data-role="add-comment"]').removeClass('disabled');
    }else {
      $('[data-role="add-comment"]').prop('disabled', true);
    }
  });

  $(document).on("click", `[data-role="comment"]`, function(event) {
    let product_id = $(this).data("product-id");
    $("#addComment [name='product_id']").val(product_id);
    $(`[data-role="comment-list"]`).addClass("loading-box");

    getComments(product_id);
  });

  $(document).on("click", `[data-role="add-comment"]`, function(event) {
    let product_id = $("#addComment [name='product_id']").val(),
        commentField = $(this).closest(".modal-content").find('[name="comment"]');

    let data = {
      entry_product_comment: commentField.val(),
      product_id: product_id
    };

    let html = "";

    Loader.btn({
      element: event.target,
      action: "start"
    });
    $.post({
      url: `/products/search/add-comments`,
      headers,
      data,
      success: function (d) {
        if (d.code === 201) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          html = commentComponent(content_data);
        } else {
          notify("warning", d.message);
        }
        $('[data-role="add-comment"]').addClass('disabled');
        $(`[data-name="comment"]`).val(' ');
        $(`[data-role="comment-list"]`).append(html);
        $(`[data-role="comment-list"]`).stop().animate({
          scrollTop: $(`[data-role="comment-list"]`)[0].scrollHeight
        }, 800);
        Loader.btn({
          element: event.target,
          action: d.code === 201 ? "success" : "warning"
        });
      },
      error: function(d) {
        console.log(d);
        Loader.btn({
          element: event.target,
          action: "error"
        });
      },
      complete: function(d) {
        Loader.btn({
          element: event.target,
          action: "end"
        });
      }
    });
  });

  $(document).on('keypress',  function (e) {
    if($('#addComment').is(':visible')) {
      var key = e.which;
      if (key == 13) {
        e.preventDefault();
        $(`[data-role="add-comment"]`).click();
      }
    }
  });

  $(document).on("click", `[data-role="open-add-modal"]`, function(event) {
    let product_id = $(this).data("product-id");
    // console.log(product_id);
    $("#addPriceOffer [name='product_id']").val(product_id);
  });

  $(document).on("click",`[data-role="add-price-offer-button"]`,function(){
    let product_id = $("#addPriceOffer [name='product_id']").val(),
        parent = $(`#addPriceOffer`);

    let data = {
      company_name: parent.find(`[name="company-name"]`).val(),
      price_offer: parent.find(`[name="price-offer"]`).val(),
      product_id: product_id
    };

    customLoader();

    $.post({
      url: `/products/search/add-price-offer`,
      data,
      headers,
      success: function(d){
        if (d.code === 201) {
          parent.modal("hide");
          parent.find(`[name="company-name"],[name="price-offer"]`).val("")
          Swal.fire("",d.message,"success")
        } else {
          Swal.fire("",d.message,"warning")
        }
        $('[data-role="add-price-offer-button"]').addClass('disabled');
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        customLoader(true);
      }
    })

  })

  $(document).on("keyup", `[data-role="company-input"], [data-role="price-input"]`, function() {
    let company_name = $('[name="company-name"]').val(),
        price_offer = $('[name="price-offer"]').val(),
        saveButton = $(`[data-role="add-price-offer-button"]`);

    if (company_name && company_name.trim() && price_offer && price_offer.trim() ) {
      saveButton.prop('disabled', false);
      saveButton.removeClass('disabled');
    }else {
      saveButton.prop('disabled', true);
    }
  });


  const getComments = (product_id) => {
    $(`[data-role="comment-list"]`).addClass("loading-box");
    let html = "";
    $.get({
      url: `/products/search/comments`,
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
              html = "";
            }
            $(`[data-role="comment-list"]`).html(html);
            $(`[data-role="comment-list"]`).removeClass("loading-box");
            $(`[data-role="comment-list"]`).scrollTop($(`[data-role="comment-list"]`)[0].scrollHeight);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
        },
      });
    };

  const getBrands = () => {
    // if (getStorage("product_brands")) {
    //   let content_data = JSON.parse(getStorage("product_brands"));
    //   let html = content_data.map((v, i) => `<option value="${v.name}" ${brand === v.name ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
    //   $(`[name="brands"]`).attr("disabled",false);
    //   $(`[name="brands"]`).html(`<option value="">${lang("Choose brand")}</option>`+html);
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
            // setStorage("product_brands", JSON.stringify(content_data), 60 * 60 * 12);
            html = content_data.map((v, i) => `<option value="${v.id}" ${brand === v.id ? " selected" : ""}>${(v.name ? v.name : lang("without_brand"))}</option>` ).join("");
            $(`[name="brands"]`).attr("disabled",false);
          } else if (d.code === 204) {
            html = "";
          }
          else {
            html = "";
            Swal.fire("", d.message, "warning");
          }
          $(`[name="brands"]`).html(`<option value="">${lang("Choose brand")}</option>`+html);
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
    // if (getStorage("product_car_brands")) {
    //   let content_data = JSON.parse(getStorage("product_car_brands"));
    //   let html = content_data.map((v, i) => `<option value="${v.id}" ${car_brand === v.id ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
    //   $(`[name="car_brands"]`).attr("disabled",false);
    //   $(`[name="car_brands"]`).html(`<option value="">${lang("Choose car brand")}</option>`+html);
    //   return;
    // }
    $(`[name="car_brands"]`).parents(".form-group").addClass("loader");

    let html = "";
    $.get({
        url: `/products/properties/car-brands`,
        headers,
        success: function (d) {
          if (d.code === 200) {
            let content_data = d.data.list || [];
            // setStorage("product_car_brands", JSON.stringify(content_data), 60 * 60 * 12);
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
          $(`[name="car_brands"]`).html(`<option value="">${lang("all_car_brands")}</option>`+html);
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
      getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount});
    }
  });

  $(`[data-role="tecdoc-crosses"]`).on("click", function() {
    let load = +$(`[data-role="tecdoc-crosses"]`).data("load");
    if(!load){
      return;
    }
    getTecDocCrosses({keyword});
  });

  const getTecDocCrosses = ({keyword}) => {
        if(!keyword.trim()) { return;}
    let start_time = new Date().getTime(),
    cross_codes_html = "",
    row_count = 0;

    clearInterval(interval);
    interval = setInterval(function () {
      $(`[data-role="content-result-time"]`).html(
        (new Date().getTime() - start_time) / 1000
      );
    }, 100);


    ModalLoader.start(lang("Loading"));
    $.ajax({
      url: `/products/search/tecdoc-crosses`,
      data: {keyword},
      headers,
      success: function(d){
        if (d.code === 200) {
          let tecdoc_codes_data = d.data && d.data.list ? d.data.list : [],
          counts = d.data && d.data.counts ? d.data.counts : [];

          cross_codes_html = Object.entries(tecdoc_codes_data).map((v, i) => crossCodeTrComponent(v, ++i)).join("");
          $(`[data-role="content-cross-brand-result-count"]`).html(d.data.counts.brands);
          $(`[data-role="content-cross-code-result-count"]`).html(d.data.counts.codes);

        } else if (d.code === 204) {
          html = warningComponent(d.message);
        } else {
          html = warningComponent(d.message);
          console.log(d);
        }

        $(`[data-role="table-cross-list"]`).html(cross_codes_html);
      },
      error: function(d){

      },
      complete: function(d){
        ModalLoader.end();
        clearInterval(interval);
        $(`[data-role="content-cross-result-time"]`).html(
          ((new Date().getTime() - start_time) / 1000)
        );
        $(`[data-role="tecdoc-crosses"]`).data("load", "0");
      },
    });
  };


  $(document).on("scroll", function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount},false);
  });

  $(document).on("click",`[data-role="load-more-container"]`,function(){
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] tr`).length;
    loading = true;
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount},false);
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
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset, excel_export, show_discount},true);
  });


  $('[data-fancybox]').fancybox({
    toolbar: true,
    // smallBtn: true,
    iframe: {
      preload: false
    },
    // fullScreen: {
    //   autoStart: true
    // },
  });

  $(document).on("click",`[data-role="excel-export"]`,function(){
    initializeParameters();
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, offset: 0, excel_export: 1, show_discount});
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
      getContent({keyword, brand, car_brand, in_stock, search_type, filter, show_discount});
      return;
    }

    $(document).find(`[data-role="filter-icon"]`).remove();
    let filter = $(this).data("name");

    filter = filter + "_desc";
    $(this).append(`
      <i data-role="filter-icon" class="fa-solid fa-arrow-down-wide-short sort"></i>
    `);
    console.log(filter);
    getContent({keyword, brand, car_brand, in_stock, search_type, filter, show_discount});
  });

});
