$(function() {

  const trComponent = (d,i) => {
    return `<tr data-id="${d.id ?? ""}" data-type="old" data-has-orders="${+d.orders_count ? 1 : 0}"" >
      <td>${i}</td>
      <td>
        <div class="d-flex align-items-center" >
            <div class="d-flex align-items-center me-2">
              <i class="fa-solid fa-folder ${+d.orders_count ? "text-primary" : ""}"></i>
            </div>
            <div
                class="me-2"
                data-role="name"
                data-name="${d.name ?? ""}"
                data-content="content-edit"
                data-input="contenteditable"
                contenteditable="false"
            >
              ${d.name ?? ""}
            </div>
            ${d.orders_count ? "(" + d.orders_count + ")" : ""}
        </div>
      </td>
      <td>
        <div
            data-role="description"
            data-name="${d.description ?? ""}"
            data-content="content-edit"
            data-input="contenteditable"
            contenteditable="false"
            style="min-height: 15px;"
        >
          ${d.description ?? ""}
        </div>
      </td>
      <td>
        ${+d.orders_count ? `<a
          class="link"
          href="javascript:void(0)"
          data-role="search-folder-orders"
        >${lang("Open folder")}</a>` : ""}
      </td>
      <td>
        <div class="form-check form-switch">
          <input
            class="form-check-input c-pointer"
            type="checkbox"
            role="switch"
            id="flexSwitchCheckChecked${i}"
            data-role="is-active"
            ${d.is_active ? "checked" : ""}
            >
          <div
            data-role="is-active-lang"
            class="badge badge-${d.is_active ? "success" : "danger"}">
            ${d.is_active ? lang("Active") : lang("Deactive")}
            </div>
        </div>
      </td>
      <td>
        <div class="d-flex justify-content-end">
          <button data-toggle="tooltip" data-placement="left" title="${lang("Delete")}" type="button" data-role="delete" type="button" class="btn btn-danger ms-2">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>`;
  };

  const trEmptyComponent = (i) => {
    return `<tr
          data-type="new-data"
          >
      <td>${i}</td>
      <td colspan="10">
        <div class="d-flex align-items-center" >
          <i class="fa-regular fa-folder"></i>
          <input
              data-role="add-new-folder-name"
              data-name="name"
              class="form-control ms-2"
              style="width:100% !important;"
          >
          </input>
        </div>
      </td>
    </tr>`;
  };


  const getFolderList = () => {
    ModalLoader.start(lang("Loading"));

    let html = "",
    count = 0;

    // $(`[data-role="folders-table-list"]`).html(html);
    $.get({
      url: `/orders/folders`,
      headers,
      success: function(d){
        if(d.code === 200){
          let content_data = d.data && d.data.list ? d.data.list : [],
          totals = d.data && d.data.totals ? d.data.totals : [];
          count = d.data && d.data.count ? d.data.count : 0;

          html = content_data.map((v,i) => trComponent(v,++i)).join("");


        } else if(d.code === 204){
          html = warningComponent(d.message);
        }

        $(`[data-role="content-result-count"]`).html(d.data && d.data.count ? d.data.count : 0);
        $(`[data-role="folders-table-list"]`).html(html);
      },
      error: function(d){
        console.log(d);
      },
      complete: function(){
        ModalLoader.end();

        $(`[data-role="add-folder-tr"]`).removeClass("d-none");

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    });
  };

  $(`[data-role="open-folders-list-modal"]`).on("click", function() {
    getFolderList();
  });

  $(`[data-role="add-folder-tr"]`).on("click", function() {
    let i = $(`[data-role="folders-table-list"] tr`).length + 1;

    if(i === 2 && $(`[data-role="folders-table-list"] tr:first-child`).data("id") === undefined){
      i = 1;
      $(`[data-role="folders-table-list"]`).html(trEmptyComponent(i));
      return;
    }

    $(`[data-role="folders-table-list"]`).append(trEmptyComponent(i));

    $(this).addClass("d-none");
  });

  $(document).on("click", `[data-role="delete-new-item"]`, function() {
    $(this).parents("tr").remove();
  });

  $(document).on("keyup", `[data-type="new-data"]`, function(e) {
    let parent = $(this);

    let data = {
      name: parent.find(`[data-name="name"]`).val(),
    };

    if (e.which === 13 && data.name && data.name.trim()) {
      e.preventDefault();

           let names = [];
           $(`[data-role="folders-table-list"] tr:visible`).each(function(){
             names.push($(this).find(`[data-role="name"]`).data("name"))
           });

           if(names.includes(data.name)){
             return;
           }

           parent.find(`[data-name="name"]`).prop("disabled", true);
          $.post({
            url: `/orders/folders/add`,
            headers,
            data,
            success: function(d){
              if(d.code === 201){
                // Swal.fire("", d.message, "success");
                // parent.parents(".modal").hide();
                getFolderList();
              } else {
                Swal.fire("", d.message, "warning");
              }
            },
            error: function(d){
              console.log(d);
            },
            complete: function(){
              ModalLoader.end();
               parent.find(`[data-name="name"]`).prop("disabled", false);
            }
          });
      }
  });


  // $(`[data-role="save-folder-add-modal"]`).on("click", function() {
  //
  // });

  $(document).on("click",`[data-role="delete"]`,function(e){

    let parent = $(this).parents("tr"),
        id = parent.data("id"),
        name = parent.find(`[data-role="name"]`).data("name"),
        u_sure_delete_lang = name ? (name + " " + lang("_u_sure_delete_this_folder")) : lang("u_sure_delete_this_folder");

    if(parent.data("type") !== "old"){return;}

    if(parent.data("has-orders")){
      u_sure_delete_lang = lang("this_folder_has_orders") + " " + u_sure_delete_lang;
    }

    disableAll(true);
    Swal.fire({
      title: u_sure_delete_lang,
      text: "",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: lang("No"),
      confirmButtonText: lang("Yes"),
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        // ModalLoader.start(lang("Loading"));
        $.ajax({
          url: `/orders/folders/${id}/delete`,
          headers,
          method: "delete",
          success: function(d){
            if(d.code === 200) {
              // Swal.fire("",d.message,'success');
              parent.remove();
              getFolderList();
            } else {
              Swal.fire("",d.message,'warning')
            }
          },
          error: function(d){
            console.error(d);
            Swal.fire(lang("error"),"",'error');
          },
          complete: function(d) {
            // ModalLoader.end();
          }
        })
      }
      disableAll(false);
    })

  });


  let name_in = "";
  $(document).on("focusin", `[data-role="name"]`, function(){
    name_in = $(this).text();
  });
  $(document).on("focusout", `[data-role="name"]`, function(){
    let parent = $(this).parents("tr"),
    el = $(this),
    id = parent.data("id"),
    name = $(this).text();

    if(parent.data("type") !== "old"){return;}

    if(name === name_in){return;}
    el.addClass("loading");
    $.ajax({
      url: `/orders/folders/${id}/edit-name`,
      headers,
      data: JSON.stringify({name, id}),
      method: "PUT",
      success: function(d){
        if(d.code === 202){
          el.addClass("is-valid");
          el.addClass("form-control");
          setTimeout(function () {
            el.removeClass("is-valid");
            el.removeClass("form-control");
          }, 3000);

          el.text(name);
        } else {
          el.text(name_in);
          console.log(d);
        }
      },
      error: function(d){
        console.log(d);
      },
      complete:function(){
        el.removeClass("loading");
      }
    });

  });


  let description_in = "";
  $(document).on("focusin", `[data-role="description"]`, function(){
    description_in = $(this).text();
  });
  $(document).on("focusout", `[data-role="description"]`, function(){
    let parent = $(this).parents("tr"),
    el = $(this),
    id = parent.data("id"),
    description = $(this).text();

    if(parent.data("type") !== "old"){return;}

    if(description === description_in){return;}
    el.addClass("loading");
    el.addClass("form-control");

    $.ajax({
      url: `/orders/folders/${id}/edit-description`,
      headers,
      data: JSON.stringify({description, id}),
      method: "PUT",
      success: function(d){
        if(d.code === 202){
          el.addClass("is-valid");
          el.addClass("form-control");
          setTimeout(function () {
            el.removeClass("is-valid");
            el.removeClass("form-control");
          }, 3000);

          el.text(description);
        } else {
          el.text(description_in);
          console.log(d);
        }
      },
      error: function(d){
        console.log(d);
      },
      complete:function(){
        el.removeClass("loading");
      }
    });

  });

  $(document).on("click", `[data-role="is-active"]`, function() {
    let parent = $(this).parents("tr"),
        id = parent.data("id"),
        el = $(this),
        is_active = $(this).is(":checked") ? "1" : "0";

    el.addClass("loading");
    el.addClass("form-control");

    if(parent.data("type") !== "old"){return;}

    $.ajax({
      url: `/orders/folders/${id}/edit-is-active`,
      headers,
      data: JSON.stringify({is_active, id}),
      method: "PUT",
      success: function(d){
        if(d.code === 202){
          el.addClass("is-valid");
          el.addClass("form-control");
          setTimeout(function () {
            el.removeClass("is-valid");
            el.removeClass("form-control");
          }, 3000);

          if(is_active === "1") {
            el.siblings(`[data-role="is-active-lang"]`).html(lang("Active"));
            el.siblings(`[data-role="is-active-lang"]`).prop("class", "badge badge-success");
          } else {
            el.siblings(`[data-role="is-active-lang"]`).html(lang("Deactive"));
            el.siblings(`[data-role="is-active-lang"]`).prop("class", "badge badge-danger");
          }
        } else {

          console.log(d);
        }
      },
      error: function(d){
        console.log(d);
      },
      complete:function(){
        el.removeClass("loading");
      }
    });
  });

  $(`[name="is_active"]`).on("click", function() {
    if($(this).is(":checked")){
      $(this).siblings(`[data-role="is-active-lang"]`).html(lang("Active"));
      $(this).siblings(`[data-role="is-active-lang"]`).prop("class", "badge badge-success");
    } else {
      $(this).siblings(`[data-role="is-active-lang"]`).html(lang("Deactive"));
      $(this).siblings(`[data-role="is-active-lang"]`).prop("class", "badge badge-danger");
    }
  });

  $(document).on("click", `[data-content="content-edit"]`, function() {
    if($(this).prop("contenteditable") === "false") {
      $(document).find(`[data-content="content-edit"]`).attr("contenteditable", "false");
      $(document).find(`[data-content="content-edit"]`).removeClass("form-control");
      $(this).attr("contenteditable", "true");
      $(this).addClass("form-control");
    }
  });
});
