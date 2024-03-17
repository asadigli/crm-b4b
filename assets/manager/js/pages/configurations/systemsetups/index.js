"use strict";

$(function(){
  $(document).on("click", `[data-role="refresh-local-cache"]`, function() {

    Swal.fire({
    title: lang("u_sure_delete_file_caches"),
    text: "",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: lang("No"),
    confirmButtonText: lang("Yes"),
    reverseButtons: true,
  }).then((result) => {
    if(result.isConfirmed) {
        $(`[data-role="home-page"]`).find(`[data-role="table-loader"]`).removeClass("d-none");
        ModalLoader.start(words["Loading"]);
        $.get({
          url: `/configurations/system-setups/refresh-local-cache`,
          headers,
          success: function(d) {
            if(d.code === 200) {
              Swal.fire("", d.message, "success");
            }
          },
          complete: function(){
            $(`[data-role="home-page"]`).find(`[data-role="table-loader"]`).addClass("d-none");
            ModalLoader.end();
          }
        });
      }
    });
  });

  $(document).on("click", `[data-role="clear-local-sessions"]`, function() {

    Swal.fire({
    title: lang("u_sure_delete_local_sessions"),
    text: "",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: lang("No"),
    confirmButtonText: lang("Yes"),
    reverseButtons: true,
  }).then((result) => {
      if(result.isConfirmed) {
        $(`[data-role="home-page"]`).find(`[data-role="table-loader"]`).removeClass("d-none");
        ModalLoader.start(words["Loading"]);
        $.get({
          url: `/configurations/system-setups/clear-local-sessions`,
          headers,
          success: function(d) {
            if(d.code === 200) {
              Swal.fire("", d.message, "success");
            }
          },
          complete: function(){
            $(`[data-role="home-page"]`).find(`[data-role="table-loader"]`).addClass("d-none");
            ModalLoader.end();
          }
          });
      }
    });
  });

});
