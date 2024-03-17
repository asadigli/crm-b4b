"use strict";

$(function(){
  const initializeHeaders = () => {
    keyword = $(`[data-role="search-keyword"]`).val().trim();
  };

  const trComponent = (d, i) => {
    return `<tr data-id="${d.id}" class="${d.deleted_at ? "text-danger" : ""}" >
      <td>${i}</td>
      <td><a href="${!d.deleted_at && d.params ? "/products/search?discount_package_id="+d.id : "javascript:void(0)"}" target="_blank" class="link" >${d.code ?? ""}</a></td>
      <td >
        ${d.name ?? ""}
      </td>
      <td >
        ${d.discount_rate ? (+d.discount_rate + "%") : ""}
      </td>
      <td>
        <div style="float:right;">
          ${d.eur_last_purchase_price ? (number_format(d.eur_last_purchase_price, 2,",",".",0) + " " + d.eur_last_purchase_currency) : ""}
        </div>
        <div style="float:right;">
          ${d.azn_last_purchase_price ? (number_format(d.azn_last_purchase_price, 2,",",".",0) + " " + d.azn_last_purchase_currency) : ""}
        </div>
      </td>
      <td>${d.deleted_at ? "" : (d.product_count ?? "")}</td>
      <td>${d.operation_date ?? ""}</td>
      <td>
        ${d.deleted_at ? "" :
            `<button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete" type="button" class="btn btn-danger ms-2">
                <i class="fas fa-trash"></i>
            </button>`}
      </td>
    </tr>`;
  };

  let count = 0,
  interval = null;

  const getContent = (urlParams) => {
    let {keyword} = urlParams;

    filter_url([
      {keyword: (keyword || "")},
    ]);

    $(`[data-role="search-keyword"]`).val(keyword);

    let html = "",
    start_time = null;

    $(`[data-role="content-result-count"]`).html("0");
    $(`[data-role="content-result-time"]`).html("0");

    start_time = new Date().getTime();
    clearInterval(interval);
    interval = setInterval(function () {
      $(`[data-role="content-result-time"]`).html(
        (new Date().getTime() - start_time) / 1000
      );
    }, 100);

    ModalLoader.start(lang("Loading"));

    $.get({
      url: `/products/discount-packages/list-live`,
      headers,
      data: urlParams,
      success: function (d) {
        // console.log(d);
        if (d.code === 200) {
          let content_data = d.data && d.data.list ? d.data.list : [];
          count = d.data && d.data.count ? d.data.count : 0;
          html = content_data.map((v, i) => trComponent(v, ++i)).join("");
          $(`[data-role="table-list"]`).html(html);

        }
        else if (d.code === 204) {
          html = warningComponent(d.message);
        }
        else {
          html = warningComponent(d.message);
        }
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
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
        ModalLoader.end();
      },
    });
  };

  let keyword = getUrlParameter("keyword") || "";

  getContent({keyword});

  $(`[data-role="search-keyword"]`).on("keypress", function(e){
    initializeHeaders();
    if(e.which === 13){
      getContent({keyword});
    }
  });

  $(`[data-role="search-filter"]`).on("click", function(){
    initializeHeaders();
    getContent({keyword});
  });

  $(document).on("click", `[data-role="delete"]`, function(e){
    let id = $(this).parents("tr").data("id");

    e.preventDefault();

    Swal.fire({
      title: lang("Are you sure to delete this discount package?"),
      text: "",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        ModalLoader.start(lang("Loading"));
        $.ajax({
          url: `/products/discount-packages/${id}/delete`,
          type: 'delete',
          headers,
          data: JSON.stringify({id}),
          success: function(d){
            if(d.code === 200) {
              notify("success", d.message);
              getContent({keyword});
            } else {
              notify("warning", d.message);
            }
          },
          error: function(d){
            console.error(d);
            Swal.fire(lang("Error"),"",'error');
          },
          complete: function(){
            ModalLoader.end();
          }
        });
      }
    })
});


});
