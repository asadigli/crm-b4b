"use strict";
$(function(){

  const current_account_currency = $(`[data-role="current-account-currency"]`).data("currency");
  const show_eur = current_account_currency === "EUR";

  const trComponent = (d, i) => {

    // if(d.product.resource === '0x001' && d.product.stock_baku.quantity) {
    //   stocks_btns.baku = true;
    //   stocks_btns.all = true;
    // }
    //
    // if(d.product.resource === '0x001' && d.product.stock_ganja.quantity) {
    //   stocks_btns.ganja = true;
    //   stocks_btns.all = true;
    // }
    //
    // if(d.product.resource === '0x002') {
    //   stocks_btns.backorder = true;
    //   stocks_btns.all = true;
    // }
    //
    // if(d.product.resource === '0x003') {
    //   stocks_btns.backorder = true;
    //   stocks_btns.all = true;
    // }

    return `<tr
              data-resource="${d.product.resource}"
              data-id="${d.id}"
              data-role="cart-tr"
              data-no-stock="${!d.product.stock_baku.quantity && !d.product.stock_ganja.quantity ? 1 : 0}"
            >
      <th scope="row">${i}</th>
      <td><input class="c-pointer" data-role="status-check" type="checkbox" ${(d.product.stock_baku.is_check || d.product.stock_ganja.is_check) ? "checked" : ""}></td>
      <td >${d.product.brand && d.product.brand.name ? d.product.brand.name : ""}</td>
      <td >${d.product.brand && d.product.brand.code ? d.product.brand.code : ""}</td>
      <td  >
        <div class="d-flex" >
          <a class="camera-link me-2" data-role="window-img-search" href="javascript:void(0)">
            <i class="fa fa-camera" aria-hidden="true"></i>
          </a>
          <span data-role="oem" data-search="code" data-code="${d.product.OEM}" >${d.product.OEM ?? ""}</span>
        </div>
      </td>
      <td >${d.product && d.product.name ? d.product.name : ""}</td>
      <td >${d.product && d.product.delivery_time ? d.product.delivery_time : ""}</td>
      <td>
        <textarea class="form-control" data-role="cart-note" name="cart_note" style="resize:none;">${d.comment ?? ""}</textarea>
      </td>
      <td >${d.operation_date ? date_format(d.operation_date) : ""}</td>
      <td class="text-${d.product.stock_baku.class}" >${d.product.stock_baku.icon ?
          `<span class="badge badge-${d.product.stock_baku.class}">${d.product.stock_baku.title}</span>` :
          number_format(d.product.stock_baku.quantity ?? 0,0, ",", ".", 0)}
      </td>
      <td class="text-${d.product.stock_baku_2.class}" >${d.product.stock_baku_2.icon ?
          `<span class="badge badge-${d.product.stock_baku_2.class}">${d.product.stock_baku_2.title}</span>` :
          number_format(d.product.stock_baku_2.quantity ?? 0,0, ",", ".", 0)}
      </td>
      <td class="text-${d.product.stock_ganja.class}" >${d.product.stock_ganja.icon ?
          `<span class="badge badge-${d.product.stock_ganja.class}">${d.product.stock_ganja.title}</span>` :
          number_format(d.product.stock_ganja.quantity ?? 0,0, ",", ".", 0)}
      </td>
      <td>
        <input
          data-role="product-cart-count"
          value="${+d.quantity}"
          type="number"
          class="form-control"
          style="padding: 0.5rem;max-height: 42px;"
          step="1"
        />
      </td>
      <td
        style="text-align:right;"
        data-role="product-sale-price"
        data-price="${d.product.sale_price}"
        data-converted-price="${d.product.converted_sale_price}"
        data-discount-price="${d.product.discount_price}"
        data-converted-discount-price="${d.product.converted_discount_price}"
      >
      ${ d.product.has_discount ? (d.product && d.product.sale_price ? `<div class="d-flex align-items-center justify-content-end">
                            <s class="text-danger me-1"><span data-name="price">${number_format(+d.product.sale_price, 2, ',', '.', ',') + "</span>" + (d.product.currency ?? "")}</s>`
                            + `<span data-name="discount-price">` + number_format(d.product.discount_price || 0, 2,",",".",0) + "</span>" + (d.product.currency ?? "")
                            + "</div>" : "") :
                          (d.product && d.product.sale_price? `<div class="d-flex align-items-center justify-content-end"><span data-name="price">`
                                            + number_format(+d.product.sale_price, 2, ',', '.', ',')+ "</span>"  +(d.product.currency ?? "")
                                            + "</div>" : "")}


      ${ d.product.has_discount ? (d.product && d.product.sale_price ?
                        (d.product.currency !== "AZN" ?
                            `<div class="d-flex align-items-center justify-content-end">
                              <span>
                                <s class="text-danger me-1">${number_format(d.product.converted_sale_price || 0, 2, ",", ".",0) + (d.product.converted_currency ?? "")}</s>
                              </span>
                              <span style="display: block;font-size: 12px;color: #585858;" >
                                ${number_format(d.product.converted_discount_price || 0, 2, ",", ".",0) + " " + d.product.converted_currency}
                              </span>
                            </div>` : "") : "") :
                          (d.product && d.product.sale_price ?
                            (d.product.currency !== "AZN" ?
                                `<div class="d-flex align-items-center justify-content-end">
                                    <span style="display: block;font-size: 12px;color: #585858;" >
                                      ${number_format(d.product.converted_sale_price || 0, 2,",",".",0) + (d.product.converted_currency ?? "")}
                                    </span>
                                </div>` : "") : "")}
      </td>
      <td
        style="text-align:right;"
        data-role="product-total-sale-price"
        data-total-price="${d.product.total_sale_price}"
        data-converted-total-price="${d.product.converted_total_sale_price}"
        data-total-discount-price="${d.product.total_discount_price}"
        data-converted-total-discount-price="${d.product.converted_total_discount_price}"
        data-has-discount="${d.product.has_discount}"
      >
      ${ d.product.has_discount ? (d.product && d.product.total_sale_price ? `<div class="d-flex align-items-center justify-content-end">
                            <s class="text-danger me-1"><span data-name="price">${number_format(+d.product.total_sale_price, 2, ',', '.', ',') + "</span>" +(d.product.currency ?? "")}</s>`
                            + `<span data-name="discount-price">` + number_format(d.product.total_discount_price || 0, 2,",",".",0) + "</span>" + (d.product.currency ?? "")
                            + "</div>" : "") :
                          (d.product && d.product.total_sale_price? `<div class="d-flex align-items-center justify-content-end"><span data-name="price">`
                                            + number_format(+d.product.total_sale_price, 2, ',', '.', ',') + "</span>" +(d.product.currency ?? "")
                                            + "</div>" : "")}


      ${ d.product.has_discount ? (d.product && d.product.total_sale_price ?
                        (d.product.currency !== "AZN" ?
                            `<div class="d-flex align-items-center justify-content-end">
                              <span>
                                <s class="text-danger me-1"><span data-role="converted-total-price">
                                  ${number_format(d.product.converted_total_sale_price || 0, 2, ",", ".",0)+ "</span>" + (d.product.converted_currency ?? "")}</s>
                              </span>
                              <span style="display: block;font-size: 12px;color: #585858;"><span data-role="converted-total-discount-price">
                                ${number_format(d.product.converted_total_discount_price || 0, 2, ",", ".",0) + "</span>" + d.product.converted_currency}
                              </span>
                            </div>` : "") : "") :
                          (d.product && d.product.total_sale_price ?
                            (d.product.currency !== "AZN" ?
                                `<div class="d-flex align-items-center justify-content-end">
                                    <span style="display: block;font-size: 12px;color: #585858;" ><span data-role="converted-total-price">
                                      ${number_format(d.product.converted_total_sale_price || 0, 2,",",".",0) + "</span>" + (d.product.converted_currency ?? "")}
                                    </span>
                                </div>` : "") : "")}
      </td>

      <td style="text-align:right;" >
        <button
                data-role="delete"
                class="btn btn-danger btn-icon"
                data-toggle="tooltip"
                data-placement="left"
                title="${lang("Delete")}"
                >
              <i class="fa-solid fa-trash-can"></i>
        </button>
      </td>
    </tr>`;
  };
  const trInvoiceComponent = (d, i) => {
    return `<tr
              data-resource="${d.product.resource}"
              data-id="${d.id}"
            >
      <th scope="row">${i}</th>
      <td style="text-align:left;" >${d.product.brand && d.product.brand.name ? d.product.brand.name : ""}</td>
      <td style="text-align:left;" >${d.product.brand && d.product.brand.code ? d.product.brand.code : ""}</td>
      <td  >
        <div class="d-flex" >
          <a class="camera-link me-2" data-role="window-img-search" href="javascript:void(0)">
            <i class="fa fa-camera" aria-hidden="true"></i>
          </a>
          <span data-role="oem" data-search="code" data-code="${d.product.OEM}" >${d.product.OEM ?? ""}</span>
        </div>
      </td>
      <td style="text-align:left;" >${d.product && d.product.name ? d.product.name : ""}</td>
      <td >${d.product && d.product.delivery_time ? d.product.delivery_time : ""}</td>
      <td class="text-${d.product.stock_baku.class}" >${d.product.stock_baku.icon ?
          `<span class="badge badge-${d.product.stock_baku.class}">${d.product.stock_baku.title}</span>` :
          number_format(d.product.stock_baku.quantity ?? 0,0, ",", ".", 0)}
      </td>
      <td class="text-${d.product.stock_baku_2.class}" >${d.product.stock_baku_2.icon ?
          `<span class="badge badge-${d.product.stock_baku_2.class}">${d.product.stock_baku_2.title}</span>` :
          number_format(d.product.stock_baku_2.quantity ?? 0,0, ",", ".", 0)}
      </td>
      <td class="text-${d.product.stock_ganja.class}" >${d.product.stock_ganja.icon ?
          `<span class="badge badge-${d.product.stock_ganja.class}">${d.product.stock_ganja.title}</span>` :
          number_format(d.product.stock_ganja.quantity ?? 0,0, ",", ".", 0)}
      </td>
      <td>${+d.quantity}</td>
      <td
              data-role="product-price"
              data-price="${d.product.sale_price}"
              class="text-end"
            >
              ${d.product && d.product.sale_price ? number_format(+d.product.sale_price, 2, ',', '.', ',') : ""}
      </td>

        <td
              data-role="product-total-price-eur"
              data-total-price="${+d.product.total_sale_price}"
              class="text-end"
          >
            ${d.product && d.product.total_sale_price ? number_format(+d.product.total_sale_price, 2, ',', '.', ',') : ""}
          </td>
        <td>${d.product.currency && d.product.sale_price ? d.product.currency : ""}</td>
    </tr>`;
  };

  const headerCalculator = () => {
    $(`[data-role="product-total-count"]`).html($(`[data-role="table-list"] [data-role="cart-tr"]:visible`).length);
    let count_tr = 0,
      whole_total = 0,
      converted_whole_total = 0;

    $(`[data-role="table-list"] > tr:visible`).each(function() {
      let has_discount = $(this).find(`[data-role="product-total-sale-price"]`).data("has-discount");
      whole_total += has_discount ? (+$(this).find(`[data-role="product-total-sale-price"]`).data("total-discount-price")) : (+$(this).find(`[data-role="product-total-sale-price"]`).data("total-price"));

      converted_whole_total += has_discount ? (+$(this).find(`[data-role="product-total-sale-price"]`).data("converted-total-discount-price")) : (+$(this).find(`[data-role="product-total-sale-price"]`).data("converted-total-price"));
      count_tr++;
    });

    if(!count_tr) {
      $(document).find(`.disabled-manually [data-role="add-to-order"]`).prop({disabled: true});
    }
    $(document).find(`[data-role="product-whole-total-price"]`).text(
        number_format(whole_total || 0, 2,",",".",0) + " " + current_account_currency + (current_account_currency !== "AZN" ? " / " + number_format(converted_whole_total || 0, 2,",",".",0) + " AZN" : "")
    );

    let choosen_total = 0,
      choosen_whole_total = 0,
      converted_choosen_whole_total = 0;

    $(`[data-role="table-list"] > tr:visible`).each(function() {
      choosen_total += $(this).find(`[data-role="status-check"]`).is(":checked") ? 1 : 0;

      if ($(this).find(`[data-role="status-check"]`).is(":checked")) {
        let has_discount = $(this).find(`[data-role="product-total-sale-price"]`).data("has-discount");

        choosen_whole_total += has_discount ? (+$(this).find(`[data-role="product-total-sale-price"]`).data("total-discount-price")) : (+$(this).find(`[data-role="product-total-sale-price"]`).data("total-price"));
        converted_choosen_whole_total += has_discount ? (+$(this).find(`[data-role="product-total-sale-price"]`).data("converted-total-discount-price")) : (+$(this).find(`[data-role="product-total-sale-price"]`).data("converted-total-price"));
      }
    });
    $(`[data-role="product-choosen-total-count"]`).html(choosen_total);

    $(document).find(`[data-role="product-choosen-whole-total-price"]`).text(
      number_format(choosen_whole_total || 0, 2,",",".",0) + " " + current_account_currency + (current_account_currency !== "AZN" ? " / " + number_format(converted_choosen_whole_total || 0, 2,",",".",0) + " AZN" : "")
    );

    let minimum_one_unchecked = 0;
    $(`[data-role="table-list"] > tr:visible`).each(function() {
      minimum_one_unchecked += !$(this).find(`[data-role="status-check"]`).is(":checked") ? 1 : 0;
    });
    $(`[data-role="check-all"]`).prop("checked", !(minimum_one_unchecked >= 1));
  };

  const getContent = (urlParams) => {
    const cartlist = (urlParams,filters) => {
      ModalLoader.start(lang("Loading"));
      let {wid, resource, filter_type, is_remote, gid} = urlParams;

      let html = "",
      count = 0;

      filter_url([
        {wid: (wid || "")},
        {resource: (resource || "")},
        {filter_type: (filter_type || "")},
        {is_remote: (is_remote || "")},
        {gid: (gid || "")},
        // {is_remote: +is_remote ? (is_remote || "") : ""},
      ]);


      let stock_btns = filters.map((v) => {
        let is_active = false;

        if(filter_type === v.filter_type && ["all", "no_stock"].includes(filter_type)) {

          is_active = true;
        }else if (is_remote === "1" && filter_type === v.filter_type && is_remote === v.is_remote && resource === v.supplier) {

          is_active = true;
        }else if (filter_type === v.filter_type && is_remote === v.is_remote && resource === v.supplier && wid === v.warehouse_id) {
          is_active = true;
        }

        return `<a
                  data-role="stock_type"
                  data-is-remote="${v.is_remote || ""}"
                  data-resource="${v.supplier || ""}"
                  data-wid="${v.warehouse_id || ""}"
                  data-filter-type="${v.filter_type || ""}"
                  data-gid="${v.id || ""}"
                  class="nav-link${is_active ? " nav-link btn btn-primary" : ""}"
                  data-toggle="tooltip"
                  data-placement="bottom"
                  data-bs-original-title="${v.details || ""}"
                  href="javascript:void(0)"
                >
                  <span class="menu-title">${v.name || ""}</span>
                </a>`;
      }).join("");

      $.get({
        url: `/cart/list-live`,
        headers,
        data: urlParams,
        success: function (d) {
          if (d.code === 200) {
            let content_data = d.data && d.data.list ? d.data.list : [];
            count =  d.data && d.data.count ? d.data.count : 0;

            // let stocks_btns = {
            //   all: false,
            //   baku: false,
            //   ganja: false,
            //   backorder: false,
            // }

            html = content_data.map((v, i) => trComponent(v, ++i)
            ).join("");

          }
          else if (d.code === 204) {
              html = warningComponent(d.message);
            }
          else {
            Swal.fire("", d.message, "warning");
            console.log(d);
          }
          $(`[data-role="table-list"]`).html(html);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {

          $(`[data-role="cart-btns"]`).html(
            `
            <div class="col-md-6 d-flex mobile-scroll-x">${stock_btns}</div>
            <div class="col-md-6 d-flex justify-content-end">
              <button data-role="delete-items" class="btn  btn-danger me-2 ml-auto disabled-manually" disabled>
                ${lang("delete_choosens")}<i class="fa-solid fa-trash-arrow-up ms-2"></i>
              </button>

              <button data-role="add-to-order" class="btn  btn-primary ml-auto disabled-manually" disabled>
                ${lang("Order to")}<i class="fa-solid fa-paper-plane ms-2"></i>
              </button>
            </div>`);


          ModalLoader.end();

          $(`[data-role="refresh-table"]`).prop("disabled", false);

          $(document).find("i").removeClass("fa-spin fa-spinner");

          $(document).find('[data-toggle="tooltip"]').tooltip();

          $(document).find('[data-toggle="tooltip"]').click(function () {
            $(document).find('[data-toggle="tooltip"]').tooltip("hide");
          });

          $(document).find("button").on("blur", function() {
            $(document).find('[data-toggle="tooltip"]').tooltip("hide");
          });

          headerCalculator();
          cartCount();
          if(count){
            $(document).find(`.disabled-manually[data-role="delete-items"]`).prop({disabled: false});
            $(document).find(`.disabled-manually[data-role="add-to-order"]`).prop({disabled: false});
          }
        },
      });
    };

    ModalLoader.start(lang("Loading"));
    $.get({
      url: `/cart/properties/filter-list`,
      headers,
      success: function(d){
        if(d.code === 200) {
          let filters = d.data || [];

          cartlist(urlParams,filters);
          // setStorage("cart_filters_list", JSON.stringify(filters), 60 * 60 * 12);

        }
      },
      complete: function(d){

        ModalLoader.end();
      },
    });
  };

  $(document).on("click", `[data-role="check-all"]`, function() {
    $(document).find(`[data-role="status-check"]`).prop("checked", this.checked);
    headerCalculator();
  });

  $(document).on("click", `[data-role="status-check"]`, function() {
    let ischecked= $(this).is(':checked');
    if(!ischecked) {
      $(`[data-role="check-all"]`).prop("checked", false);
    }

    headerCalculator();
  });

  let wid = getUrlParameter("wid") || "",
    resource = getUrlParameter("resource") || "",
    filter_type = getUrlParameter("filter_type") || "",
    is_remote = getUrlParameter("is_remote") || "",
    gid = getUrlParameter("gid") || "";

  getContent({wid,resource,filter_type,is_remote,gid});

  $(document).on("click", `[data-role="stock_type"]`, function() {

    wid = $(this).data("wid").toString();
    resource = $(this).data("resource");
    is_remote = $(this).data("is-remote").toString();
    filter_type = $(this).data("filter-type");
    gid = $(this).data("gid").toString();

    getContent({wid,resource,filter_type,is_remote,gid});
  });

  $(document).on("click",`[data-role="delete"]`,function(e){
    let id = $(this).closest("tr").data("id"),
    el = $(this);

    e.preventDefault();

    Swal.fire({
      title: lang("you_sure_delete_cart_item"),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        ModalLoader.start(lang("Loading"));
        $.ajax({
          url: `/cart/${id}/delete`,
          headers,
          type: 'delete',
          success: function(d){
            // console.log(d)
            if(d.code === 200) {
              Swal.fire("",d.message,'success');
              el.parents("tr").hide();


            } else {
              Swal.fire("",d.message,'warning')
            }
          },
          error: function(d){
              console.log(d)
          },
          complete: function(d) {
            headerCalculator();
            cartCount();
            ModalLoader.end();
          }
        });
      }
    })
  });

  $(document).on("click",`[data-role="delete-items"]`,function(e){
    let cart_ids = [];
    $(`[data-role="table-list"] > tr:visible`).each(function(){
      if ($(this).find(`[data-role="status-check"]`).is(":checked")) {
        cart_ids[cart_ids.length] = $(this).data("id");
      }
    });
    e.preventDefault();

    if(!cart_ids.length) {
      Swal.fire("", lang("not_choosed_product"), "warning"); return false;
    }

    Swal.fire({
      title: lang("you_sure_delete_cart_items"),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        ModalLoader.start(lang("Loading"));
        $.ajax({
          url: `/cart/delete-items`,
          headers,
          data: JSON.stringify({cart_ids}),
          type: 'delete',
          success: function(d){
            // console.log(d);
            if(d.code === 200) {
              cartCount();
              Swal.fire({
                title: d.message,
                icon: "success",
                allowOutsideClick: false,
                allowEscapeKey: false,
              }).then((res) => {
                if (res.isConfirmed) {
                  window.location.reload();
                }
              });
              Object.values(cart_ids).map(v => {
                $(`[data-id="${v}"]`).remove();
              });

            } else {
              Swal.fire("",d.message,'warning')
            }
          },
          error: function(d){
              console.error(d)
          },
          complete: function(d) {
            ModalLoader.end();
            headerCalculator();
            cartCount();
          }
        });
      }
    })
});

  let product_count_in = "";
  $(document).on("focusin", `[data-role="table-list"] > tr:visible`, function () {
    product_count_in = $(this).find(`[data-role="product-cart-count"]`).val().trim();
  });
  $(document).on("focusout", `[data-role="table-list"] > tr:visible`, function () {
    let t = $(this).find(`[data-role="product-cart-count"]`),
    id = $(this).data("id"),
    minimum = $(this).find(`[data-role="product-cart-count"]`).data("min"),
    maximum = $(this).find(`[data-role="product-cart-count"]`).data("max");

    if (product_count_in === t.val().trim()) {
      return;
    }

    let product_count = +t.val().trim();

    if (product_count <= 0) {
      product_count = 1;
      t.val(1);
      Swal.fire("", lang("product_min_count"), "warning");
      // return;
    }


    t.parent("div").addClass("load");
    t.prop("disabled", true);
    $.ajax({
      url: `/cart/${id}/edit-quantity`,
      headers,
      type: "PUT",
      data: JSON.stringify({
        quantity: product_count,
      }),
      success: function (d) {
        if (d.code === 202) {
          t.addClass("is-valid");
          setTimeout(function () {
            t.removeClass("is-valid");
          }, 3000);

          t.val(product_count);

          let product_price = t.parents("tr").find(`[data-role="product-sale-price"]`).data("price"),
          converted_price = t.parents("tr").find(`[data-role="product-sale-price"]`).data("converted-price"),
          discount_price = t.parents("tr").find(`[data-role="product-sale-price"]`).data("discount-price"),
          converted_discount_price = t.parents("tr").find(`[data-role="product-sale-price"]`).data("converted-discount-price");

          t.parents("tr").find(`[data-role="product-total-sale-price"]`).find(`[data-name="price"]`).text(number_format(+product_count * +product_price, 2, ',', '.', ','));
          t.parents("tr").find(`[data-role="product-total-sale-price"]`).find(`[data-name="discount-price"]`).text(number_format(+product_count * +discount_price, 2, ',', '.', ','));

          t.parents("tr").find(`[data-role="product-total-sale-price"]`).data("total-price",(+product_count * +product_price));
          t.parents("tr").find(`[data-role="product-total-sale-price"]`).data("total-discount-price",(+product_count * +discount_price));

          t.parents("tr").find(`[data-role="product-total-sale-price"]`).data("converted-total-price",(+product_count * +converted_price));
          t.parents("tr").find(`[data-role="converted-total-price"]`).text(number_format((+product_count * +converted_price),2,",","."));
          t.parents("tr").find(`[data-role="product-total-sale-price"]`).data("converted-total-discount-price",(+product_count * +converted_discount_price));
          t.parents("tr").find(`[data-role="converted-total-discount-price"]`).text(number_format((+product_count * +converted_discount_price),2,",","."));

          headerCalculator();
          cartCount();
        } else {
          Swal.fire("", d.message, "warning");
          t.val(product_count_in);
        }
        t.parent("div").removeClass("load");
      },
      error: function (d) {
        console.log(d);
        t.parent("div").removeClass("load");
      },
      complete: function () {
        t.prop("disabled", false);
      },
    });
  });



  let cart_note_in = "";
  $(document).on("focusin", `[data-role="table-list"] > tr:visible`, function () {
    cart_note_in = $(this).find(`[data-role="cart-note"]`).val().trim();
  });
  $(document).on("focusout", `[data-role="table-list"] > tr:visible`, function () {
    let t = $(this).find(`[data-role="cart-note"]`),
    id = $(this).data("id");

    if (cart_note_in === t.val().trim()) {
      return;
    }

    let cart_note = t.val().trim();

    t.parent("div").addClass("load");
    t.prop("disabled", true);
    $.ajax({
      url: `/cart/${id}/edit-note`,
      headers,
      type: "PUT",
      data: JSON.stringify({comment: cart_note}),
      success: function (d) {
        // console.log(d);
        if (d.code === 202) {
          t.addClass("is-valid");
          setTimeout(function () {
            t.removeClass("is-valid");
          }, 3000);

          t.val(cart_note);

        } else {
          Swal.fire("", d.message, "warning");
          t.val(cart_note_in);
        }
        t.parent("div").removeClass("load");
      },
      error: function (d) {
        console.log(d);
        t.parent("div").removeClass("load");
      },
      complete: function () {
        t.prop("disabled", false);
      },
    });
  });

  let cart_order_ids = [];
  $(document).on("click", `[data-role="add-to-order"]`, function() {
    cart_order_ids = [];
    $(`[data-role="table-list"] > tr:visible`).each(function(){
      if ($(this).find(`[data-role="status-check"]`).is(":checked")) {
        cart_order_ids[cart_order_ids.length] = $(this).data("id");
      }
    });

    if(!cart_order_ids.length) {
      Swal.fire("", lang("not_choosed_product"), "warning"); return false;
    }


    let options = "";

    let show_stock_option = true;
    // let show_stock_option = false;
    // $(document).find(`[data-role="table-list"] tr:visible`).each(function() {
    //   if ($(this).find(`[data-role="status-check"]`).is(":checked") && $(this).data("resource") === '0x001') {
    //     show_stock_option = true;
    //   }
    // });

    if(filter_type === "no_stock"){
      let count = 0,
      no_stock_count = 0;
      $(document).find(`[data-role="table-list"] [data-role="cart-tr"]:visible`).each(function() {
        count++;
        if ($(this).find(`[data-role="status-check"]`).is(":checked") && ($(this).data("no-stock").toString() === "1")) {
          no_stock_count++;
        }
      });

      if(count === no_stock_count) {
        Swal.fire("", lang("you_cant_send_to_order_no_stock_products"), "warning");
        return;
      }
    }

    ModalLoader.start(lang("Loading"));
    $.get({
      url: `/cart/properties/filter-list`,
      headers,
      success: function(d){
        let content_data = d.data ?? [];
        options = `<option value="" >${lang("choose_warehouse_group")}</option>`;
        options  += content_data.map((v,i) => {
          if(v.filter_type === "group") {
            return `<option value="${v.id}" ${gid === v.id ? "selected" : ""}
                >${v.name}</option>`
          }
        }).join("");

        Swal.fire({
          title: lang("comment"),
          html: show_stock_option ? `<select data-role="select-groups" class="custom-select">
                    ${options}
              </select>
              <div data-role="warehouse-group-warning" class="mt-2 d-none alert d-flex" >

              </div>
              <textarea style="width:90%;margin-left:0px;" data-role="send-to-order-comment" class="swal2-textarea" placeholder="${lang("special_notes_about_order")}"></textarea>
              <button data-role="send-to-confirm-order" type="button" class="swal2-confirm swal2-styled swal2-default-outline" aria-label="" style="display: inline-block; background-color: rgb(48, 133, 214);">
              ${lang("send_to_order")}
              </button>
              ` : "",
            showCancelButton: false,
            showConfirmButton: false
        });


      },
      error: function(d){
        console.log(d);
      },
      complete: function(d){
        ModalLoader.end();
      }
    });

  });

  $(document).on("click", `[data-role="send-to-confirm-order"]`, function(){

    let entry_comment = $(document).find(`[data-role="send-to-order-comment"]`).val(),
    group_id = $(document).find(`[data-role="select-groups"]`).val(),
    warning_el = $(document).find(`[data-role="warehouse-group-warning"]`),
    show_stock_option = true;

    if(!group_id){
      warning_el.text(lang("please_choose_warehouse_group"));
      warning_el.addClass("alert-warning");
      warning_el.removeClass("d-none");
      return;
    }

    ModalLoader.start(lang("Loading"));
    $.post({
      url: "/orders/is-approve",
      headers,
      data: {cart_ids: cart_order_ids, entry_comment,group_id},
      success: function(d) {
        if(d.code === 201){
          let content_data = d.data && d.data.order_approve ? d.data.order_approve : [],
          // warehouse_id = d.data &&  d.data.warehouse && d.data.warehouse.id ? d.data.warehouse.id : null,
          group_id = d.data && d.data.group && d.data.group.id ? d.data.group.id : null,
          group_name = d.data &&  d.data.group && d.data.group.name ? d.data.group.name : null,
          entry_comment = d.data && d.data.entry_comment ? d.data.entry_comment : "";

          Swal.fire({
            html: `
        <div data-role="invoice-modal" class="row" data-group-id="${group_id}">
                  <div class="col-md-12" style="font-size:14px;">
                      <div class="card-box">
                          <div class="pb-4">
                              <div class="float-left">
                                  <h3 class="m-0 d-print-none text-start fw-bold">${lang("invoice") + (show_stock_option ? (group_name ? (" - " + group_name) :  "") : "")}</h3>
                              </div>
                          </div>
                          <p class="alert alert-warning text-start mb-0">
                             ${lang("order_approve_info")}
                          </p>
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="table-responsive">
                                      <table  class="table mt-4 table-centered table-bordered">
                                          <thead>
                                            <tr>
                                              <th style="" class="border-bottom-0">#</th>
                                              <th class="border-bottom-0">${lang("Brand")}</th>
                                              <th class="border-bottom-0">${lang("Brand code")}</th>
                                              <th class="border-bottom-0">${lang("OEM")}</th>
                                              <th class="border-bottom-0">${lang("Product name")}</th>
                                              <th class="border-bottom-0">${lang("Day")}</th>
                                              <th class="border-bottom-0">${lang("baku")}</th>
                                              <th class="border-bottom-0">${lang("stock_baku_2")}</th>
                                              <th class="border-bottom-0">${lang("ganja")}</th>
                                              <th class="border-bottom-0">${lang("Quantity")}</th>
                                              <th style="text-align:right;" class="border-bottom-0">${lang("Price")}</th>
                                              <th style="text-align:right;" class="border-bottom-0">${lang("Total")}</th>
                                              <th style="width:5%;" ></th>
                                            </tr>
                                          </thead>
                                          <tbody data-role="table-invoice-list">
                                            ${content_data.map((v,i) => trInvoiceComponent(v,++i)).join("")}
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>

                          <div class="text-end d-flex justify-content-end">
                              <div class="text-end"><b>${lang("Total")}: ${(d.data.totals ? number_format(d.data.totals.sale_price, 2, ',', '.', ',') : 0) }</b></div>
                          </div>
                          <div>
                                ${  d.data && d.data.messages ? d.data.messages.map((v,i) => {return `<br><div class="d-flex" >${v}</div>`}).join("") : ""}
                          </div>
                      </div>

                  </div>
                  <input value="${entry_comment}" type="hidden" data-role="invoice-entry-comment"  />
              </div>
        `,
        customClass: 'swal-invoice-wide',
        showCancelButton: true,
        confirmButtonText: lang("confirm"),
        cancelButtonText: lang("close"),
              }).then((result) => {

                if(result.isConfirmed) {
                  ModalLoader.start(lang("Loading"));
                  disableAll(true);
                  let cart_ids = [];
                  // let warehouse_id = $(`[data-role="invoice-modal"]`).data("warehouse-id");
                  let group_id = $(`[data-role="invoice-modal"]`).data("group-id");
                  let entry_comment = $(`[data-role="invoice-entry-comment"]`).val();

                  $(document).find(`[data-role="table-invoice-list"] tr`).each(function() {
                    cart_ids[cart_ids.length] = $(this).data("id");
                  });
                  $.ajax({
                    url: `/orders/confirm-approve`,
                    data: JSON.stringify({cart_ids,group_id,entry_comment}),
                    headers,
                    type: 'PUT',
                    success: function(d) {
                      if(d.code === 202){
                        Swal.fire("", d.message, "success").then((result)=> {
                          getContent({wid,resource,filter_type,is_remote,gid});
                        });

                      } else {
                        Swal.fire("", d.message, "warning");
                      }

                    },
                    complete: function(d) {
                      cartCount();
                      ModalLoader.end();
                      disableAll(false);
                    }
                  });
                }
            });
        } else {
          Swal.fire("", d.message, "warning");
        }
      },
      error: function(d){
        console.log(d);
      },
      complete: function(d) {
        ModalLoader.end();
        // cartCount();
      }
    });
  });


  $(`[data-role="refresh-table"]`).on("click", function() {
    $(this).find("i").addClass("fa-spinner").addClass("fa-spin");
    getContent({wid,resource,filter_type,is_remote,gid});
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

});
