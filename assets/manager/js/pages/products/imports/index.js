"use-strict";
$(function(){

  const clearTable = ({end_date, start_date, brand, keyword}) => {
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

    getContent({end_date, start_date, brand, keyword});
  };

  const initializeHeaders = () => {
    start_date = $(`[data-role="select-start-date"]`).val().trim();
    end_date = $(`[data-role="select-end-date"]`).val().trim();
    brand = $(`[data-role="select-brands"]`).val().trim();
    keyword = $(`[data-role="search-keyword"]`).val().trim();
  };

  const trComponent = (d,i) => {
    return `<tr data-id="${d.id}">
        <td>${i}</td>
        <td>${d.description ?? ""}</td>
        <td>${d.brand_name ?? ""}</td>
        <td>${d.result_count ?? ""}</td>
        <td>${d.brand_price_rate ?? ""}</td>
        <td>${d.creator_name ?? ""}</td>
        <td>${d.operation_date ?? ""}</td>
        <td>
          ${d.deleted_at ? "" :
              `<button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete" type="button" class="btn btn-danger ms-2">
                  <i class="fas fa-trash"></i>
              </button>`}
        </td>
      </tr>`;
  };

  const getContent = (urlParams) => {

    $(`[data-role="content-result-count"]`).html("0");

    let {end_date, start_date, brand, keyword} = urlParams;

    filter_url([
      {start_date: (start_date || "")},
      {end_date: (end_date || "")},
      {brand: (brand || "")},
      {keyword: (keyword || "")},
    ]);

    if (!(start_date && end_date)) {
      Swal.fire("", lang("minimum_date_should_selected_parameter"), "warning");
      return;
    }

    if(keyword){
      $(`[data-role="search-keyword"]`).val(keyword);
    }

    $(`[data-role="table-loader"]`).removeClass("d-none");
    $(`.table-responsive`).addClass("load");

    let html = "",
    count = 0;

    ModalLoader.start(lang("Loading"));
    $.get({
      url: `/products/imports/list-live`,
      headers,
      data: {end_date, start_date, brand_id: brand, keyword},
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
        $(`[data-role="table-loader"]`).addClass("d-none");
        $(`.table-responsive`).removeClass("load");
        ModalLoader.end();
        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find("button").on("blur", function() {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    });
  }

  let start_date = getUrlParameter("start_date") || $(`[data-role="select-start-date"]`).val(),
    end_date = getUrlParameter("end_date") || $(`[data-role="select-end-date"]`).val(),
    brand = getUrlParameter("brand") || $(`[data-role="select-brands"]`).val(),
    keyword = getUrlParameter("keyword") || $(`[data-role="search-keyword"]`).val();

  getContent({end_date, start_date, brand, keyword});

  $(`[data-role="search-keyword"], [data-role="select-start-date"], [data-role="select-end-date"]`).on("keypress", function(e){
    if (e.which === 13) {
      initializeHeaders();
        getContent({end_date, start_date, brand, keyword});
      }
  });

  $(document).on("click", `[data-role="search-filter"]`, function() {
    initializeHeaders();
    getContent({end_date, start_date, brand, keyword});
  });

  $(document).on("click", `[data-role="clear-filter"]`, function() {
    clearTable({end_date, start_date, brand, keyword});
  });

  $(document).on("change", `[data-role="product-image"]`, function(){
    let label = $(`[data-role="excel-label"]`);
    let file = "";
    file = $(this)[0].files[0].name;
    label.text(file);
  });

  $(`[data-role="save-add-modal"]`).on("click", function() {
    let parent = $(`[data-role="add-excel-modal-form"]`);

    let isValid = {
      description: false,
      excel_file: false,
      brand_id: false,
      currency_id: false,
    };

    let data = {
      description: parent.find(`[name="description"]`).val(),
      excel_file: parent.find(`[name="excel_file"]`).val(),
      brand_id: parent.find(`[name="brand_id"]`).val(),
      brand_price_rate: parent.find(`[name="brand_price_rate"]`).val(),
      currency_id: parent.find(`[name="currency_id"]`).val(),
      // b2b_active: parent.find(`[name="b2b_active"]`).is(":checked") ? "1" : "0",
    };
    console.log(data);
    console.log(parent);
    console.log(parent.find(`[name="brand_id"]`).val());
    Object.keys(isValid).map((v) => {
      let alert_message = parent.find(`[data-name="${v}"]`);
       alert_message.addClass("d-none");

      if(!data[v].trim()) {
        isValid[v] = false;
        parent.find(`[name="${v}"]`).removeClass("is-valid");
        parent.find(`[name="${v}"]`).addClass("is-invalid");

        $(`[name="${v}"]`).addClass("is-invalid");
        alert_message.html(lang(v) + " " + lang("cant_be_empty"));
        alert_message.removeClass("d-none");
        isValid[v] = false;

      } else {
        isValid[v] = true;
        parent.find(`[name="${v}"]`).removeClass("is-invalid");
        parent.find(`[name="${v}"]`).addClass("is-valid");
      }

    });

    if(!isValid.description || !isValid.excel_file ||  !isValid.brand_id) {
      return;
    }

    data.excel_file = parent.find(`[name="excel_file"]`)[0].files[0].name;

    const addExcelFile = (data) => {

      let btn_id = $("#btn_loader_add_id").attr("id");

      Loader.btn({
        element_id: btn_id,
        action: "start"
      });

      ModalLoader.start(lang("Loading"));
      $.post({
        url: `/products/imports/add`,
        data,
        headers,
        success: function(d){
          if(d.code === 201) {
            getContent({end_date, start_date, brand, keyword});
            Loader.btn({
              element_id: btn_id,
              action: "success"
            });
            Swal.fire("", d.message, "success");
            $("#add-modal").modal("hide");
            $(`[name="description"],[name="excel_file"]`).val("");
            $(`[data-role="excel-label"]`).html("");

            Object.keys(isValid).map((v) => {
              parent.find(`[name="${v}"]`).removeClass("is-valid");
            });
          } else {
            Loader.btn({
              element_id: btn_id,
              action: "warning"
            });
            Swal.fire("", d.message, "warning");
          }
        },
        error: function(d) {
          Loader.btn({
            element_id: btn_id,
            action: "error"
          });
          console.log(d);
        },
        complete: function(d) {
          ModalLoader.end();
          Loader.btn({
            element_id: btn_id,
            action: "end"
          });
        }
      });
    }

    getBase64(parent.find(`[name="excel_file"]`)[0].files[0]).then((result) => {
      data.excel_file_64 = result;
      addExcelFile(data);
    },function() {}).catch(e => {
      console.log(e);
    });



  });

  const getBrands = () => {

    $(`[data-role="select-brands"]`).parents(".form-group").addClass("loader");
    let html = "";
    $.get({
        url: `/products/properties/brands`,
        headers,
        cache: true,
        success: function (d) {
          if (d.code === 200) {
            let content_data = d.data.list ?? [];
            if(content_data.length){
              setStorage("bpm_product_brands", JSON.stringify(content_data), 60 * 60 * 12);
            }
            html = content_data.map((v, i) => `<option value="${v.id}" ${brand === v.id ? " selected" : ""}>${(v.name ? v.name : lang("without_brand"))}</option>` ).join("");
            $(`[data-role="select-brands"]`).attr("disabled",false);
          } else if (d.code === 204) {
            html = "";
          }
          else {
            html = "";
            Swal.fire("", d.message, "warning");
          }
          $(`[data-role="select-brands"]`).html(`<option value="">${lang("All brands")}</option>`+html);
        },
        error: function (d) {
          console.error(d);
        },
        complete: function () {
          $(`[data-role="select-brands"]`).parents(".form-group").removeClass("loader");
        },
      });
    };
    getBrands();

    const getCurrencies = () => {
      if (getStorage("bpm_currencies_list")) {
        let content_data = JSON.parse(getStorage("bpm_currencies_list"));
        let html = content_data.map((v, i) => `<option value="${v.id}">${(v.main_name ?? "")}</option>` ).join("");
        $(`[data-role="select-currencies"]`).attr("disabled",false);
        $(`[data-role="select-currencies"]`).html(html);
        return;
      }
      $(`[data-role="select-currencies"]`).parents(".form-group").addClass("loader");
      let html = "";
      $.get({
          url: `/currencies/list`,
          headers,
          cache: true,
          success: function (d) {
            if (d.code === 200) {
              let content_data = d.data ?? [];
              if(content_data.length){
                setStorage("bpm_currencies_list", JSON.stringify(content_data), 60 * 60 * 12);
              }
              html = content_data.map((v, i) => `<option value="${v.id}">${(v.main_name ?? "")}</option>` ).join("");
              $(`[data-role="select-currencies"]`).attr("disabled",false);
            } else if (d.code === 204) {
              html = "";
            }
            else {
              html = "";
              Swal.fire("", d.message, "warning");
            }
            $(`[data-role="select-currencies"]`).html(html);
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            $(`[data-role="select-currencies"]`).parents(".form-group").removeClass("loader");
          },
        });
      };
      getCurrencies();


    $(document).on("click", `[data-role="delete"]`, function(e){
      let id = $(this).parents("tr").data("id");

      e.preventDefault();

      Swal.fire({
        title: lang("Are you sure to delete this import?"),
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
            url: `/products/imports/${id}/delete`,
            type: 'delete',
            headers,
            data: JSON.stringify({id}),
            success: function(d){
              if(d.code === 200) {
                notify("success", d.message);
                getContent({end_date, start_date, brand, keyword});
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
