$(function(){
  $(`[data-bs-toggle="tooltip"]`).tooltip();

  let keyword = getUrlParameter("keyword") || ""
      group = getUrlParameter("group") || ""
      content_list = {};

  const configComponent = (v,i,types,resources,groups) => {
    return `<tr data-id="${v.id}">
              <td>${++i}</td>
              <td>
              <select class="form-control" data-text="resource">
                ${Object.keys(resources).map(f => {
                  return `<option value="${f}" ${v.resource === f ? "selected" : ""}>${lang(f)}</option>`
                }).join("")}
              </select>
              </td>
              <td>
              <select class="form-control" data-text="type">
                ${Object.keys(types).map(f => {
                  return `<option value="${f}" ${v.type === f? "selected" : ""}>${lang(f)}</option>`
                }).join("")}
              </select>
              </td>
              <td>
              <select class="form-control" data-text="group">
                ${Object.keys(groups).map(f => {
                  return `<option value="${f}" ${v.group === f? "selected" : ""}>${lang(f)}</option>`
                }).join("")}
              </select>
              </td>
              <td><input type="text" class="form-control" data-text="key" value="${v.key || ""}"></td>
              <td><input type="text" class="form-control" data-text="value" value="${v.value || ""}"></td>
              <td>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" data-text="is_active" data-role="is-active" id="${v.id}" ${v.is_active ? "checked" : ""}>
                  <label for="${v.id}"></label>
              </td>
              <td>
              <button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete-config" type="button" class="btn btn-danger ms-2">
                <i class="fas fa-trash"></i>
              </button>
              </td>
            </tr>`
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



  $(document).on("change",`[data-text="resource"],[data-text="group"],[data-text="type"],[data-text="key"],[data-text="value"]`,function(){
    let elem = $(this),
        id = elem.parents("tr").data("id");
    let data = {
      key: $(this).data("text"),
      value: $(this).val()
    }
    let isValid = {
      key: false,
      value: false
    }

    Object.keys(isValid).map(v => {
      if (!data[v]) {
        isValid[v] = false
      }else{
        isValid[v] = true
      }
    })

    if(!isValid.key || !isValid.value) return;
    if (data.key === "type") {
      if (data.value !== "integer" && typeof elem.parents(`tr[data-id="${id}"]`).find(`[data-text="value"]`).val() !== "number") {
        Swal.fire("",lang("Value must be numeric"),"warning");
        elem.val(content_list[id]["type"]);
        return;
      }else if (data.value === "string" && (!["number","string"].includes(typeof elem.parents(`tr[data-id="${id}"]`).find(`[data-text="value"]`).val()) )){
        Swal.fire("",lang("Value must be string"),"warning");
        elem.val(content_list[id]["type"]);
        return;
      }
    }
    customLoader();
    $.ajax({
      type: "put",
      url: `/configs/${id}/edit`,
      data: JSON.stringify(data),
      headers,
      success: function(d){
        if (d.code !== 202) {
          elem.parents(`tr[data-id="${id}"]`).find(`[data-text=${data.key}]`).val(content_list[id][data.key]);
        }
        Swal.fire("",d.message,d.code === 202 ? "success" : "warning")
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        customLoader(true)
      }
    })
  })

  const listAll = (data) => {
    let counter = null;
    let start_time_interval = new Date().getTime();
    counter = setInterval(() => {
      $(`[data-role="content-result-time"]`).html((new Date().getTime() - start_time_interval)/1000)
    },100);
    customLoader()
    $.get({
      url: `/configs/list`,
      data,
      headers,
      success: function(d){
        let h = '';
        if (d.code === 200) {
          d.data.configs.map((v,i) => {
            content_list[v.id] = v;
            h += configComponent(v,i,d.data.types,d.data.resources,d.data.groups);

          }).join(" ");
          $(`[data-role="content-result-count"]`).html(d.data.configs.length);
        }else{
          h = warningComponent(d.message)
          $(`[data-role="content-result-count"]`).html("0");
        }
        $(`[data-role="table-list"]`).html(h);
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        clearInterval(counter);
        customLoader(true);
      }
    })
  }

  listAll({keyword,group});


$(document).on("click",`[data-role="config-save-btn"]`,function(){
  let parent = $(this).parents("#addConfig"),
      data = {
        key: parent.find(`[name="key"]`).val(),
        value: parent.find(`[name="value"]`).val(),
        group: parent.find(`[name="group"]`).val(),
        resource: parent.find(`[name="resource"]`).val(),
        type: parent.find(`[name="type"]`).val(),
        is_active: parent.find(`[name="active"]`).is(":checked") ? "1" : "0",
      },
      isValid = {
        key: false,
        value: false,
        group: false,
        resource: false,
        is_active: false,
        // type: false
      };

      Object.keys(isValid).map(v => {
        if (!data[v].trim()) {
          isValid[v] = false
          $(`[name="${v}"]`).addClass("is-invalid");
        }else{
          isValid[v] = true
          $(`[name="${v}"]`).removeClass("is-invalid");
        }
      })
      
      if (!isValid.key || !isValid.value || !isValid.group || !isValid.resource || !isValid.is_active ) {
        return;
      }

      if ((data.type === "integer" && isNaN(data.value)) || (data.type === "string" && !["string","number"].includes(typeof data.value))) {
            Swal.fire("", lang("Invalid value"), "warning");
            return;
          }

      customLoader();

      $.post({
        url: `/configs/add`,
        data,
        headers,
        success: function(d){
          if (d.code === 201) {
            parent.find(`[name="key"],[name="value"],[name="group"]`).val("");
            parent.find(`[data-role="is-supplier"]`).is(":checked") ? "1" : "0";
            parent.modal("hide");
            listAll()
          }
          Swal.fire("",d.message,d.code === 201 ? "success" : "warning")
        },
        error: function(e){
          console.error(e);
        },
        complete: function(){
          customLoader(true);
        }
      })
})


  $(document).on("keyup",`[data-role="search-filter"]`,function(e){
    keyword = $(this).val();
    offset = 0;
     if (e.keyCode === 13) {
       filter_url([{keyword}]);
       listAll({keyword,offset});
     }
  })

  $(document).on("click",`[data-role="delete-config"]`,function(){
  let id = $(this).parents("tr").data("id");
  Swal.fire({
    title: lang("Are u sure to delete this config") + "?",
    showCancelButton: true,
    confirmButtonText: lang("Delete"),
    cancelButtonText: lang("Cancel"),
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      customLoader();
      $.ajax({
        url: `/configs/${id}/delete`,
        type: "DELETE",
        headers,
        data: JSON.stringify({id}),
        success: function(d){
          if (d.code === 202) {
            listAll({keyword});
          }
          Swal.fire("",d.message,d.code === 202 ? "success" : "warning");
        },
        error: function(d){
          console.error(d);
        },
        complete: function(){
          customLoader(true)
        }
      })
    }
  })

});

  $(document).on("change",`[data-role="is-active"]`,function(){
  let id = $(this).parents("tr").data("id"),
      elem = $(this)
      is_active = $(this).prop("checked") ? "1" : "0";
  Swal.fire({
    title: lang("Are u sure to change status") + "?",
    showCancelButton: true,
    confirmButtonText: lang("Yes"),
    cancelButtonText: lang("No"),
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      customLoader();
      $.ajax({
        url: `/configs/${id}/status`,
        type: "PUT",
        headers,
        data: JSON.stringify({is_active}),
        success: function(d){
          if (d.code === 202) {
            content_list[id]["is_active"] = is_active === "1";
          }
          Swal.fire("",d.message,d.code === 202 ? "success" : "warning");
        },
        error: function(d){
          console.error(d);
        },
        complete: function(){
          customLoader(true)
        }
      })
    }else{
      elem.parents(`tr[data-id="${id}"]`).find(`[data-role="is-active"]`).prop("checked",content_list[id][["is_active"]]);
    }
  })

});




});
