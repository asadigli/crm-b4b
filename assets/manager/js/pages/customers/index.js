"use strict";
$(function(){

  let offset = 0,
      excel_export = 0,
      limit = 100,
			loading = false,
			loading_completed = false,
			total_shown_product_count = 0,
      interval = null;

  const clearTable = ({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export, is_inactive_customers, inactive_customers}) => {
    $(`[data-role="select-end-date"]`).val(end_date);
    $(`[data-role="select-start-date"]`).val(start_date);

    $(`[data-role="table-list"]`).html("");
    $(`[data-role="content-result-tla"]`).html(" 0 ");
    $(`[data-role="content-result-count"]`).html(" 0 ");
    $(`[data-role="content-result-time"]`).html(" 0 ");
    $(`[data-role="table-loader"]`).addClass("d-none");

    getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export, is_inactive_customers, inactive_customers});
  };

  const initializeHeaders = () => {
    keyword = $(`[data-role="search-keyword"]`).val().trim();
    due_date = $(`[data-role="due_date"]`).val();
    city_id = $(`[data-role="select-city"]`).val().trim();
    currency_id = $(`[data-role="select-currency"]`).val().trim();
    search_by_debts = $(`[data-role="search_by_debts"]`).val().trim();
    customer_type = $(`[data-role="customer_type"]`).val().trim();
    status = $(`[data-role="status"]`).val().trim();
    is_inactive_customers = $(`[data-role="is-inactive-customers"]`).is(":checked") ? "1" : "0";

    if(is_inactive_customers === "1") {
      inactive_customers = $(`[data-role="inactive-customers"]`).val().trim();
      if(!inactive_customers.trim()) {
        inactive_customers = 180;
      }
    } else {
      inactive_customers = "";
    }

  }

  const trComponent = (d,i,offset) => {

    let entryInfo = Array.isArray(d.entry_info)
     ? d.entry_info.map(entry => `<b>${entry[0]}</b>: ${entry[1]}`).join('<br>')
     : '';

    return `
        <tr data-id="${d.id}" >
          <th>${i + offset}</th>
          <td><a href="${d.id ? `/customers/${d.id}/account` : "javascript:void(0)"}" target="_blank" class="link" >${d.name ?? ""}</a></td>
          <td><a href="${d.id ? `/customers/${d.id}/account` : "javascript:void(0)"}" target="_blank" class="link" >${d.code ?? ""}</a></td>
          <td>${entryInfo ?? ""}</td>
          <td>${d.currency ?? ""}</td>
          <td>${d.city_name ?? ""}</td>
          <td style="text-align:right;" >${d.monthly_sale_amount ? number_format(d.monthly_sale_amount ?? 0,2,",",".",0)  : "0,00"}</td>
          <td style="text-align:right;" >${d.monthly_payment_amount ? number_format(d.monthly_payment_amount ?? 0,2,",",".",0)  : "0,00"}</td>
          <td>${d.remote_id ?? ""}</td>
          <td style="text-align:right;" >${d.sale_amount ? number_format(d.sale_amount ?? 0,2,",",".",0)  : "0,00"}</td>
          <td style="text-align:right;" >${d.payment_amount ? number_format(d.payment_amount ?? 0,2,",",".",0)  : "0,00"}</td>
          <td>${d.last_sale_date ?? ""}</td>
          <td>${d.last_payment_date ?? ""}</td>
          <td style="text-align:right;" >${d.left_amount ? number_format(d.left_amount ?? 0,2,",",".",0)  : "0,00"}</td>
          <td>
            <div class="custom-control custom-checkbox badge badge-${d.is_blocked ? "danger" : "success"}">
              <input type="checkbox" data-role="is-blocked" id="${d.id}_block" ${d.is_blocked ? "checked" : ""}>
              <label for="${d.id}_block">${+d.is_blocked ? lang("Blocked") : lang("Unblocked")}</label>
            </div>
          </td>
        </tr>`;
    };



  const getContent = (urlParams,loadmore = false) => {
    let start_time = new Date().getTime(), html = "", count = 0, total_left_amount = 0;

    let {keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export, is_inactive_customers, inactive_customers} = urlParams;

    if (excel_export) {
      ModalLoader.start(lang("Loading"));
    }

    filter_url([
      {keyword: (keyword || "")},
      // {is_azn_customers: +is_azn_customers ? (is_azn_customers || "") : ""},
      {city_id: (city_id || "")},
      {currency_id: (currency_id || "")},
      {search_by_debts: (search_by_debts || "")},
      {due_date: (due_date || "")},
      {customer_type: (customer_type || "")},
      {status: (status || "")},
      {inactive_customers: (inactive_customers || "")},
      {is_inactive_customers: +is_inactive_customers ? (is_inactive_customers || "") : ""},
    ]);


    if (!loadmore) {

      $(`[data-role="content-result-tla"]`).html("0");
      $(`[data-role="content-result-count"]`).html("0");
      $(`[data-role="content-result-time"]`).html("0");


      ModalLoader.start(lang("Loading"));
      $(`[data-role="search-filter"]`).find("i").addClass("fa-spinner").addClass("fa-spin");
      total_shown_product_count = 0;
      loading_completed = false;
      offset = 0;

      clearInterval(interval);
      interval = setInterval(function () {
        $(`[data-role="content-result-time"]`).html(
          (new Date().getTime() - start_time) / 1000
        );
      }, 100);
    }


    // console.log({keyword, city_id, currency_id, search_by_debts, due_date, offset});
    $.get({
      url: `/customers/list-live`,
      headers,
      data: {keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, offset, excel_export, is_inactive_customers, inactive_customers},
      success: function(d){
        if (excel_export) {
          // console.log(d);
          if (d.code === 200) {
            let url = d.data.url;
            location.href = url;
            excel_export = 0;
            getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status});
          } else {
            Swal.fire(lang("Error"),"","warning");
          }
        } else {
        if (d.code === 200) {
          $(`[data-role="excel-export"]`).removeClass("avh-disable")
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;
          total_left_amount = d.data && d.data.total_left_amount ? d.data.total_left_amount : 0;

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

          html = content_data.map((v,i) => trComponent(v,++i,offset)).join("");
          loading_completed = total_shown_product_count >= d.data.count;
        }
        else if (d.code === 204) {
          html = warningComponent(d.message);
          $(`[data-role="excel-export"]`).addClass("avh-disable")
        }
        else {
          $(`[data-role="excel-export"]`).addClass("avh-disable")
          html = warningComponent(d.message);
          Swal.fire("", d.message, "warning");
          console.log(d);
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
          $(`[data-role="content-result-tla"]`).html(number_format(total_left_amount,2,".",""));
          $(`[data-role="content-result-count"]`).html(count);
          $(`[data-role="table-list"]`).html(html);
        }
        ModalLoader.end();

      }
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){
        ModalLoader.end();
        loading = false;

        $(`[data-role="search-filter"]`).find("i").removeClass("fa-spinner").removeClass("fa-spin");

        if(!loadmore) {
          clearInterval(interval);
          $(`[data-role="content-result-time"]`).html(
            ((new Date().getTime() - start_time) / 1000)
          );

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

  let keyword = getUrlParameter("keyword"),
      city_id = getUrlParameter("city_id") || "",
      due_date = getUrlParameter("due_date") || $(`[data-role="due_date"]`).val(),
      search_by_debts = getUrlParameter("search_by_debts") || "",
      customer_type = getUrlParameter("customer_type") || "",
      status = getUrlParameter("status") || $(`[data-role="status"]`).val(),
      is_inactive_customers = getUrlParameter("is_inactive_customers") || "",
      inactive_customers = getUrlParameter("inactive_customers") || "",
      currency_id = getUrlParameter("currency_id") || "";
  // is_azn_customers = getUrlParameter("is_azn_customers");

  getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, is_inactive_customers, inactive_customers});

  $(`[data-role="search-keyword"],
      [data-role="select-start-date"],
      [data-role="select-end-date"],
      [data-role="due_date"],
      [name="inactive_customers"]`).on("keypress", function(e){

    if (e.which === 13) {
        initializeHeaders();
        getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export, is_inactive_customers, inactive_customers});
      }
  });

  $(document).on("change", `[data-role="is-blocked"]`, function() {
    let $input = $(this),
        id = $input.parents("tr").data("id"),
        is_blocked = $input.prop("checked") ? "1" : "0",
        are_u_sure_edit_is_blocked_message = is_blocked === "1" ? lang("Are you sure block this customer") : lang("Are you sure deblock this customer");


    Swal.fire({
      title: are_u_sure_edit_is_blocked_message,
      showCancelButton: true,
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        customLoader();
        $.ajax({
          url: `/customers/${id}/edit-is-blocked`,
          type: "PUT",
          headers,
          data: JSON.stringify({ is_blocked }),
          success: function (d) {
            Swal.fire("", d.message, d.code === 202 ? "success" : "warning");
            $input.closest("div").prop("class", "custom-control custom-checkbox badge badge-danger");
            $input.siblings("label").text(lang("Blocked"));

            // getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export, is_inactive_customers, inactive_customers});
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            customLoader(true)
          }
        })
      } else {
        $input.prop("checked", !$th.is(":checked"))
      }
    });
  });



  const getCities = () => {
  $(`[name="cities"]`).parents(".form-group").addClass("loader");
  let html = "";

  $.get({
      url: `/customers/city-list`,
      headers,
      cache: true,
      success: function (d) {
        if (d.code === 200) {
          let content_data = d.data ?? [];
          html = content_data.map((v, i) => `<option value="${v.city_id}" ${city_id === v.city_id ? " selected" : ""}>${(v.city_name ? v.city_name : lang("without_cities"))}</option>` ).join("");
          $(`[name="cities"]`).attr("disabled",false);
        } else if (d.code === 204) {
          html = "";
        }
        else {
          html = "";
          Swal.fire("", d.message, "warning");
        }
        $(`[name="cities"]`).html(`<option value="">${lang("All cities")}</option>`+html);
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        $(`[name="cities"]`).parents(".form-group").removeClass("loader");
      },
    });
  };
  getCities();

  const getCurrencies = () => {
    $(`[name="currencies"]`).parents(".form-group").addClass("loader");
    let html = "";

    $.get({
        url: `/currencies/list`,
        headers,
        cache: true,
        success: (d) => {
          if (d.code === 200) {
            let content_data = d.data ?? [];
            html = content_data.map((v, i) => `<option value="${v.id}" ${currency_id === v.id ? " selected" : ""}>${(v.main_name ? v.main_name : lang("without_currencies"))}</option>` ).join("");
            $(`[name="currencies"]`).attr("disabled",false);
          } else {
            Swal.fire("", d.message, "warning");
          }
          $(`[name="currencies"]`).html(`<option value="">${lang("All currencies")}</option>`+html);
        },
        error: (d) => {
          console.error(d);
        },
        complete: () => {
          $(`[name="currencies"]`).parents(".form-group").removeClass("loader");
        },
      });
    };
    getCurrencies();

  $(document).on("click", `[data-role="search-filter"]`, function() {
    initializeHeaders();
    getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export, is_inactive_customers, inactive_customers});
  });


  $(document).on("scroll", function(){
    if (!$("#load_more_div").isInViewport() || loading_completed || loading) return false;
		$("#load_more_div").addClass("loading");
		offset = $(`[data-role="table-list"] > tr`).length;
		loading = true;
    getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export},true);
  });

	$(document).on("click",`[data-role="load-more"]`,function(){
		if (loading_completed || loading) return false;
		$("#load_more_div").addClass("loading");
		offset = $(`[data-role="table-list"] > tr`).length;
		loading = true;
    getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export},true);
	});

  $(document).on("click",`[data-role="excel-export"]`,function(){
    if(
      !customer_type.trim() &&
      !currency_id.trim() &&
      !city_id.trim() &&
      !due_date.trim() &&
      !keyword.trim() &&
      !status.trim()
      ) {
      Swal.fire("", lang("minimum_one_parameter"), "warning");
      return;
    }

    let tableList = $("#customer_list_tbody > tr").length;
    if (tableList.length <= 1) {
      Swal.fire("", lang("no result to excel export"), "error");
      return;
    }
    getContent({keyword, city_id, currency_id, search_by_debts, due_date, customer_type, status, excel_export: 1});
  });


});
