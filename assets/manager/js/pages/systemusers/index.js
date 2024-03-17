"use strict";
$(function(){
  const trComponent = (v,i,groups,roles) => {
      return `
              <tr data-id="${v.id}" class="${v.me ? "text-success" : ""}" >
                <th>${i}</th>
                <td><img src="${v.photo ?? '/assets/globals/image/no-image.png'}"></td>
                <td><p data-role="groups-p">${v.admin_groups.length ?
                  v.admin_groups.map(v => `${v.name}`).join("<br>")
                : ""}<p><br>
                <span style="cursor: pointer" data-role="add-group" data-bs-toggle="modal" data-bs-target="#addGroup"><i class="fas fa-plus"></i></span>
                </td>
                <td><input type="text" class="form-control" data-role="firstname" value="${v.firstname || ""}"></td>
                <td><input type="text" class="form-control" data-role="lastname" value="${v.lastname || ""}"></td>
                <td><input type="text" class="form-control" data-role="email" value="${v.email || ""}"></td>
                <td><input type="text" class="form-control" data-role="phone" value="${v.phone || ""}"></td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" data-role="dashboard" id="${v.id}d" ${v.dashboard ? "checked" : ""}>
                    <label for="${v.id}d"></label>
                </td>

                <td>
                <select class="custom-select" data-role="role">
                  ${Object.keys(roles).map(f => {
                    return `<option value="${f}" ${v.role === f ? "selected" : ""}>${roles[f]}</option>`
                  }).join("")}
                </select>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" data-role="blocked" id="${v.id}" ${v.blocked ? "checked" : ""}>
                    <label for="${v.id}"></label>
                </td>
                <td>
                  <div class="d-flex alignt-items-center">
                    <button type="button" data-role="save-changes" id="save_btn_${v.id}" type="button" disabled class="btn btn-primary ms-2">
                      <i class="fas fa-save"></i>
                    </button>
                    <button type="button" data-role="edit-password" type="button" class="btn btn-primary ms-2">
                      <i class="fa-solid fa-lock"></i>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete-admin" data-id=${v.id} type="button" class="btn btn-danger ms-2">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
      `;

  };
  const warningComponent = (message) => {
    return `<tr>
              <td style="padding:0;margin:0;" colspan="200">
                <div class="d-flex justify-content-center" >
                  <div style="color: #676689!important;" class="alert" >
                      <strong>${message}</strong>
                  </div>
                </div>
              </td>
            </tr>`;
  };
  let keyword = getUrlParameter("keyword") || "";

  let content_list = {};
  const getContent = (data) => {
    content_list = {};
    $(`[data-role="table-loader"]`).removeClass("d-none");
    $(`.table-responsive`).addClass("load");

    let html = "",
    count = 0;
    customLoader();
    $.get({
      url: `/system-users/list-live`,
      data,
      headers,
      success: function (d) {
        let roles = d.data.roles;
        if (!d.is_developer)  {
          delete roles["developer"];
        }
        if (d.code === 200) {
          let i =0;
           d.data.admins.map((v) => {
            if ( v.is_developer || v["role"] !== "developer" ||  d.is_developer ) {
              content_list[v.id] = v;
              i++;
              html += trComponent(v,i,d.data.groups,roles)
            }
          } );
          count = i;
        }else{
          html = warningComponent(d.message);
        }


        $(`[data-role="content-result-count"]`).html(count);
        $(`[data-role="table-list"]`).html(html);
      },
      error: function (d) {
        console.error(d);
      },
      complete: function () {
        customLoader(true);
        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });

        $(document).find("button").on("blur", function() {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      },
    });
  };
  getContent({keyword});

  const generatePassword = () => {
    let chars = "1234567890aAbBcCdDeEfFghHjJkKLmMnNpPqQrRsStTuUvVwWxXyYzZ",
        string_length = 8,
        randomstring = '',
        charCount = 0,
        numCount = 0;

    for (var i=0; i<string_length; i++) {
        if((Math.floor(Math.random() * 2) == 0) && numCount < 3 || charCount >= 5) {
            var rnum = Math.floor(Math.random() * 10);
            randomstring += rnum;
            numCount += 1;
        } else {
            // If any of the above criteria fail, go ahead and generate an alpha character from the chars string
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum,rnum+1);
            charCount += 1;
        }
    }
    return randomstring;
  };

  $(document).on("click",`[data-role="generate-password"]`,function(){
    let password = generatePassword();
    $(`[data-role="swal-password"]`).val(password);
  })

  $(document).on("keyup",`[data-role="search-filter"]`,function(e){
    keyword = $(this).val();
    if (e.keyCode === 13) {
      filter_url([{keyword}]);
      getContent({keyword});
    }

  })


  // $(document).on("click",`[data-role="save-add-modal"]`,function(event){
  //    let parent = $(`[data-role="add-modal-content"]`);
  //    let name = parent.find(`[name="name"]`).val(),
  //        surname = parent.find(`[name="surname"]`).val(),
  //        email = parent.find(`[name="email"]`).val(),
  //        is_active = parent.find(`[name="is_active"]`).is(":checked") ? 1 : 0,
  //        role = parent.find(`[name="role"]`).val(),
  //        group = parent.find(`[name="group"]`).val(),
  //        password = parent.find(`[name="password"]`).val();
  //
  //    let data = {name,surname,email,role,group,password,is_active};
  //    let isValid = {
  //      email: false,
  //      name: false,
  //      surname: false,
  //      password: false,
  //      role: false,
  //    };
  //
  //
  //    Object.keys(isValid).map(v => {
  //        let alert_message = parent.find(`[name="${v}"]`).siblings(`[data-role="alert-message"]`);
  //        alert_message.addClass("d-none");
  //
  //        if (!data[v].trim()) {
  //          $(`[name="${v}"]`).addClass("is-invalid");
  //          isValid[v] = false;
  //        } else {
  //          $(`[name="${v}"]`).removeClass("is-invalid");
  //          isValid[v] = true;
  //
  //          if(v === "name" && data.name.length < 3) {
  //            $(`[name="${v}"]`).addClass("is-invalid");
  //            alert_message.html(lang("name_min_simvol"));
  //            alert_message.removeClass("d-none");
  //            isValid[v] = false;
  //          }
  //
  //          if(v=== "email" && data.email.length && !validateEmail(data.email)) {
  //            $(`[name="${v}"]`).addClass("is-invalid");
  //            alert_message.html(lang("wrong_email_format"));
  //            alert_message.removeClass("d-none");
  //            isValid[v] = false;
  //          }
  //        }
  //    });
  //
  //    if(!isValid.name || !isValid.surname || !isValid.email || !isValid.password || !isValid.role) return;
  //
  //    $.post({
  //      url: `/system-users/add`,
  //      headers,
  //      data,
  //      success: function(d){
  //        if (d.code === 201) {
  //          getContent();
  //          $(`[name="name"],[name="surname"],[name="role"],[name="email"],[name="password"],[name="group"]`).val("");
  //
  //          Swal.fire({
  //            title: lang("Login information"),
  //            confirmButtonText: lang("Close"),
  //            html:`<div data-role="login-info"><p class="mb-2">${lang("Email")}: <b>${data.email}</b> </p>\n<p class="mb-2">${lang("Password")}: <b>${data.password}</b> </p></div>
  //            <a href="javascript:void(0)" data-role="copy">${lang("Copy")}<i class="fa fa-copy"></i></a>`
  //          });
  //          parent.parents(".modal").hide();
  //          $('body').removeClass('modal-open');
  //         $('.modal-backdrop').remove();
  //        } else {
  //          Swal.fire('',d.message,'warning');
  //        }
  //      },
  //      error: function(d){
  //        console.error(d);
  //      },
  //      complete: function(){
  //        $(`[id="add-modal"]`).hide();
  //        $('body').removeClass('modal-open');
  //       $('.modal-backdrop').remove();
  //      }
  //    })
  //  });

  $(document).on("click",`[data-role="delete-admin"]`,function(e){
    let parent = $(this).parents("tr");
    let system_user_id = parent.data("id");

    Swal.fire({
      title: lang("Are u sure to delete this user"),
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
        ModalLoader.start(lang("Loading"));
        $.ajax({
          url: `/system-users/${system_user_id}/delete`,
          headers,
          method: "delete",
          success: function(d){
            if(d.code === 202) {
              parent.remove();
            }
            Swal.fire("",d.message,d.code === 202 ? "success" : "warning");

          },
          error: function(e){
            console.error(e)
          },
          complete: function(d) {
            ModalLoader.end();
          }
        })
      }
    })

  });


  $(document).on("change",`[data-role="firstname"],[data-role="lastname"],[data-role="email"],[data-role="phone"],[data-role="dashboard"],[data-role="group"],[data-role="role"],[data-role="blocked"]`,function(){
    let parent = $(this).parents("tr"),
        id = parent.data("id");
    let data = {
      firstname : parent.find(`[data-role="firstname"]`).val() || null,
      lastname : parent.find(`[data-role="lastname"]`).val() || null,
      email : parent.find(`[data-role="email"]`).val() || null,
      phone : parent.find(`[data-role="phone"]`).val() || null,
      dashboard : parent.find(`[data-role="dashboard"]`).prop("checked"),
      // group_id : parent.find(`[data-role="group"]`).val() || null,
      role : parent.find(`[data-role="role"]`).val() || null,
      blocked : parent.find(`[data-role="blocked"]`).prop("checked")
    }
    let has_update = {
      firstname : false,
      lastname : false,
      email : false,
      phone : false,
      dashboard : false,
      // group_id : false,
      role : false,
      blocked : false
    }
    Object.keys(data).map(v => {
      if (content_list[id][v] !== data[v]) {
        has_update[v] = true;
      }else{
        has_update[v] = false;
      }
    })
    if (has_update.firstname || has_update.lastname || has_update.email || has_update.phone || has_update.dashboard || has_update.role || has_update.blocked) {
      $(`#save_btn_${id}`).prop("disabled",false);

    }else{
      $(`#save_btn_${id}`).prop("disabled",true);

    }

  })


  $(document).on("click",`[data-role="save-changes"]`,function(){
    let parent = $(this).parents("tr"),
        id = parent.data("id"),
        data = {
          firstname: parent.find(`[data-role="firstname"]`).val() || null,
          lastname: parent.find(`[data-role="lastname"]`).val() || null,
          email: parent.find(`[data-role="email"]`).val() || null,
          phone: parent.find(`[data-role="phone"]`).val() || null,
          role: parent.find(`[data-role="role"]`).val() || null,
          dashboard: parent.find(`[data-role="dashboard"]`).prop("checked") ? "yes" : "no",
          // group_id: parent.find(`[data-role="group"]`).val() || null,
          blocked: parent.find(`[data-role="blocked"]`).prop("checked") ? "yes" : "no",
        };
    customLoader();
    $.ajax({
      type: `put`,
      url: `/system-users/${id}/edit`,
      data: JSON.stringify(data),
      headers,
      success: function(d){
        if (d.code === 202) {
          data.dashboard = data.dashboard === "yes";
          data.blocked = data.blocked === "yes";
          Object.keys(data).map(v => {
            content_list[id][v] = data[v];
          })
          $(`#save_btn_${id}`).prop("disabled",true);
        }
        Swal.fire("",d.message,d.code === 202 ? "success" : "warning")
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        customLoader(true);
      }
    })
  })

  $(document).on("click",`[data-role="edit-password"]`,function(){
    let id = $(this).parents("tr").data("id"),
        password = generatePassword();
    let email = $(this).parents("tr").find(`[data-role="email"]`).val();
    Swal.fire({
      title: lang("Are u sure to edit password") + "?",
      html: `<div class="input-group">
               <input autocomplete="off" type="text" data-role="swal-password" class="form-control" placeholder="${lang("Password")}" value="${password}">
               <div class="input-group-prepend">
                 <span style="cursor:pointer;" class="input-group-text" data-role="generate-password"><i class="fas fa-sync-alt"></i></span>
               </div>
             </div>`,
      showCancelButton:true,
      confirmButtonText: lang("Save"),
      cancelButtonText: lang("Cancel"),
      confirmButtonColor: "#399CE3",
      reverseButtons:true
    }).then((res) => {
        if (res.isConfirmed) {
          ModalLoader.start(lang("Loading") + "...")
          $.ajax({
            url: `/system-users/${id}/edit-password`,
            type: "PUT",
            headers,
            data: JSON.stringify({id,password: $(`[data-role="swal-password"]`).val()}),
            success: function(d){
              if (d.code === 202) {
                Swal.fire({
                  title: lang("Login information"),
                  confirmButtonText: lang("Close"),
                  html:`<div data-role="login-info"><p class="mb-2">${lang("Url")}: <b data-role="url">${window.location.origin}</b></p>\n<p class="mb-2">${lang("User")}: <b data-role="user">${email}</b></p>\n<p class="mb-2">${lang("Password")}: <b>${d.data.password}</b></p></div>
                        <a href="javascript:void(0)" data-role="copy" data-refresh="0">${lang("Copy")} <i class="fa fa-copy"></i></a>`
                });
              } else {
                Swal.fire("",d.message,"warning");
              }
            },
            error: function(d){
              console.error(d);
            },
            complete: function(){
              ModalLoader.end();
            }
          })
        }

    })

  });

  let validatePhone = (d) => /^[\+]?[(]?[0-9]{2}[)]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{2}[-\s\.]?[0-9]{2}$/im.test(d);
  let glob_phone = $(`[name="phone"]`).val().replace(/-/g, '').replace(/[()]/g, '');
  let enable_btn = {
    phone: validatePhone(glob_phone),
    pwd: false
  };
  $('[name="phone"]').inputmask({
    mask: '(99)-999-99-99',
    autoUnmask: true,
    removeMaskOnSubmit: true,
  });

  $(document.body).on('mouseover', '[name="phone"]', function(){
    $('[name="phone"]').attr('placeholder' , '');
  });

  $(document.body).on("keyup change",`[name="phone"]`,function(){
    let t = $(this);
    let phone = t.val().replace(/-/g, '').replace(/[()]/g, '');
    if (validatePhone(phone)) {
      validateInput(t,enable_btn.phone = true,"")
    }else{
      validateInput(t,enable_btn.phone = false,t.data("error-text"))
    }
  });


    const groupsComponent = (v) => {
      return `<option value="${v.id}">${v.name}</option>`;
    }

    let groups_loaded = false;
    $(document).on("click",`[data-bs-target="#addUser"]`,function(){
      if (groups_loaded) {
        $(`#addUser`).find(`[name="password"]`).val(generatePassword());
      }
      if (!groups_loaded) {
        $.get({
          url: `/system-users/groups`,
          headers,
          success: function(d){
            let h = "";
            if (d.code === 200) {
              groups_loaded = true;
              $(`#addUser`).find(`[name="group"]`).prop("disabled",false)
                $(`#addUser`).find(`[name="password"]`).val(generatePassword());

              d.data.map(v => {
                h += groupsComponent(v);
              })
            }
            $(`#addUser`).find(`[name="group"]`).html(h)
          },
          error: function(e){
            console.error(e)
          }
        });
      }

      if (!groups_loaded) {
        $.get({
          url: `/system-users/groups`,
          headers,
          success: function(d){
            let h = "";
            if (d.code === 200) {
              groups_loaded = true;
              $(`#addUser`).find(`[name="group"]`).prop("disabled",false)
                $(`#addUser`).find(`[name="password"]`).val(generatePassword());

              d.data.map(v => {
                h += groupsComponent(v);
              })
            }
            $(`#addUser`).find(`[name="group"]`).html(h)
          },
          error: function(e){
            console.error(e)
          }
        });
      }

    })


  $(document).on("click",`[data-role="add-user-button"]`,function(){
    let parent = $(`#addUser`);

    let data = {
      name: parent.find(`[name="name"]`).val(),
      surname: parent.find(`[name="surname"]`).val(),
      phone: parent.find(`[name="phone"]`).val().replace(/-/g, '').replace(/[()]/g, ''),
      email: parent.find(`[name="email"]`).val(),
      // group: parent.find(`[name="group"]`).val(),
      role: parent.find(`[name="role"]`).val(),
      password: parent.find(`[name="password"]`).val(),
      dashboard: parent.find(`[name="dashboard"]`).prop("checked") ? "1" : "0",
    },
    isInvalid = {
      name: true,
      phone: true,
      surname: true,
      email: true,
      // group: true,
      role: true,
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

    if (isInvalid.name || isInvalid.surname || isInvalid.email || isInvalid.phone || isInvalid.role) {
      return;
    }

    customLoader();

    $.post({
      url: `/system-users/add`,
      data,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 201) {
          parent.modal("hide");
          parent.find(`[name="name"],[name="surname"],[name="phone"],[name="email"]`).val("")
          parent.find(`[name="group"]`).val("").change();
          parent.find(`[name="password"]`).val(generatePassword());
          Swal.fire({
            title: lang("Login information"),
            confirmButtonText: lang("Close"),
            html: `<div data-role="login-info"><p class="mb-2">${lang("Url")}: <b data-role="url">${window.location.origin}</b></p>\n<p class="mb-2">${lang("User")}: <b data-role="user">${d.data.email}</b></p>\n<p class="mb-2">${lang("Password")}: <b data-role="password">${d.data.password}</b></p></div>
                  <a href="javascript:void(0)" data-role="copy-info">${lang("Copy")}
                  <i class="fa fa-copy"></i></a>`
          }).then(v => {
            getContent({keyword});
          });
        }
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        customLoader(true);
      }
    })

  })


  $(document).on("click",`[data-role="copy-info"]`,function(){
    let text = $(`[data-role="login-info"]`).text().trim();
    if(!text || text.trim() == ''){
      return;
    }
    copyTextToClipboard(text,() => {
      Swal.fire("",lang("Copied"),"success").then(v => {
          getContent({keyword});
      })
    });
  });


  $(document).on("click",`[data-role="copy"]`,function(){
    let text = $(`[data-role="login-info"]`).text().trim();
    if(!text || text == ''){
      return;
    }

    let refresh = ("" + $(this).data("refresh")) !== "0";

    copyTextToClipboard(text,() => {
      Swal.fire("",lang("Copied"),"success").then(v => {
        if (refresh) {
          getContent({keyword});
        }
      });
    });
  });

  $(document).on("click",`[data-role="generate-password"]`,function(){
    $(`[name="password"]`).val(generatePassword());
  });

  const orderGroupsComponent = (v) => {
    return `<option value="${v.id}">${v.name}</option>`;
  }

  let order_groups_loaded = false;
  $(document).on("click",`[data-role="add-group"]`,function(){
    let id = $(this).parents("tr").data("id"),
        exist_groups = content_list[id]["admin_groups"];
        exist_groups = exist_groups.map(v => v.id);
    if (order_groups_loaded) {
      $(`#addGroup`).find(`[name="order-group"]`).val(exist_groups).change()
    }
    $(`#addGroup [data-role="add-group-button"]`).data("id",id);
    if (!order_groups_loaded) {
      customLoader();
      $.get({
        url: `/system-users/order-groups`,
        headers,
        success: function(d){
          let h = "";
          if (d.code === 200) {
            order_groups_loaded = true;
            $(`#addGroup`).find(`[name="order-group"]`).prop("disabled",false)
            d.data.map(v => {
              h += orderGroupsComponent(v);
            })
          }
          $(`#addGroup`).find(`[name="order-group"]`).html(h).val(exist_groups).trigger("change")
        },
        error: function(e){
          console.error(e)
        },
        complete: function(){
          customLoader(true)
        }

      });
    }

  })


  $(document).on("click",`[data-role="add-group-button"]`,function(){
    let parent = $(`#addGroup`),
        groups = parent.find(`[name="order-group"]`).val(),
        details = [],
        id = $(this).data("id");
    groups.map(function(v){
        let  text = parent.find(`[name="order-group"] option[value="${v}"]`).text();
      details.push(text);
    })
    // if (!groups.length) {
    //   Swal.fire("",lang("Select a group"),"warning")
    //   return;
    // }
    customLoader();
    $.ajax({
      type: `put`,
      url: `/system-users/${id}/add-order-group`,
      data: JSON.stringify({groups}),
      headers,
      success: function(d){
        if (d.code === 202) {
          parent.modal("hide");
          $(`tr[data-id="${id}"] [data-role="groups-p"]`).html(details.map(v => `${v}<br>` ))
        }
      },
      error: function(e){
        console.error(e);
      },
      complete: function(){
        customLoader(true)
      }
    })
  })
});
