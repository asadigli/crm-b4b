"use strict";

$(function(){

  const updateCache = (data,callback) => {
    ModalLoader.start(lang("Loading"));

    $.ajax({
      url: `/caches/refresh`,
      data: JSON.stringify(data),
      headers,
      method: "PUT",
      success: function(d) {
        if(d.code === 202) {
          let date = d.data ?? "";
          Swal.fire(d.message, "", "success");
          if (typeof callback === "function") {
            callback(d.data);
          }
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
  }

  $(`[data-role="refresh-cache"]`).on("click", function() {
    let cache_type = $(this).data("type"),date = new Date(),time_selector = $(this).parents("tr").find(`[data-role="lastupdate-date"]`);
    let currentDate = date.toISOString().substring(0,10),
        firstDay = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().substring(0,10);

    Swal.fire({
      title: lang("Reset cache"),
      icon: "info",
      html:
      `<div class="form-group">
          <label>${lang("Limit hour")}</label>
          <input autocomplete="off" type="number" name="limit_hour" class="form-control" placeholder="${lang("Limit hour")}">
        </div>
        <div class="form-group">
          <label>${lang("Start date")}</label>
          <input autocomplete="off" type="date" name="start_date" class="form-control" value=${firstDay}>
        </div>
        <div class="form-group">
          <label>${lang("End date")}</label>
          <input autocomplete="off" type="date" name="end_date" class="form-control" value=${currentDate}>
        </div>`,
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText: lang("Confirm"),
      cancelButtonText: lang("Cancel"),
    }).then((result) => {
      if (result.isConfirmed) {
        updateCache({
          type: cache_type,
          limit_hour: $(`[name="limit_hour"]`).val(),
          start_date: $(`[name="start_date"]`).val(),
          end_date: $(`[name="end_date"]`).val(),
        },(d) => {
          time_selector.html(typeof d.date !== "undefined" ? d.date : d)
        });

      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
    });

  });

  $(document).on("click",`[data-role="refresh-cache-last-hour"]`,function(){
    updateCache({
      type: $(this).data("type"),
      limit_hour: 5,
    },() => {
      getDashboardReports();
    });
  });
});
