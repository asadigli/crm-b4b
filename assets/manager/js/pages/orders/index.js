"use strict";
$(function(){

  // let group_id = null;
  // if($(`[data-role="order-group-link"]`).hasClass("current")) {
  //   group_id = $(`[data-role="order-group-link"].current`).data("id");
  // }

  const loaderComponent = () => {
    return `<div class="load-more d-none" data-role="load-more-container"  id="load_more_div">
        <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
      </div>`;
  }
  const clearTable = ({start_date, end_date, group_id, keyword, offset, status, no_date_filter, remote_customer_id, folder_id}) => {
    status = "all";

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0'),
    mm = String(today.getMonth() + 1).padStart(2, '0'),
    yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;

    end_date = today;
    start_date = today;

    $(`[data-role="select-end-date"]`).val(end_date);
    $(`[data-role="select-start-date"]`).val(start_date);

    $(`[data-role="orders-list"]`).html("");
    $(`[data-role="content-result-count"]`).html(" 0 ");
    $(`[data-role="content-result-time"]`).html(" 0 ");
    $(`[data-role="table-loader"]`).addClass("d-none");

    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
  };

  const initializeHeaders = (settetData = {}) => {
    start_date = !settetData.start_date ? $(`[data-role="select-start-date"]`).val().trim() : settetData.start_date;
    end_date = !settetData.end_date ? $(`[data-role="select-end-date"]`).val().trim() : settetData.end_date ;
    keyword = $(`[data-role="search-keyword"]`).val().trim();
    group_id = JSON.stringify($(`[data-role="order-group-link"].current`).data("id"));
    status = $(`[data-role="order-statuses"]`).val();
    no_date_filter = $(`[data-role="no-date-filter"]`).is(":checked") ? "1" : "0";

    $(`[data-role="select-start-date"]`).val(start_date);
    $(`[data-role="select-end-date"]`).val(end_date);
  }

  const trComponent = (d,i,offset) => {
    return `
      <div class="custom-tab-sidebar-list-item ${d.status === "pending" ? "unread" : ""}" data-role="order-list-component" data-id="${d.id}">
        <div class="d-flex align-items-center justify-content-between" >
          <h6>${d.code || ""}</h6>
          <div class="custom-dropdown-box" data-type="no-request">
            <button
            class="btn bg-transparent py-0 no-shadow"
            type="button"
            data-role="open-folder-dropdown"
            data-type="no-request"
            >
              <i class="fa-solid fa-ellipsis-vertical" data-type="no-request"></i>
            </button>
            <div data-role="dropwdown-folder-list" class="custom-dropdown">
            </div>
          </div>
        </div>
        ${d.from_transfer ? `      <div class="d-flex align-items-center justify-content-between" >
                <span style="color:#adadad">${(d.from_transfer_group_name || "") + " " + lang("from_group_transfer")}</span>

              </div>` : ""}

        <span>${d.operation_date || ""}</span>
        <span>${d.entry && d.entry.phone ? d.entry.phone : ""}</span>
        <span>${d.entry && d.entry.name ? d.entry.name+" ("+d.remote_customer_id+")" : ""} /${(d.amount ? number_format(d.amount,2,",",".",0) : "") + (d.currency ? " " + d.currency : "")}/${d.product_count}</span>

        <div data-role="order-confirm-text-component" class="d-flex justify-content-between ">
          <span data-role="order-status-component" style="color:#fff !important;font-size: 10px;" class="mt-1 badge badge-${d.status === "pending" ? "info" :
          (d.status === "confirmed" ? "confirmed" :
          (d.status === "finished" ? "success" :
          (d.status === "canceled" ? "danger" :
          (d.status === "on_the_way" ? "byorder" :
          (d.status === "partially_shipped" ? "warning" : "primary")))))}">
          ${d.status ? lang(d.status) : ""}</span>
          ${d.status === "pending" ? `<p data-role="confirm-order" class="link m-0">${lang("Confirm")}</p>` : ""}
        </div>
      </div>`;
  };

  const trOrderDetailsComponent = (d,i) => {
    return `<tr>
            <td>${i}</td>
            <td>${d.brand && d.brand.name ? d.brand.name : ""}</td>
            <td>${d.delivery_time || ""}</td>
            <td>${d.product && d.product.model || ""}</td>
            <td data-role="product-oem" >${d.brand && d.brand.org_code ? d.brand.org_code : ""}</td>
            <td>${d.product && d.product.stock_baku || ""}</td>
            <td>${d.product && d.product.stock_baku_2 || ""}</td>
            <td>${d.product && d.product.stock_ganja || ""}</td>
            <td  data-role="product-brand-code" data-brand-code="${d.brand && d.brand.code ? d.brand.code : ""}" >${d.brand && d.brand.code ? d.brand.code : ""}</td>
            <td data-role="quantity-change" data-quantity="${d.quantity ?? ""}" >${d.quantity ?? ""}</td>
            <td data-role="product-price" class="text-end">
              ${d.has_discount ? (d.price ?
                                    (`<span class="text-danger" data-toggle="tooltip" data-placement="top" title="${lang("With discount price")}">` + `<i class="fa-solid fa-arrow-down" class="text-danger"></i>` + number_format(d.price,2,",",".",0) + `</span>`) : "")
                               : (d.price ? number_format(d.price,2,",",".",0) : "")}
            </td>
            <td data-role="product-total-price" class="text-end" >${d.total_price ? number_format(d.total_price,2,",",".",0) : ""}</td>
          </tr>`;
  };
  const trOrderDetailsFooterComponent = (totals) => {
    return `
        <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-end text-bold" >${lang("total_price")}:</td>
                <td class="text-end" >${number_format(totals.total_sale_price,2,",",".",0) + " " + totals.total_sale_price_currency} </td>
              </tr>`;
  };
  const trLastPaymentsComponent = (d,i) => {
    return `<tr>
              <td>${i}</td>
              <td>${d.payment_date || ""}</td>
              <td>${d.invoice_code || ""}</td>
              <td>${d.payment_amount ? number_format(d.payment_amount,2,",",".",0) : ""}</td>
            </tr>`;
  };

  // <div class="dropdown-item">Action</div>
  const dropdownFolderListComponent = (d,i) => {
    return `<div
              data-role="add-order-to-folder"
              data-type="no-request"
              data-has-order="${+d.has_current_order}"
              class="dropdown-item c-pointer"
              data-id="${d.id}"
              >
      <div class="d-flex" data-type="no-request">
        <div style="margin-right: 0.5rem;" data-type="no-request">
          <i
            data-type="no-request"
            style="margin:0!important;"
            data-toggle="tooltip"
            data-placement="left"
            title="${+d.has_current_order ? lang("choosen_order_exists_in_this_folder") : ""}"
            class="fa-solid fa-folder ${+d.orders_count ? (+d.has_current_order ? "text-danger" : "text-primary") : ""}"
            ></i>${d.orders_count ? "(" + d.orders_count + ")" : ""}
        </div>
        <div

            data-type="no-request"
            data-role="name"
            data-name="${d.name ?? ""}"
        >
          ${d.name ?? ""}
        </div>
      </div>
    </div>`;
  }

  let interval = null,
      loading = false,
      offset = 0;


  const getContent = (urlParams,first_time = true) => {
    let html = "",
    start_time = null,
    count = 0;


    let {start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id} = urlParams;


    if(first_time) {
      $(`[data-role="content-result-count"]`).html("0");

      $(`[data-role="content-result-confirmed"]`).removeClass("d-none");
      $(`[data-role="content-result-confirmed-count"]`).html("0");

      $(`[data-role="content-result-time"]`).html("0.00");

      $(`[data-role="orders-list-parent"]`).addClass("loading");

      $(`[data-role="search-filter"]`).find("i").addClass("fa-spinner").addClass("fa-spin");

      if(status){
        $(`[data-role="content-result-confirmed"]`).addClass("d-none");
      }

      filter_url([
        {start_date: (start_date || "")},
        {end_date: (end_date || "")},
        {group_id: (group_id || "")},
        {folder_id: (folder_id || "")},
        {status: (status || "")},
        {keyword: (keyword || "")},
        {no_date_filter: +no_date_filter ? (no_date_filter || "") : ""},
      ]);

      if (!(start_date && end_date)) {
        Swal.fire("", lang("minimum_date_should_selected_parameter"), "warning");
        return;
      }

      start_time = new Date().getTime();
      clearInterval(interval);
      interval = setInterval(function () {
        $(`[data-role="content-result-time"]`).html(
          ((new Date().getTime() - start_time) / 1000).toFixed(2)
        );
      }, 100);
    }
    $.get({
      url: `/orders/list-live`,
      headers,
      data: urlParams,
      success: function(d){

        const warningComponent = (message) => {
          return `<div class="d-flex justify-content-center" >
                        <div style="margin:0.3rem 0.8rem; width:80%;text-align:center;color: #676689!important;background-color : transparent !important;border:none !important;" class="alert alert-warning text-warning fade show" role="alert">
                            <strong>${message ?? ""}</strong>
                        </div>
                      </div>`;
        };

        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [],
          folder_name = d.data && d.data.folder_name ? d.data.folder_name : "",
          totals = d.data && d.data.totals ? d.data.totals : [];
          count = d.data && d.data.count ? d.data.count : 0;

          html = content_data.map((v,i) => trComponent(v,++i,offset)).join("");

          if(first_time) {
            $(`[data-role="content-result-count"]`).html(d.data.count || 0);
            $(`[data-role="content-result-confirmed-count"]`).html(d.data.confirmed_order_count || 0);
            $(`[data-role="total-order-amount"]`).html(number_format(totals.order_amount ?? 0,2,",",".",0) + " EUR");

            $(`[data-role="folder-param-name"]`).html(folder_name ? folder_name + `<i data-role="close-btn" class="ms-2 text-danger fa-solid fa-xmark c-pointer"></i>` : "");
          }

          if(+count > +$(`[data-role="orders-list"] [data-role="order-list-component"]`).length) {
            $(`[data-role="orders-list"]`).find(`[data-role="load-more-container"]`).remove();
            html += loaderComponent();


            $(document).find(`[data-role="load-more-container"]`).addClass("d-none");
            $(document).find(`[data-role="load-more-container"]`).removeClass("loading");

          }
        }
        else if (d.code === 204) {
          if(first_time){
            html = warningComponent(d.message);
          } else {
            $(document).find(`[data-role="load-more-container"]`).addClass("d-none");
            $(document).find(`[data-role="load-more-container"]`).removeClass("loading");
          }
        }
        else {
          html = warningComponent(d.message);
          Swal.fire("", d.message, "warning");
          console.log(d);
        }

        if(first_time) {
          $(`[data-role="orders-list"]`).html(html);
        } else {
          $(`[data-role="orders-list"]`).append(html);
        }
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){
        $(document).find("i").removeClass("fa-spinner").removeClass("fa-spin");
        loading = false;
        if(first_time) {
          clearInterval(interval);
          $(`[data-role="content-result-time"]`).html(
            ((new Date().getTime() - start_time) / 1000).toFixed(2)
          );
        }
        $(`[data-role="orders-list-parent"]`).removeClass("loading");

        $(`[data-role="search-filter"]`).find("i").removeClass("fa-spinner").removeClass("fa-spin");

        if(+count === +$(`[data-role="orders-list-parent"] a`).length || !count) {
          $(document).find(`[data-role="load-more-container"]`).addClass("d-none");
          $(document).find(`[data-role="load-more-container"]`).removeClass("loading");
        } else {
          $(document).find(`[data-role="load-more-container"]`).removeClass("d-none");
          $(document).find(`[data-role="load-more-container"]`).addClass("loading");
        }

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find("button").on("blur", function() {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    })
  };

  let start_date = getUrlParameter("start_date") || $(`[data-role="select-start-date"]`).val(),
    end_date = getUrlParameter("end_date") || $(`[data-role="select-end-date"]`).val(),
    keyword = getUrlParameter("keyword") || $(`[data-role="search-keyword"]`).val(),
    status = getUrlParameter("status") || $(`[data-role="order-statuses"]`).val(),
    folder_id = getUrlParameter("folder_id") || "",
    no_date_filter = getUrlParameter("no_date_filter") || "",
    group_id = getUrlParameter("group_id") || JSON.stringify($(`[data-role="order-group-link"]`).eq(0).data("id"));

  getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});

  $(`[data-role="refresh-table"]`).on("click", function() {
    $(this).find("i").addClass("fa-spinner").addClass("fa-spin");
    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
  });

  $(`[data-role="search-keyword"], [data-role="select-start-date"], [data-role="select-end-date"]`).on("keypress", function(e){

    if (e.which === 13) {
        initializeHeaders();
        offset = 0;
        getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
      }
  });

  $(document).on("click", `[data-role="search-filter"]`, function() {
    initializeHeaders();
    offset = 0;
    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
  });

  $(document).on("click", `[data-role="search-folder-orders"]`, function() {
    folder_id = $(this).parents("tr").data("id");
    $(this).parents(".modal").hide();
    $(`.modal-backdrop.fade`).remove();
    ModalLoader.end();
    // offset = 0;
    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
  });

  $(document).on("click", `[data-role="close-btn"]`, function(){
    folder_id = "";
    filter_url([
      {start_date: (start_date || "")},
      {end_date: (end_date || "")},
      {group_id: (group_id || "")},
      {folder_id: (folder_id || "")},
      {status: (status || "")},
      {keyword: (keyword || "")},
      {no_date_filter: +no_date_filter ? (no_date_filter || "") : ""},
    ]);

    $(this).parents(`[data-role="folder-param-name"]`).html("");

  });

  setInterval(function(){
    // console.log($(document).find(`[data-role="load-more-container"]`).isInViewport());
    // console.log(false);
    if (!$(document).find(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    // console.log(true);
      // console.log("here2");
    offset = $(`[data-role="orders-list"] [data-role="order-list-component"]`).length;
    loading = true;
    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id},false);
  },1000)

  $(document).on("click",`[data-role="load-more-container"]`,function(){
    if (!$(document).find(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="orders-list"] [data-role="order-list-component"]`).length;
    loading = true;
    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id},false);
  });

  $(`[data-role="orders-list"]`).on("click", `[data-role="order-list-component"]`, function(e){

    if(e.target.getAttribute("data-type") === "no-request"){
      return;
    }

    $(`[data-role="orders-list"] [data-role="order-list-component"]`).removeClass("current");
    $(this).removeClass("unread");

     $(this).addClass("current");
     let order_id = $(this).data("id");
     getOrderDetails(order_id, folder_id);
  });

  $(document).on("click",`[data-role="order-group-link"]`, function() {
    $(`[data-role="order-group-link"]`).removeClass("current");
    $(this).addClass("current");
    start_date = $(this).data("default-start-date");
    $(`[data-role="content-result-count"]`).html("0");
    let s_date = new Date(start_date),
    e_date = new Date(end_date);

    if(e_date.getDate() < s_date.getDate()){
      end_date = start_date;
    }

    initializeHeaders({start_date, end_date});
    $.ajax({
      url: `/orders/update-order-group-order`,
      method: "PUT",
      headers,
      data: JSON.stringify({group_id})
    });

    offset = 0;
    getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
    $(`[data-role="order-detail-component"]`).addClass("d-none");
  });

  const getOrderDetails = (order_id, folder_id = null) => {
    $(`[data-role="order-details"]`).addClass("loading");
    $(`[data-role="order-detail-component"]`).addClass("d-none");

    let client_html = "",
    client_account_html = "",
    last_payment_html = "",
    order_html = "";

    $.get({
      url: `/orders/${order_id}/details/list-live`,
      headers,
      data: {group_id,folder_id},
      success: function(d) {
        if(d.code === 200) {
          let content_data = d.data && d.data.invoice ? d.data.invoice : [],
          client = d.data && d.data.client ? d.data.client : [],
          invoice = d.data && d.data.invoice ? d.data.invoice : [],
          order_edit_statuses = d.data && d.data.invoice && d.data.invoice.order_edit_statuses ? d.data.invoice.order_edit_statuses : [],
          account = d.data && d.data.client && d.data.client.account ? d.data.client.account : [],
          last_payments = d.data && d.data.client && d.data.client.last_payments ? d.data.client.last_payments : [];

          order_html += content_data.list.map((d,i) => trOrderDetailsComponent(d, ++i));
          $(`[data-role="order-details-code"]`).text(invoice.code)
          $(`[data-role="order-details-code"]`).data("code", invoice.code)
          $(`[data-role="order-details-list-footer"]`).html(trOrderDetailsFooterComponent(invoice));

          $(`[data-role="entry-information"]`).find(`[data-name="name"]`).text(client.name +" ("+client.remote_customer_id+")"  ||  "");
          $(`[data-role="entry-information"]`).find(`[data-name="email"]`).text(client.email || "");
          // $(`[data-role="entry-information"]`).find(`[data-name="phone"]`).text(client.phone || "");
          $(`[data-role="entry-information"]`).find(`[data-name="comment"]`).text(client.comment ?? "");

          $(`[data-role="account-list"]`).find(`[data-name="payment-date"]`).text(client.account && client.account.last_payment_date ? client.account.last_payment_date : "");
          $(`[data-role="account-list"]`).find(`[data-name="payment-amount"]`).text(client.account && client.account.last_payment_amount ? number_format(client.account.last_payment_amount,2,",",".",0) : "");
          $(`[data-role="account-list"]`).find(`[data-name="left-debt"]`).text(client.account && client.account.debt_amount ? number_format(client.account.debt_amount,2,",",".",0) : "");

          $(`[data-role="order-edit-statuses"]`).prop("disabled", false);
          let order_edit_statuses_options = "";
          Object.keys(order_edit_statuses).map((v) => {
            order_edit_statuses_options += `<option value="${v}" ${order_edit_statuses[v] ? "selected" : ""} >${lang(v)}</option>`
          });
          $(`[data-role="order-edit-statuses"]`).html(order_edit_statuses_options);

          if(invoice.status === "finished" || invoice.status === "canceled"){
            $(`[data-role="order-edit-statuses"]`).prop("disabled", true);
          }
          $(`[data-role="order-status"]`).html(`
              <span class="badge badge-${invoice.status === "pending" ? "info" :
                                    (invoice.status === "confirmed" ? "confirmed" :
                                    (invoice.status === "finished" ? "success" :
                                    (invoice.status === "canceled" ? "danger" :
                                    (invoice.status === "on_the_way" ? "byorder" :
                                    (invoice.status === "partially_shipped" ? "warning" : "primary")))))}">
              ${invoice.status ? lang(invoice.status) : ""}</span>
            `);
          $(`[data-role="order-id"]`).val(invoice.id ?? "");

          last_payment_html += last_payments.map((d,i) => trLastPaymentsComponent(d, ++i));
          $(`[data-role="last-payment-list"]`).html(last_payment_html);
        } else if(d.code === 204) {
          order_html = warningComponent(d.message);
        }

        $(`[data-role="order-details-list"]`).html(order_html);
      },
      error: function(d){
        console.log(d);
      },
      complete: function(){
        $(`[data-role="order-details"]`).removeClass("loading");
        $(`[data-role="order-detail-component"]`).removeClass("d-none");

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    });

  };

  let checked_cols = {};

  const setOrdersToCheckedCols = (checked_col_orders) => {
    $(document).find(`[data-role="copy-details-checkbox"]`).data("order", "");
    if(checked_col_orders.length){
      checked_col_orders.map((v,i) => {
        i++;
        let col_element = $(document).find(`[data-role="copy-details-checkbox"][data-index="${parseInt(v)}"]`);
        col_element.data("order", i);
        col_element.closest("div").find(`[data-role="order-circle"]`).html(i);
      });
    }
  };

  const changeColChecked = (col) => {
    if(col.is(":checked")) {
      checked_cols[col.data("index") + "_"] = {
        index: col.data("index"),
        checked: "1",
      };
    } else {
      delete checked_cols[col.data("index") + "_"];
      col.closest("div").find(`[data-role="order-circle"]`).html("");
    }
    // console.log(checked_cols);
    let checked_col_orders = Object.keys(checked_cols);
    setOrdersToCheckedCols(checked_col_orders);
  };

  let first_checked_cols = {};
  $(`[data-role="order-details-header"] tr > th`).each(function() {
    let col = $(this).find(`[data-role="copy-details-checkbox"]`);
    if(col.is(":checked")){
      first_checked_cols[col.data("order")] = col;
    }
  });

  Object.keys(first_checked_cols).map((v) => {
    changeColChecked(first_checked_cols[v]);
  });

  $(document).on("click", `[data-role="copy-details-checkbox"]`, function() {
    changeColChecked($(this));
  });

  $(document).on("click", `[data-role="copy-brand-codes"]`, function (e) {
    let col_indexes = Object.keys(checked_cols),
    codes = [],
    type = $(this).data("type");

    if(!col_indexes.length){
      notify("warning", lang("check_columns_for_copy"));
      return;
    }

    $(`[data-role="order-details-list"] > tr`).each(function() {
      codes.push(col_indexes.map((v) => {
        v = parseInt(v);

        let text = $(this).find(`td:nth-child(${v+1})`).text(),
        td = $(this).find(`td:nth-child(${v+1})`);

        if(type === "standart" && (td.data("role") === "product-price" || td.data("role") === "product-total-price")) {

          text = text.replace(',','.');
        }

        if(type === "excel" && (td.data("role") === "product-oem" || td.data("role") === "product-brand-code")) {
          // text = `="${text}"`;
        }

        if(td.data("role") === "product-price"){
          text = text.trim();
        }

         return text;
       }).join("\t"));
    });

    let copy_text = codes.join("\r\n");

    if (!navigator.clipboard) {
      const elem = document.createElement('textarea');

      $(elem).html(copy_text);
      document.body.appendChild(elem);
      elem.select();

      document.execCommand("copy");
      document.body.removeChild(elem);
    } else {
      // console.log("navigator");
      navigator.clipboard.writeText(copy_text);
    }
    $(this).closest("div").find(".copy").addClass("copied");
    setTimeout(function() {
      $(".copy").removeClass("copied");
    }, 2000);

    $.ajax({
      url: `/orders/update-copy-check`,
      method: "PUT",
      headers,
      data: JSON.stringify({copy_check_indexes: checked_cols}),
      success: function(d){

      },
      error: function(d){
        console.log(d);
      }
    });
  });

  if ($(window).width() > 767){
    $(`[data-role="orders-list-parent"]`).resizable({
      handles: 'e, w'
    });
    $(`[data-role="orders-list-parent"]`).resize(function(){
      $(`[data-role="order-details"]`).width($(`[data-role="orders-main"]`).width()-$(`[data-role="orders-list-parent"]`).width());
    });
  }else{
    $(`[data-role="orders-main"]`).addClass("row");
  };

  $(document).on("click", `[data-role="confirm-order"]`, function() {
    let id = $(this).closest(`[data-role="order-list-component"]`).data("id"),
    order_list = $(this).closest(`[data-role="order-list-component"]`),
    text = $(this);

    ModalLoader.start(lang("Loading"));
    $.ajax({
      method: "PUT",
      url: `/orders/${id}/edit-status`,
      headers,
      data: JSON.stringify({group_id,status:"confirmed"}),
      success: function(d) {
        if(d.code === 202) {
          Swal.fire("", d.message, "success");
          text.remove();
          order_list.removeClass("unread");
          $(`[data-role="content-result-confirmed-count"]`).text(+$(`[data-role="content-result-confirmed-count"]`).text() + 1);

          order_list.find(`[data-role="order-status-component"]`).addClass("badge-info");
          order_list.find(`[data-role="order-status-component"]`).text(lang("confirmed"));
          $(document).find(`[data-role="order-status"]`).find("span").addClass("badge-info");
          $(document).find(`[data-role="order-status"]`).find("span").text(lang("confirmed"));
          $(document).find(`[data-role="order-edit-statuses"]`).val(status);
        } else {
          Swal.fire("", d.message, "warning");
        }
      },
      error: function(d) {
        console.log(d);
      },
      complete: function(){
        ModalLoader.end();
      }
    });
  });

  $(`[data-role="edit-order-status"]`).on("click", function() {
    let id = $(`[data-role="order-id"]`).val(),
    status = $(`[data-role="order-edit-statuses"]`).val();

    Swal.fire({
      title: lang("u_sure_change_order_status"),
      text: "",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true,
    }).then((result) => {
      if(result.isConfirmed){
        ModalLoader.start(lang("Loading"));
        $.ajax({
          method: "PUT",
          url: `/orders/${id}/edit-status`,
          headers,
          data: JSON.stringify({group_id,status}),
          success: function(d) {
            if(d.code === 202) {
              Swal.fire("", d.message, "success");

              $(`[data-role="content-result-confirmed-count"]`).text(+$(`[data-role="content-result-confirmed-count"]`).text() + 1);

              $(`[data-role="order-status"]`).html(`
                  <span class="badge badge-${status === "pending" ? "info" :
                                        (status === "confirmed" ? "confirmed" :
                                        (status === "finished" ? "success" :
                                        (status === "canceled" ? "danger" :
                                        (status === "on_the_way" ? "byorder" :
                                        (status === "partially_shipped" ? "warning" : "primary")))))}">
                  ${status ? lang(status) : ""}</span>
                `);
              let order_el_parent = $(document).find(`[data-role="orders-list"]`).find(`[data-id="${id}"]`),
                  order_status_el = order_el_parent.find(`[data-role="order-status-component"]`);

              order_status_el.prop("class", `badge badge-${status === "pending" ? "info" :
                                    (status === "confirmed" ? "confirmed" :
                                    (status === "finished" ? "success" :
                                    (status === "canceled" ? "danger" :
                                    (status === "on_the_way" ? "byorder" :
                                    (status === "partially_shipped" ? "warning" : "primary")))))}`);
              order_status_el.text(lang(status));

              if(status !== "pending") {
                order_el_parent.find(`[data-role="confirm-order"]`).addClass("d-none");
              } else {
                order_el_parent.find(`[data-role="confirm-order"]`).removeClass("d-none");
                if(!order_el_parent.find(`[data-role="confirm-order"]`).length){
                  order_el_parent.find(`[data-role="order-confirm-text-component"]`).html(`<p data-role="confirm-order" class="link m-0">${lang("Confirm")}</p>`)
                }
              }

              if(status === "finished") {
                $(document).find(`[data-role="order-edit-statuses"]`).prop("disabled", true);
              }
            } else {
              Swal.fire("", d.message, "warning");
            }
          },
          error: function(d) {
            console.log(d);
          },
          complete: function(){
            ModalLoader.end();
          }
        });
      }
    });

  });

  const getFoldersList = (el, order_id = null) => {
    let html = "",
    dropdown_list = el.parents(`[data-role="order-list-component"]`).find(`[data-role="dropwdown-folder-list"]`),
    count = 0;

    // dropdown_list.html(html);

    // el.addClass("show")
    // dropdown_list.addClass("show");
    // dropdown_list.addClass("loading-box");
    // dropdown_list.addClass("minh-50");
    $.get({
      url: `/orders/folders/list`,
      data: {order_id},
      headers,
      success: function(d){
        if(d.code === 200){
          let content_data = d.data && d.data.list ? d.data.list : [],
          totals = d.data && d.data.totals ? d.data.totals : [];
          count = d.data && d.data.count ? d.data.count : 0;

          html = content_data.map((v,i) => dropdownFolderListComponent(v,++i)).join("");


        } else if(d.code === 204){
          html = warningComponent(d.message);
        }

      dropdown_list.html(html);
      },
      error: function(d){
        console.log(d);
      },
      complete: function(){
        dropdown_list.removeClass("loading-box");
        // dropdown_list.removeClass("minh-50");

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    });
  }

  $(document).on("click", `[data-role="open-folder-dropdown"]`, function(){
    let
      el = $(this),
      folder_id = el.data("id"),
      dropdown_list = el.parents(`[data-role="order-list-component"]`).find(`[data-role="dropwdown-folder-list"]`),
      order_id = el.parents(`[data-role="order-list-component"]`).data("id");

      dropdown_list.addClass("loading-box");
      getFoldersList($(this),order_id);

  });

  $(document).on("click", `[data-role="add-order-to-folder"]`, function() {
    let
      el = $(this),
      folder_id = el.data("id"),
      dropdown_list = el.parents(`[data-role="order-list-component"]`).find(`[data-role="dropwdown-folder-list"]`),
      order_id = el.parents(`[data-role="order-list-component"]`).data("id");

    if(folder_id && order_id && !(+el.data("has-order"))) {

      dropdown_list.addClass("loading-box");
      $.post({
        url: `/orders/folders/${folder_id}/add-order`,
        headers,
        data: {order_id, folder_id},
        success: function(d){
          getFoldersList(el,order_id);
        },
        error: function(d){

        },
        complete: function(){
          // dropdown_list.removeClass("loading-box");
        }
      });
    }
  });


  $(document).on("click", `[data-role="transfer-order"]`, function() {
    let id = $(`[data-role="order-id"]`).val();
    ModalLoader.start(lang("Loading"));
    $.get({
      url: `/order-groups/list`,
      headers,
      success: function(d){
        if(d.code === 200) {
          let content_data = d.data ?? [],
          group_options = "";

          group_options  += content_data.map((v,i) => {
            return `<option value="${v.id}"
                >${v.name}</option>`;
          }).join("");

          getOrderDetailsTransferModal(id,group_id,group_options);
        }
      },
      error: function(d){

      },
      complete: function(d){
        ModalLoader.end();
      }
    });

    const trTransferOrderComponent = (d,i) => {
      if(+d.quantity > 0) {
        return `<tr data-id="${d.id}">
                <td>${i}</td>
                <td><input class="c-pointer" data-role="status-check" type="checkbox"></td>
                <td>${d.brand && d.brand.name ? d.brand.name : ""}</td>
                <td>${d.delivery_time || ""}</td>
                <td>${d.product && d.product.model || ""}</td>
                <td data-role="product-oem" >${d.brand && d.brand.org_code ? d.brand.org_code : ""}</td>
                <td>${d.product && d.product.stock_baku || ""}</td>
                <td>${d.product && d.product.stock_baku_2 || ""}</td>
                <td>${d.product && d.product.stock_ganja || ""}</td>
                <td  data-role="product-brand-code" data-brand-code="${d.brand && d.brand.code ? d.brand.code : ""}" >${d.brand && d.brand.code ? d.brand.code : ""}</td>
                <td style="max-width:50px;"  data-quantity="${d.quantity ?? ""}" >
                  <input data-role="order-transfer-quantity" min="1" type="number" data-max="${+d.quantity}" class="form-control" value="${+d.quantity}" />
                </td>
              </tr>`;
      }
    }

    const getOrderDetailsTransferModal = (id, group_id,group_options) => {
      ModalLoader.start(lang("Loading"));
      $.get({
        url: `/orders/${id}/details/list-live`,
        headers,
        data:{group_id},
        success: function(d){
          if(d.code === 200) {
            let content_data = d.data && d.data.invoice && d.data.invoice.list ? d.data.invoice.list : [];

            Swal.fire({
              html: `
              <div data-role="invoice-modal" class="row">
                    <div class="col-md-12" style="font-size:14px;">
                        <div class="card-box">
                            <p class="text-start mb-2">
                               <b>${$(`[data-role="order-details-code"]`).data("code")}</b> ${lang("Transfer this order")}
                            </p>
                            <div class="row">
                              <div class="col-md-4">
                                <select class="custom-select" data-role="transfer-order-groups">
                                  ${group_options}
                                </select>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table  class="table mt-4 table-centered table-bordered">
                                            <thead>
                                              <tr>
                                                <th style="" class="border-bottom-0">#</th>
                                                <th style="width:1%;"><input class="c-pointer" data-role="check-all" type="checkbox"></th>
                                                <th class="border-bottom-0">${lang("Brand")}</th>
                                                <th class="border-bottom-0">${lang("Day")}</th>
                                                <th class="border-bottom-0">${lang("Model")}</th>
                                                <th class="border-bottom-0">${lang("OEM")}</th>
                                                <th class="border-bottom-0">${lang("baku")}</th>
                                                <th class="border-bottom-0">${lang("ganja")}</th>
                                                <th class="border-bottom-0">${lang("Brand code")}</th>
                                                <th class="border-bottom-0">${lang("Quantity")}</th>
                                              </tr>
                                            </thead>
                                            <tbody data-role="table-transfer-list">
                                              ${content_data.map((v,i) => trTransferOrderComponent(v,++i)).join("")}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div>
                              <p class="d-none alert alert-warning text-start mb-2" data-role="transfer-warning-message" ></p>
                            </div>
                        </div>

                    </div>
                </div>
          `,
          customClass: 'swal-invoice-wide',
          showCancelButton: true,
          confirmButtonText: lang("confirm"),
          cancelButtonText: lang("close"),
                }).then((result) => {

                  $(document).find(`[data-role="transfer-warning-message"]`).addClass("d-none");
                  $(document).find(`[data-role="transfer-warning-message"]`).text("");

                  if(result.isConfirmed) {

                    let order_detail_list = [],
                    transfer_group_id = $(document).find(`[data-role="transfer-order-groups"]`).val();

                    $(document).find(`[data-role="table-transfer-list"] tr`).each(function() {
                      if($(this).find(`[data-role="status-check"]`).is(":checked")) {
                        order_detail_list.push({
                          "id": $(this).data("id"),
                          "quantity": $(this).find(`[data-role="order-transfer-quantity"]`).val()
                        });
                      }
                    });

                    // console.log(order_detail_list);return;
                    ModalLoader.start(lang("Loading"));
                    $.ajax({
                      url: `/orders/${id}/transfer-order`,
                      method: "PUT",
                      data: JSON.stringify({order_detail_list, group_id: transfer_group_id}),
                      headers,
                      success: function(d){
                        if(d.code === 202) {
                          offset = 0;
                          getContent({start_date, end_date, group_id, keyword, offset, status, no_date_filter, folder_id});
                          getOrderDetails(id, folder_id);
                          Swal.fire("",  d.message, "success");
                        } else {
                          Swal.fire("", d.message, "warning");
                        }
                      },
                      error: function(d){

                      },
                      complete: function() {
                        ModalLoader.end();
                      }
                    });
                  }
              });

          }
        },
        error: function(d){

        },
        complete: function(d){
          ModalLoader.end();
        }
      });
    }
  });

  $(document).on("change", `[data-role="order-transfer-quantity"]`, function(){
    if(+$(this).val() > +$(this).data("max")) {
      $(this).val($(this).data("max"));
    }
    if(+$(this).val() <= 0) {
      $(this).val(1);
    }
  });

  $(document).on("click", `[data-role="check-all"]`, function() {
    $(document).find(`[data-role="status-check"]`).prop("checked", this.checked);
    headerCalculator();
  });

  $(document).on("click", `[data-role="status-check"]`, function() {
    let ischecked= $(this).is(':checked');
    if(!ischecked) {
      $(`[data-role="check-all"]`).prop("checked", false);
    }
  });

});
