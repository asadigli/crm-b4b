"use strict";
$(function(){
  const initializeParameters = () => {
    date = $(`[name="date"]`).val();
    log_path = $(`[name="log_path"]`).val();
  };

  const trComponent = (data,index) => {
    return `
      <tr>
        <th scope="row"> ${index+1} </th>
        <td>${data.type ?? ""}</td>
        <td>${data.datetime ?? ""}</td>
        <td>${data.title ?? ""}</td>
        <td>${data.body ?? ""}</td>
      </tr>`;

  };



  const getContent = (urlParams) => {
    $(`[data-role="table-loader"]`).removeClass("d-none");
    $(`.table-responsive`).addClass("load");

    let { date } = urlParams;
    filter_url([
      {date: (date || "")},
      {log_path: (log_path || "")},
    ]);

    let h = '',
    count = 0;

    ModalLoader.start(lang("Loading"));
    $.get({
      url: `/configurations/error-logs/list-live`,
      headers,
      data: urlParams,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count && d.data.count ? d.data.count : 0;

          h = content_data.map((v,i) => trComponent(v,i)).join("");
        }
        else if (d.code === 204) {
          h = warningComponent(d.message);
        }
        else {
            console.log(d);
        }

        $(`[data-role="content-result-count"]`).html(count);
        $(`[data-role="table-list"]`).html(h);
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){
        $(`[data-role="table-loader"]`).addClass("d-none");
        $(`.table-responsive`).removeClass("load");

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find("button").on("blur", function() {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
        ModalLoader.end();
      }
    })
  }

  let date = getUrlParameter("date") || $(`[name="date"]`).val(),
      log_path = getUrlParameter("log_path") || $(`[name="log_path"]`).val();

  getContent({date,log_path});

  const logPaths = () => {
    $(`[name="log_path"]`).parents(".form-group").addClass("loader");
    let html = "";
    $.get({
      url: `/configurations/error-logs/paths`,
      headers,
      success: function (d) {
        if (d.code === 200) {
          let content_data = d.data.list ?? [];
          html = content_data.map((v, i) => `<option value="${v.value}"${log_path === v.value ? " selected" : ""}>${(v.name ?? "")}</option>` ).join("");
          $(`[name="log_path"]`).attr("disabled",false);
        }
        else if (d.code === 204) {
          html = "";
        }
        else {
          html = "";
          Swal.fire("", d.message, "warning");
        }
        $(`[name="log_path"]`).html(`<option value="">${lang("logs_paths")}</option>`+html);
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        $(`[name="log_path"]`).parents(".form-group").removeClass("loader");
      },
    });
  }
  logPaths();

  $(document).on("click",`[data-role="search-filter"]`,function(e){
    e.preventDefault();
    initializeParameters();
    getContent({date,log_path});
  });

  $(document).on("keyup",`[type="date"]`,function(e){
    if(e.which == 13){
      initializeParameters();
      getContent({date,log_path});
    }
  });

});
