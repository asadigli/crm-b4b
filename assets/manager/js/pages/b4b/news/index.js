"use strict"
$(function(){
  const trComponent = (d,i) => {
    return `<tr data-id="${d.id}" data-pop-up-type="${d.type}">
              <td>${i+1}</td>
              <td data-type="images">${d.images ?
                                      `<img
                                        src="${window.location.origin + "/" + d.images[0]}"
                                        data-type="image"
                                        style="max-width:100px;height: auto;"
                                        alt="${d.title}">` : ""}
              </td>
              <td data-type="title">${d.title ?? ""}</td>
              <td data-type="body">${d.body ?? ""}</td>
              <td data-type="start_date">${d.start_date ?? ""}</td>
              <td data-type="end_date">${d.end_date ?? ""}</td>
              <td>
              <span class="order-badge badge badge-${d.is_active ? "success" : "danger"}">${d.is_active ? words["Active"] : words["Deactive"]}</span>
              <input type="hidden" name="is_active" value="${d.is_active}">
              </td>
              <td>
              <span class="order-badge badge badge-${d.is_popup ? "success" : "danger"}">${d.is_popup ? words["Active"] : words["Deactive"]}</span>
              <input type="hidden" name="is_popup" value="${d.is_popup}">
              </td>
              <td>
                <div class="d-flex justify-content-end">
                  <button data-toggle="tooltip" data-placement="top" title="${lang("Edit")}" data-bs-toggle="modal" data-bs-target="#newsEdit" type="button" data-role="edit" type="button" class="btn btn-primary ms-2">
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
      url: `/news/list-live`,
      headers,
      success: function(d){
        // console.log(d);
        let h = "";
        let count = 0;
        if (d.code === 200) {
          d.data.map((v,i) => {
            h += trComponent(v,i);
          })
        }else{
          h = warningComponent(d.message)
        }
        count = d.data && d.data.length ? d.data.length : 0;
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

  const getTypes = () => {
    $.get({
      url: `/news/types`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose pop-up type")}</option>`;

          d.data.map(v => {
            h += `<option value="${v}">${lang(v)}</option>`;
          });
          $(`[name="type"]`).html(h);
        }
      },
      error: function(d){
        console.error(d);
      },
    });
  }
  getTypes();

  let add_modal = "#newsAdd",
      edit_modal = "#newsEdit";

  $(document).on("click", `[data-role="open-add-news"]`, function() {
    let add_flup = new Flup ({
      selector: "#news_add_image",
      limit: 1
    });
  });

  $(document).on("click",`[data-role="add-news"]`,function(e){
    let data = {
      title: $(`${add_modal} [name="title"]`).val(),
      body: $(`${add_modal} [name="body"]`).val(),
      start_date: $(`${add_modal} [name="start_date"]`).val(),
      end_date: $(`${add_modal} [name="end_date"]`).val(),
      image: $(`${add_modal} [name="news_add_image"]`).val(),
      type: $(`${add_modal} [name="type"]`).val(),
      is_active: $(`${add_modal} [name="is_active"]`).is(":checked") ? 1 : 0,
      is_popup: $(`${add_modal} [name="is_popup"]`).is(":checked") ? 1 : 0,
    };
    // console.log(data);

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.post({
      url: `/news/add`,
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
            let default_start_date = $(`${add_modal} [name="start_date"]`).data("start-date");
            let default_end_date = $(`${add_modal} [name="end_date"]`).data("end-date");
            $(`${add_modal} [name="start_date"]`).val(default_start_date);
            $(`${add_modal} [name="end_date"]`).val(default_end_date);

            $(`${add_modal} [name="title"], [name="body"], [name="url"], [name="image"]`).val("");

            $(`${add_modal} textarea`).val("");
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
    let edit_flup = new Flup ({
      selector: "#news_edit_image",
      limit: 1
    });

    $(`${edit_modal} input`).val("");
    let parent = $(this).parents("tr");

    let data = {
      id: parent.data("id"),
      type: parent.data("pop-up-type"),
      title: parent.find(`[data-type="title"]`).text(),
      body: parent.find(`[data-type="body"]`).text(),
      start_date: parent.find(`[data-type="start_date"]`).text(),
      end_date: parent.find(`[data-type="end_date"]`).text(),
      image: parent.find('img').attr('src'),
      is_active: parent.find(`[name="is_active"]`).val(),
      is_popup: parent.find(`[name="is_popup"]`).val(),
    }

    // console.log(data);

    $(`${edit_modal} [name="id"]`).val(data.id);
    $(`${edit_modal} [name="title"]`).val(data.title);
    $(`${edit_modal} [name="body"]`).val(data.body);
    $(`${edit_modal} [name="type"]`).val(data.type).change();
    $(`${edit_modal} [name="start_date"]`).val(data.start_date);
    $(`${edit_modal} [name="end_date"]`).val(data.end_date);
    if(data.is_active === "false"){
      $(`${edit_modal} [name="is_active"]`).prop("checked", false).change();
    }else{
      $(`${edit_modal} [name="is_active"]`).prop("checked", true).change();
    }
    if(data.is_popup === "false"){
      $(`${edit_modal} [name="is_popup"]`).prop("checked", false).change();
    }else{
      $(`${edit_modal} [name="is_popup"]`).prop("checked", true).change();
    }

    if(data.image) {
      let img_component = `
        <div data-role="img-parent" style="cursor:pointer;" class="uploaded-img-box mr-2" >
          <input type="hidden" name="news_edit_image" val="${data.image}" />
          <img src="${data.image}" />
          <div class="overlay-delete-btn">
            <i data-role="delete-image-modal" class="fi fi-rr-cross-circle"></i>
            <a href="${data.image}" data-fancybox="gallery"><i class="fi fi-rr-eye"></i></a>
          </div>
        </div>
      `;
      $(`[data-role="edit-modal-content"]`).find(`[data-role="image-load-data"]`).html(img_component);
      // console.log(img_component);
    }

  });

  $(document).on("click",`[data-role="save"]`,function(e){
    let id = $(`${edit_modal} [name="id"]`).val();
    let data = {
      title: $(`${edit_modal} [name="title"]`).val(),
      body: $(`${edit_modal} [name="body"]`).val(),
      start_date: $(`${edit_modal} [name="start_date"]`).val(),
      end_date: $(`${edit_modal} [name="end_date"]`).val(),
      type: $(`${edit_modal} [name="type"]`).val(),
      image: $(`${edit_modal} [name="news_edit_image"]`).val() ? $(`${edit_modal} [name="news_edit_image"]`).val() : $(`[data-role="edit-modal-content"]`).find('img').attr('src'),
      is_active: $(`${edit_modal} [name="is_active"]`).prop("checked") ? "1" : "0",
      is_popup: $(`${edit_modal} [name="is_popup"]`).prop("checked") ? "1" : "0"
    };
    console.log(data.image.startsWith(window.location.origin));

    data.deleted_image = data.image.startsWith(window.location.origin)

    Loader.btn({
      element: e.target,
      action: "start"
    });
    $.ajax({
      url: `/news/${id}/edit`,
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
        title: lang("Are u sure to delete this news"),
        icon: "question",
        showDenyButton: true,
        denyButtonText: lang("Cancel"),
        confirmButtonText: lang("Confirm"),
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEscapeKey: false,
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          ModalLoader.start(lang("Loading"));
          $.ajax({
            url: `/news/${id}/delete`,
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
