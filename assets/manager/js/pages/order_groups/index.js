$(function(){
  let keyword = getUrlParameter("keyword") || "";

  let warehouse_list = {};
  const groupsComponent = (v, i) => {
    warehouse_list[v.id] = v.warehouse_id;
    return `<tr data-id="${v.id}">
                <td >${++i}</td>
                <td><p data-role="warehouse-p">${v.warehouse || ""}<p><br>
                <span style="cursor: pointer" data-role="edit-warehouse" data-bs-toggle="modal" data-bs-target="#editWarehouse"><i class="fas fa-edit"></i></span>
                </td>
                <td><input type="text" class="form-control" data-text="name" value="${v.name || ""}"></td>
                <td><input type="text" class="form-control" data-text="description" value="${v.description || ""}"></td>
                <td><textarea type="text" class="form-control" data-text="details" placeholder="${lang("Details")}">${v.details || ""}</textarea></td>
                <td><input type="date" class="form-control" data-text="default_start_date"${v.default_start_date ? ` value="${v.default_start_date}"` : ""}></td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" data-text="is_remote" id="${v.id}" ${v.is_remote ? "checked" : ""}>
                    <label for="${v.id}"></label>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" data-text="is_active" id="${v.id}i" ${v.is_active ? "checked" : ""}>
                    <label for="${v.id}i"></label>
                </td>
                <td>
                  <div class="d-flex justify-content-center">
                    <button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete-group" type="button" class="btn btn-danger ms-2">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
            </tr>`;
  }

  const warningComponent = (message = null) => {
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

  const getAll = (data) => {
    let counter = null;
    let start_time_interval = new Date().getTime();
      counter = setInterval(() => {
        $(`[data-role="content-result-time"]`).html((new Date().getTime() - start_time_interval) / 1000)
      }, 100);
    customLoader();
    $.get({
      url: `/order-groups/list`,
      data,
      headers,
      success: function(d){
        let h = "";
        if (d.code === 200) {
          $(`[data-role="content-result-count"]`).html(d.data.length)
          d.data.map((v,i) => h += groupsComponent(v,i));
        }else{
          $(`[data-role="content-result-count"]`).html("0")
          h = warningComponent(d.message);
        }
        $(`[data-role="table-list"]`).html(h);
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        customLoader(true);
        clearInterval(counter)
      }
    })
  }

  getAll();

  const warehousesComponent = (v) => {
    return `<option value="${v.id}">${v.name}</option>`;
  }

  let warehouses_loaded = false;
  $(document).on("click",`[data-bs-target="#addGroup"]`,function(){
    if (warehouses_loaded) return;
    $.get({
      url: `/order-groups/warehouses`,
      headers,
      success: function(d){
        let h = "";
        if (d.code === 200) {
          warehouses_loaded = true;
          $(`#addGroup`).find(`[name="warehouse"]`).prop("disabled",false)
          $(`#editWarehouse`).find(`[name="warehouse"]`).prop("disabled",false)
          d.data.map(v => {
            h += warehousesComponent(v);
          })
        }
        $(`#addGroup`).find(`[name="warehouse"]`).html(`<option value="">${lang("Select warehouse")}</option>`+h)
        $(`#editWarehouse`).find(`[name="warehouse"]`).html(h)
      },
      error: function(e){
        console.error(e)
      }
    });
  });

  $(document).on("click",`[data-bs-target="#editWarehouse"]`,function(){
    let id = $(this).parents("tr").data("id");
    if (warehouses_loaded) {
      $(`#editWarehouse`).find(`[name="warehouse"]`).val(warehouse_list[id]).change()
    }
      $(`#editWarehouse [data-role="edit-warehouse-button"]`).data("id",id);
    if (!warehouses_loaded) {
      $.get({
        url: `/order-groups/warehouses`,
        headers,
        success: function(d){
          let h = "";
          if (d.code === 200) {
            warehouses_loaded = true;
            $(`#addGroup`).find(`[name="warehouse"]`).prop("disabled",false)
            $(`#editWarehouse`).find(`[name="warehouse"]`).prop("disabled",false)
            d.data.map(v => {
              h += warehousesComponent(v);
            })
          }
          $(`#addGroup`).find(`[name="warehouse"]`).html(h)
          $(`#editWarehouse`).find(`[name="warehouse"]`).html(h).val(warehouse_list[id]).change()

        },
        error: function(e){
          console.error(e)
        }
      });
    }

  })



    $(document).on("click",`[data-role="edit-warehouse-button"]`,function(){
      let parent = $(`#editWarehouse`),
          warehouse = parent.find(`[name="warehouse"]`).val(),
          warehouse_name= parent.find(`[name="warehouse"] option:selected`).text(),
          id = $(this).data("id"),
          data = {
            key: `warehouse_id`,
            value: warehouse
          };
        customLoader();
        $.ajax({
          type: `put`,
          url: `/order-groups/${id}/edit-detail`,
          data: JSON.stringify(data),
          headers,
          success: function(d){
            if (d.code === 202) {
              parent.modal("hide");
              $(`tr[data-id="${id}"] [data-role="warehouse-p"]`).html(warehouse_name);
            }else{
              Swal.fire("",d.message,"warning")
            }

          },
          error: function(e){
            console.error(e);
          },
          complete: function(){
            customLoader(true)
          }
      });
    })

  $(document).on("click", `[data-role="delete-group"]`, function () {
    let id = $(this).parents("tr").data("id");
    Swal.fire({
      title: lang("Are u sure to delete this order group") + "?",
      showCancelButton: true,
      confirmButtonText: lang("Delete"),
      cancelButtonText: lang("Cancel"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        customLoader();
        $.ajax({
          url: `/order-groups/${id}/delete`,
          type: "DELETE",
          headers,
          data: JSON.stringify({ id }),
          success: function (d) {
            if (d.code === 202) {
              $(`tr[data-id=${id}]`).remove();
            }
            Swal.fire("", d.message, d.code === 202 ? "success" : "warning");
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            customLoader(true)
          }
        })
      }
    })

  });

  $(document).on("click",`[data-role="add-group-button"]`,function(){
    let parent = $(`#addGroup`);

    let data = {
      name: parent.find(`[name="name"]`).val(),
      description: parent.find(`[name="description"]`).val(),
      details: parent.find(`[name="details"]`).val(),
      warehouse: parent.find(`[name="warehouse"]`).val(),
      default_start_date: parent.find(`[name="default_start_date"]`).val(),
      is_active: parent.find(`[name="is_active"]`).prop("checked") ? "1" : "0",
      is_remote: parent.find(`[name="is_remote"]`).prop("checked") ? "1" : "0",
    },
    isInvalid = {
      name: true,
    }
    Object.keys(isInvalid).map(v => {
      if (!data[v].trim()) {
        parent.find(`[name=${v}]`).addClass("is-invalid");
        isInvalid[v] = true
      }else{
        isInvalid[v] = false;
        parent.find(`[name=${v}]`).removeClass("is-invalid");
      }
    })

    if (isInvalid.name) {
      return;
    }

    customLoader();

    $.post({
      url: `/order-groups/add`,
      data,
      headers,
      success: function(d){
        if (d.code === 201) {
          parent.modal("hide");
          parent.find(`[name="name"],[name="description"]`).val("")
          parent.find(`[name="group"]`).val("").change();
          getAll({keyword});
        }
        Swal.fire("",d.message,d.code === 201 ? "success" : "warning")
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        customLoader(true);
      }
    })

  })

  const updateDetail = (th) => {
    id = th.parents("tr").data("id");
    let data = {
      key: th.data("text"),
    }

    if (["is_remote","is_active"].includes(data.key)) {
      data.value = th.prop("checked") ? "1" : "0";
    }else{
      data.value =  th.val();
    }

    if (["is_remote","is_active"].includes(data.key)) {
      Swal.fire({
        title: data.key === "is_remote" ? lang("Are you sure that you want to change remote status") + "?" :  lang("Are you sure that you want to change status") + "?",
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: lang("Yes"),
        cancelButtonText: lang("No"),
        reverseButtons: true
      }).then(swal => {
        if (swal.isConfirmed) {
          customLoader();
          $.ajax({
            type: "PUT",
            url: `/order-groups/${id}/edit-detail`,
            data: JSON.stringify(data),
            headers,
            success: function (d) {
              Swal.fire("", d.message, d.code === 202 ? "success" : "warning")
            },
            error: function (e) {
              console.error(e)
            },
            complete: function () {
              customLoader(true)
            }
          })
        } else {
          th.prop("checked",!th.is(":checked"));
        }
      })
    }else{
      customLoader();
      $.ajax({
        type: "PUT",
        url: `/order-groups/${id}/edit-detail`,
        data: JSON.stringify(data),
        headers,
        success: function (d) {
          Swal.fire("", d.message, d.code === 202 ? "success" : "warning")
        },
        error: function (e) {
          console.error(e)
        },
        complete: function () {
          customLoader(true)
        }
      });
    }
  }
  $(document).on("keypress", `[data-text="name"],[data-text="default_start_date"],[data-text="details"]`, function (e) {
    if (e.which === 13) {
      updateDetail($(this));
    }
  });

  let prev_val = "";
  $(document).on("focusin",`[data-text="name"],[data-text="default_start_date"],[data-text="description"],[data-text="details"]`,function(){
    prev_val = $(this).val();
  });

  $(document).on("focusout",`[data-text="name"],[data-text="default_start_date"],[data-text="description"],[data-text="details"]`,function(){
    if (prev_val.trim() !== $(this).val().trim()) {
      updateDetail($(this));
    }
  });

  $(document).on("change", `[data-text="is_active"],[data-text="is_remote"]`, function (e) {
    if (e.which === 13) {
      updateDetail($(this));
    }
  });


  $(document).on("keyup", `[data-role="search-filter"]`, function (e) {
    keyword = $(this).val();
    if (e.keyCode === 13) {
      filter_url([{ keyword }]);
      getAll({ keyword });
    }
  });

});
