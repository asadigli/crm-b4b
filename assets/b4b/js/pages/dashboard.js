"use-strict";
$(function(){
  const number_format = (number, decimals, dec_point, thousands_sep) => {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

  const newProductComponent = (item,key) => {
    return `
        <div
              data-id="${item.id}"
              data-remote-id="${item.remote_id}"
              data-resource="${item.resource}"
              data-in-cart="${item.in_cart}"
              data-name="${item.name}"
              data-brand-name="${item.brand_name}"
              data-brand-code="${item.brand_code}"
              data-description="${item.description}"
              data-model="${item.model}"
              data-oem="${item.OEM}"
              data-sale-price="${item.has_discount ? item.discount_price : item.sale_price}"
              data-main-price="${item.main_price ? item.main_price : ''}"
              data-final-currency="${item.currency}"
              data-currency="${item.currency}"
              data-cart-id="${item.cart_id || ""}"
              data-role="prod-parent"
              class="col-md-3"
          >
          <div class="box">
              <div class="fx-card-item">
                  <div class="fx-card-avatar fx-overlay-1">
                    <img src="${item.new_from_warehouse_image || "/assets/globals/image/no-image.png"}" alt="${item.name}">
                      <div class="add-to-cart-wrapper">
                          <a href="javascript:void(0)" class="add-to-cart ${item.in_cart ? "selected" : ""}" id="product_${key}" data-role="add-to-cart">
                              <i class="fa-solid fa-cart-shopping"></i>
                          </a>
                          <div data-role="count-change-parent" class="number">
                              <span class="minus" data-action="count-change" data-role="count-minus">-</span>
                              <input type="number" value="${item.in_cart || 1}" data-role="product-cart-count" class="${item.in_cart ? "product-in-cart" : ""}">
                              <span class="plus" data-action="count-change" data-role="count-plus">+</span>
                          </div>
                      </div>
                  </div>
                  <div class="fx-card-content">
                      <div class="row m-0">
                          <div class="col-4 border-bottom">
                              <span>${words["Product"]}</span>
                          </div>
                          <div class="col-8 border-bottom">
                              <span>${item.name || ""}</span>
                          </div>
                          <div class="col-4 border-bottom">
                              <span>${words["Car brand"]}</span>
                          </div>
                          <div class="col-8 border-bottom">
                              <span>${item.description || ""}</span>
                          </div>
                          <div class="col-4 border-bottom">
                              <span>${words["Brand"]}</span>
                          </div>
                          <div class="col-8 border-bottom">
                              <span>${item.brand_name || ""}</span>
                          </div>
                          <div class="col-4 border-bottom">
                              <span>${words["Brand code"]}</span>
                          </div>
                          <div class="col-8 border-bottom">
                              <span>
                                  <a target="_blank" href="${item.brand_code ? `/products/search?keyword=${item.brand_code}` : "javascript:void(0)"}" class="link">
                                      ${item.brand_code || ""}
                                  </a>
                              </span>
                          </div>
                          <div class="col-4 border-bottom">
                              <span>${words["Original code"]}</span>
                          </div>
                          <div class="col-8 border-bottom">
                              <span>
                                  <a target="_blank" href="${item.OEM ? `/products/search?keyword=${item.OEM}` : "javascript:void(0)"}" class="link">
                                      ${item.OEM || ""}
                                  </a>
                              </span>
                          </div>
                          <div class="col-4">
                              <span>${words["Price"]}</span>
                          </div>
                          <div class="col-8 flex-column">
                              ${item.has_discount ? (item.sale_price ? `<div class="d-flex align-items-center justify-content-end">
                                  <s class="text-danger me-1">${number_format(item.sale_price, 2, ",", ".")}${item.currency || ''}</s>
                                  ${number_format(item.discount_price, 2, ",", ".")}${item.currency || ''}
                              </div>` : (item.sale_price_description || '')) :
                              (item.sale_price ? `<div class="d-flex align-items-center justify-content-end">
                                  ${number_format(item.sale_price, 2, ",", ".")}${item.currency || ''}
                              </div>` : (item.sale_price_description || ''))}
                              ${item.has_discount ? (item.sale_price ? (item.currency !== "AZN" ?
                                  `<div class="d-flex align-items-center justify-content-end">
                                      <span>
                                          <s class="text-danger me-1">${number_format(item.converted_sale_price, 2, ",", ".")}${item.converted_currency || ''}</s>
                                      </span>
                                      <span style="display: block;font-size: 12px;color: #585858;">
                                          ${number_format(item.converted_discount_price, 2, ",", ".")} ${item.converted_currency}
                                      </span>
                                  </div>` : '') : '') :
                                  (item.sale_price ? (item.currency !== "AZN" ?
                                      `<div class="d-flex align-items-center justify-content-end">
                                          <span style="display: block;font-size: 12px;color: #585858;">
                                              ${number_format(item.converted_sale_price, 2, ",", ".")}${item.converted_currency || ''}
                                          </span>
                                      </div>` : '') : '')}
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    `;
  }

  $("#dashboard-slide").owlCarousel({
    dots: true,
    nav: true,
    loop: true,
    autoplay: true,
    autoplayTimeout: 2500,
    autoplayHoverPause: true,
    items:1
  });

  const getList = (data) => {
    let counter_start = new Date().getTime();
    counter = setInterval(() => {
      $(`[data-role="content-result-time"]`).html(new Date().getTime() - counter_start);
    },100)
    customLoader();
    $.get({
      url: "/list-live",
      headers,
      data,
      success: function(d){
        let h = "";
        if (d.code === 200) {
          d.data.list.map((v,i) => {
            h += newProductComponent(v,i);
          })
        }else{
          h = warningComponent(d.message)
        }
        $("#new_product").html(h);
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        ModalLoader.end();
        clearInterval(counter);
        customLoader(true);
      }
    })
  }

  getList();

  const addToCart = ($this) => {
    let parent = $this.parents(`[data-role="prod-parent"]`),
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
        cart_id = parent.data("cart-id"),
        in_cart = parent.data("in-cart");

    let btn_id = $this.attr("id"),
        btn = $this;

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
  }

  $(document).on("click", `[data-role="add-to-cart"]`, function(event){
    addToCart($(this));
  });


  let addToCartTimes = null;
  $(document.body).on("click", `[data-action="count-change"]`, function(){
    $this = $(this).parents(`[data-role="prod-parent"]`).find(`[data-role="add-to-cart"]`);

    clearTimeout(addToCartTimes);

    addToCartTimes = setTimeout(function() {
      addToCart($this);
    }, 1000);
  });
});
