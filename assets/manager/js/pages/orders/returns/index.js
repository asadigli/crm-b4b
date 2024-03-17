"use strict";
$(function(){

  const clearTable = ({start_date, end_date}) => {
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

    $(`[data-role="table-list"]`).html("");
    $(`[data-role="content-result-count"]`).html(" 0 ");
    $(`[data-role="content-result-time"]`).html(" 0 ");
    $(`[data-role="table-loader"]`).addClass("d-none");

    getContent({start_date, end_date});
  };

  const initializeHeaders = () => {
    start_date = $(`[data-role="select-start-date"]`).val().trim();
    end_date = $(`[data-role="select-end-date"]`).val().trim();
  }

  const trComponent = (d,i) => {
    return `
      <tr>
        <th>${i}</th>
        <td>${d.code ? `<a target="_blank" href="${d.id ? "/orders/returns/" + d.id + "/details" : "javascript:void(0)"}" class="link" >${d.code ?? ""}</a>` :""}</td>
        <td>${d.opeartion_date ?? ""}</td>
        <td>${d.depo_name ?? ""}</td>
        <td>
            <span class="badge badge-${d.status === "pending" ? "info" :
                                      (d.status === "confirmed" ? "confirmed" :
                                      (d.status === "shipped" ? "success" :
                                      (d.status === "canceled" ? "danger" :
                                      (d.status === "on_the_way" ? "byorder" :
                                      (d.status === "partially_shipped" ? "warning" : "primary")))))}">
              ${d.status ? lang(d.status) : ""}
            </span>
        </td>
        <td>${number_format(d.product_count,0,",",".",0)}</td>
        <td>${number_format(d.amount,2,",",".",0) + (d.currency ? " " + d.currency : "")}</td>
      </tr>`;
  };
  const warningComponent = (message) => {
    return `<tr>
              <td style="padding:0;margin:0;" colspan="200">
                <div class="d-flex justify-content-center" >
                  <div style="color: #676689!important;" class="alert" >
                      <strong>${message || ""}</strong>
                  </div>
                </div>
              </td>
            </tr>`;
  };

  let interval = null;
  const getContent = (urlParams) => {

    $(`[data-role="content-result-count"]`).html("0");
    $(`[data-role="content-result-time"]`).html("0");

    let {start_date, end_date} = urlParams;

    filter_url([
      {start_date: (start_date || "")},
      {end_date: (end_date || "")},
    ]);

    if (!(start_date && end_date)) {
      Swal.fire("", lang(minimum_date_should_selected_parameter), "warning");
      return;
    }

    ModalLoader.start(lang("Loading"));
    $(`[data-role="search-filter"]`).find("i").addClass("fa-spinner").addClass("fa-spin");

    let start_time = new Date().getTime(),
    html = "",
    count = 0;

    clearInterval(interval);
    interval = setInterval(function () {
      $(`[data-role="content-result-time"]`).html(
        (new Date().getTime() - start_time) / 1000
      );
    }, 100);

    $.get({
      url: `/orders/returns/list-live`,
      headers,
      data: {start_date, end_date},
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;

          html = content_data.map((v,i) => trComponent(v,++i)).join("");
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
        ModalLoader.end();

        $(`[data-role="search-filter"]`).find("i").removeClass("fa-spinner").removeClass("fa-spin");

        clearInterval(interval);
        $(`[data-role="content-result-time"]`).html(
          ((new Date().getTime() - start_time) / 1000)
        );

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
    end_date = getUrlParameter("end_date") || $(`[data-role="select-end-date"]`).val();

  getContent({start_date, end_date});

  $(`[data-role="search-keyword"], [data-role="select-start-date"], [data-role="select-end-date"]`).on("keypress", function(e){

    if (e.which === 13) {
        initializeHeaders();
        getContent({start_date, end_date});
      }
  });

  $(document).on("click", `[data-role="search-filter"]`, function() {
    initializeHeaders();
    getContent({start_date, end_date});
  });

});
