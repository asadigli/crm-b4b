$(function(){

  let return_modal = "#returnModal";

  $(document).on("click",`[data-role="return"]`,function(){
    $("#ReverseQuantity").prop('max',$(this).data("quantity"));
    $("#ReverseInvoice").text($(this).data("invoice"));
    $("#ReverseQuantity").val($(this).data("quantity"));
    $("#ReverseName").text($(this).data("name"));
    $("#ReverseCode").text($(this).data("code"));
    $("#ReverseSpecode3").text($(this).data("specode3"));
    $("#ReverseSpecode2").text($(this).data("specode2"));
    $("#ReverseSpecode").text($(this).data("specode"));
    $("#ReversePrice").val($(this).data("price"));

    $(`${return_modal} [data-name="id"]`).val($(this).parents("tr").data("id"));
  });


  $(document).on("click",`[data-role="return-btn"]`,function(e){
    let data = {
      id: $(`${return_modal} [data-name="id"]`).val(),
      quantity: $("#ReverseQuantity").val(),
      description: $("#ReverseDesc").val(),
    };

    // console.log(data);
    // return ;

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.post({
      url: `/orders/returns/add`,
      data,
      headers,
      success: function(d){
        // console.log(d);
        $(return_modal).modal("hide");
        Swal.fire(
          d.message,
          '',
          d.code === 201 ? "success" : "warning"
        );
        if (d.code === 201) {
          Loader.btn({
    				element: e.target,
    				action: "success"
    			});
        } else {
          Loader.btn({
    				element: e.target,
    				action: "warning"
    			});
        }
      },
      error: function(d){
        console.error(d);
        Loader.btn({
  				element: e.target,
  				action: "error"
  			});
      },
      complete: function(){
        Loader.btn({
          element: e.target,
          action: "end"
        });
      }
    });

  });


  $(document).on("click", `[data-role="excel-export"]`, function() {
  disableAll(true);
  Swal.fire({
    title: lang("are_yu_sure_export_excel_operation"),
    text: "",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: lang("Yes"),
    cancelButtonText: lang("No"),
    reverseButtons: true,
  }).then((result) => {
    if(result.isConfirmed) {

      let currentdate = new Date();
      let filename = currentdate.getDate() +""
                  + (currentdate.getMonth()+1)  + ""
                  + currentdate.getFullYear() + "-"
                  + currentdate.getHours() + ""
                  + currentdate.getMinutes() + ""
                  + currentdate.getSeconds() +  "-" + "ACCOUNT_DETAILS-CRM";

      let data = {
        name: filename,
        key: "account_invoice_details",
        params: window.location.href,
      };

      $.post({
        url: "/file-export/add-to-history",
        data,
        headers,
        success: function(d) {
          if(d.code === 201) {
            let tableClone = $(`[id="account_details_table"]`).clone();
            tableClone
               .find(".no-export")
               .each(function(){
                 $(this).remove();
               });
            tableClone.attr("id", "cart_clone_table");
            $("body").append(tableClone);

            export_table("cart_clone_table",filename);
            tableClone.remove();
          }
        },
        error: function(d) {
          console.log(d);
        },
        complete: function(d) {

        }
      });
    }
  })
  disableAll(false);
});

});
