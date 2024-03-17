"use strict"
$(function(){

  const trComponent = (d,i) => {
    return `<tr data-id="${d.id}">
              <td>${i+1}</td>
              <td>${d.image ? `<img src="${d.image}" data-type="image" alt="${d.title}">` : ""}</td>
              <td data-type="title">${d.title ?? ""}</td>
              <td data-type="description">${d.description ?? ""}</td>
              <td data-type="start_date">${d.start_date ?? ""}</td>
              <td data-type="end_date">${d.end_date ?? ""}</td>
              <td data-type="url">${d.url ?? ""}</td>
              <td>
                <div class="d-flex justify-content-end">
                  <button data-toggle="tooltip" data-placement="top" title="${lang("Edit")}" data-bs-toggle="modal" data-bs-target="#bannerEdit" type="button" data-role="edit" type="button" class="btn btn-primary ms-2">
                    <i class="fas fa-pen"></i>
                  </button>
                  <button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete" type="button" class="btn btn-danger ms-2">
                    <i class="fas fa-trash"></i>
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
      url: `/banners/live`,
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

  let add_modal = "#bannerAdd",
      edit_modal = "#bannerEdit";

  $(document).on("click",`[data-role="add-banner"]`,function(e){
    let data = {
      title: $(`${add_modal} [name="title"]`).val(),
      description: $(`${add_modal} [name="description"]`).val(),
      start_date: $(`${add_modal} [name="start_date"]`).val(),
      end_date: $(`${add_modal} [name="end_date"]`).val(),
      url: $(`${add_modal} [name="url"]`).val(),
      image: $(`${add_modal} [name="image"]`).attr("data-value"),
    };
    // console.log(data);

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.post({
      url: `/banners/add-action`,
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
            $(`${add_modal} input`).val("");
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
      title: parent.find(`[data-type="title"]`).text(),
      description: parent.find(`[data-type="description"]`).text(),
      start_date: parent.find(`[data-type="start_date"]`).text(),
      end_date: parent.find(`[data-type="end_date"]`).text(),
      url: parent.find(`[data-type="url"]`).text(),
    }
    // console.log(data);

    $(`${edit_modal} [name="id"]`).val(data.id);
    $(`${edit_modal} [name="title"]`).val(data.title);
    $(`${edit_modal} [name="description"]`).val(data.description);
    $(`${edit_modal} [name="start_date"]`).val(data.start_date);
    $(`${edit_modal} [name="end_date"]`).val(data.end_date);
    $(`${edit_modal} [name="url"]`).val(data.url);

  });

  $(document).on("click",`[data-role="save"]`,function(e){
    let data = {
      id: $(`${edit_modal} [name="id"]`).val(),
      title: $(`${edit_modal} [name="title"]`).val(),
      description: $(`${edit_modal} [name="description"]`).val(),
      start_date: $(`${edit_modal} [name="start_date"]`).val(),
      end_date: $(`${edit_modal} [name="end_date"]`).val(),
      url: $(`${edit_modal} [name="url"]`).val(),
      image: $(`${edit_modal} [name="image"]`).attr("data-value"),
    };

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.ajax({
      url: `/banners/edit-action`,
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

  $(document).on("click",`[data-role="delete"]`,function(){
    let id = $(this).parents("tr").data("id");

    Swal.fire({
        title: lang("Are u sure to delete this banner"),
        icon: "question",
        showDenyButton: true,
        denyButtonText: lang("Cancel"),
        confirmButtonText: lang("Confirm"),
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEscapeKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          ModalLoader.start(lang("Loading"));
          $.ajax({
            url: `/banners/${id}/delete`,
            headers,
            type: "DELETE",
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
                  $(`[data-id="${id}"]tr`).remove();
                }
              });
            },
            error: function(d){
              console.error(d);
            },
            complete: function(){
              ModalLoader.end();
            }
          });
        }
      });
  });
});
