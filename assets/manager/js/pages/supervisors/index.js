"use strict"
$(function(){

  const trComponent = (d,i) => {
    return `<tr data-id="${d.id}">
              <td>${i+1}</td>
              <td>${d.image ? `<img src="${d.image}" data-type="image" alt="${d.name}">` : ""}</td>
              <td data-type="name">${d.name ?? ""}</td>
              <td data-type="surname">${d.surname ?? ""}</td>
              <td class="d-none" data-type="ava_name">${d.ava_name ?? ""}</td>
              <td data-type="phone">${d.phone ?? ""}</td>
              <td data-type="email">${d.email ?? ""}</td>
              <td data-type="whatsapp">${d.whatsapp ?? ""}</td>
              <td>
                <div class="d-flex justify-content-end">
                  <button data-toggle="tooltip" data-placement="top" title="${lang("Edit")}" data-bs-toggle="modal" data-bs-target="#supervisorEdit" type="button" data-role="edit" type="button" class="btn btn-primary ms-2">
                    <i class="fas fa-pen"></i>
                  </button>
                </div>
              </td>
            </tr>` ;
  }

  let interval = null;

  const getContent = (data) => {
    let start_time = new Date().getTime();
    interval = setInterval(function(){$(`[data-role="content-result-time"]`).html(`${(Math.round((new Date().getTime() - start_time)/100)/10).toFixed(3)}`);},100);

    customLoader();
    $.get({
      url: `/supervisors/live`,
      headers,
      success: function(d){
        // console.log(d);
        let h = "";
        if (d.code === 200) {
          d.data.map((v,i) => {
            h += trComponent(v,i);
          })
        }else{
          h = warningComponent(d.message)
        }
        $(`[data-role="content-result-count"]`).html(d.data.length);
        $(`[data-role="table-list"]`).html(h);
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        ModalLoader.end();
        clearInterval(interval);
        customLoader(true);
      }
    })
  }
  getContent();

  let add_modal = "#supervisorAdd",
      edit_modal = "#supervisorEdit";

  $(document).on("click",`[data-role="add-supervisor"]`,function(e){
    let data = {
      name: $(`${add_modal} [name="name"]`).val(),
      surname: $(`${add_modal} [name="surname"]`).val(),
      phone: $(`${add_modal} [name="phone"]`).val(),
      email: $(`${add_modal} [name="email"]`).val(),
      whatsapp: $(`${add_modal} [name="whatsapp"]`).val(),
      ava_name: $(`${add_modal} [name="ava_name"]`).val(),
      image: $(`${add_modal} [name="image"]`).attr("data-value"),
    };
    // console.log(data);

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.post({
      url: `/supervisors/add-action`,
      data,
      headers,
      success: function(d){
        // console.log(d);
        Swal.fire({
          title: d.message,
          icon: d.code === 201 ? "success" : "warning",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEscapeKey: false,
        }).then((result) => {
          if (result.isConfirmed && d.code === 201){
            $(add_modal).modal("hide");
            getContent();
          }
        });
        Loader.btn({
          element: e.target,
          action: d.code === 201 ? "success" : "warning"
        });
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

  let modals = [add_modal,edit_modal];
  modals.map(v => {
    $(document).on("change",`${v} [name="image"]`,function(e){
      let th = $(this);
      let input = e.target;
      th.parents(".form-group").addClass("loader");
      let val = getBase64(input.files[0]).then(function(res){
        $(`${v} [name="image"]`).attr("data-value",res);
      }).finally(function(){
        th.parents(".form-group").removeClass("loader");
      });
    });
  });

  $(document).on("click",`[data-role="edit"]`,function(){
    $(`${edit_modal} input`).val("");
    let parent = $(this).parents("tr");
    let data = {
      id: parent.data("id"),
      name: parent.find(`[data-type="name"]`).text(),
      surname: parent.find(`[data-type="surname"]`).text(),
      ava_name: parent.find(`[data-type="ava_name"]`).text(),
      phone: parent.find(`[data-type="phone"]`).text(),
      email: parent.find(`[data-type="email"]`).text(),
      whatsapp: parent.find(`[data-type="whatsapp"]`).text(),
    }
    // console.log(data);

    $(`${edit_modal} [name="id"]`).val(data.id);
    $(`${edit_modal} [name="name"]`).val(data.name);
    $(`${edit_modal} [name="surname"]`).val(data.surname);
    $(`${edit_modal} [name="ava_name"]`).val(data.ava_name);
    $(`${edit_modal} [name="phone"]`).val(data.phone);
    $(`${edit_modal} [name="email"]`).val(data.email);
    $(`${edit_modal} [name="whatsapp"]`).val(data.whatsapp);

  });

  $(document).on("click",`[data-role="save"]`,function(e){
    let data = {
      id: $(`${edit_modal} [name="id"]`).val(),
      name: $(`${edit_modal} [name="name"]`).val(),
      surname: $(`${edit_modal} [name="surname"]`).val(),
      phone: $(`${edit_modal} [name="phone"]`).val(),
      email: $(`${edit_modal} [name="email"]`).val(),
      whatsapp: $(`${edit_modal} [name="whatsapp"]`).val(),
      ava_name: $(`${edit_modal} [name="ava_name"]`).val(),
      image: $(`${edit_modal} [name="image"]`).attr("data-value"),
    };

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.ajax({
      url: `/supervisors/edit-action`,
      data: JSON.stringify(data),
      headers,
      type: "PUT",
      success: function(d){
        // console.log(d);
        Swal.fire({
            title: d.message,
            icon: d.code === 202 ? "success" : "warning",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEscapeKey: false,
          }).then((result) => {
            if (result.isConfirmed && d.code === 202){
              $(edit_modal).modal("hide");
              getContent();
            }
          });
        Loader.btn({
          element: e.target,
          action: d.code === 202 ? "success" : "warning"
        });
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

});
