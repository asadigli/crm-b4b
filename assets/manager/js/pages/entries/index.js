$(function () {
  let keyword = getUrlParameter("keyword") || "",
      sort_by = getUrlParameter("sort_by") || "",
      search_by_blocks = getUrlParameter("search_by_blocks") || "";

  const entryComponent = (v, i) => {
    return `<tr data-id="${v.id}">
                <td >${offset + (++i)}</td>
                <td><img src="${v.avatar ?? '/assets/globals/image/no-image.png'}"></td>
                <td style="max-width:250px"><p class="text-wrap" data-role="customers-p">${v.customers.length ?
                  v.customers.map(v => `${v.name +  (v.ava_id ? " - "  + "(" + v.ava_id + ")" : "") + (v.currency_name ? " - " + v.currency_name : "") }`).join("<br>")
                : ""}<p><br>
                <span style="cursor: pointer" data-role="add-customer" data-bs-toggle="modal" data-bs-target="#addCustomer"><i class="fas fa-plus"></i></span>
                </td>
                <td>
                  ${v.customers.length ?
                    v.customers.map(customer => `
                      <a href="${customer.id ? `/customers/${customer.id}/account` : "javascript:void(0)"}" target="_blank" class="link">
                      ${customer.ava_code ?? ""}
                      </a>`
                    ).join("<br>")
                  : ""}
                  <p><br>
                </td>
                <td style="min-width:160px">
                  <input type="text" class="form-control" data-text="name" value="${v.name || ""}">
                  <br>
                  <input type="text" class="form-control" data-text="email" value="${v.email || ""}">
                  <br>
                  <input type="text" class="form-control" data-text="phone" value="${v.phone || ""}"></td>
                <td data-role="curator">${v.curator.name ? v.curator.name :  ""}</td>
                <td data-role="responsible-person">${ (v.person_name || "") + " " + (v.person_surname || "")}</td>
                <td data-role="city">${v.city.name ? v.city.name : ""}</td>
                <td data-role="warehouse">${v.warehouse.name ? v.warehouse.name : ""}</td>
                <td>
                <div class="input-group num-input-parent">
                  <div class="input-group-prepend">
                    <span class="input-group-text first" data-role="used-limit"
                    data-toggle="tooltip"
                    title="${v.used_limit ? `Web: ${v.used_limit.web}${Object.keys(v.entry_limit_ips).length && Array.isArray(v.entry_limit_ips.web) && v.entry_limit_ips.web.length ? `(${v.entry_limit_ips.web.join("\n")})` : ""}` : ""} |
                    ${v.used_limit ? `Mobile: ${v.used_limit.mobile}${Object.keys(v.entry_limit_ips).length && Array.isArray(v.entry_limit_ips.mobile) && v.entry_limit_ips.mobile.length ? `(${v.entry_limit_ips.mobile.join("\n")})` : ""}` : ""} |
                    ${v.used_limit ? `${lang("Unknown")}: ${v.used_limit.unknown}${Object.keys(v.entry_limit_ips).length && Array.isArray(v.entry_limit_ips.unknown) && v.entry_limit_ips.unknown.length ? `(${v.entry_limit_ips.unknown.join("\ns")})` : ""}` : ""}">${v.used_limit.all ?? "0"}</span>
                  </div>
                  <input data-role="entry-limit"
                  data-min="0"
                  value="${v.entry_limit || 0}"
                  type="number"
                  class="form-control num-input"
                  style="padding: 0.5rem;max-height: 42px;"
                  step="1">
                  <div class="input-group-append">
                    <button data-role="save-entry-limit" data-toggle="tooltip" data-reset="1" data-placement="top" class="btn btn-warning" title="${lang("Reset")}">
                      <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                    <button data-role="save-entry-limit" data-toggle="tooltip" data-reset="0" data-placement="top" class="btn btn-primary" title="${lang("Save")}">
                      <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                  </div>
                </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" data-role="stock-show" id="${v.id}" ${v.stock_show ? "checked" : ""}>
                    <label for="${v.id}"></label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" data-role="store-show" id="${v.id}i" ${v.is_store_active ? "checked" : ""}>
                    <label for="${v.id}i">60-90</label>
                  </div>
                </td>
                <td>
                  <div class="custom-control custom-checkbox badge badge-${v.is_blocked ? "danger" : "success"}">
                    <input type="checkbox" data-role="is-blocked" id="${v.id}_block" ${v.is_blocked ? "checked" : ""}>
                    <label for="${v.id}_block">${+v.is_blocked ? lang("Blocked") : lang("Unblocked")}</label>
                  </div>
                </td>
                <td>${v.online_count ? `<i style="color:green;" class="fa-solid fa-circle"></i>` : `<i style="color:red;" class="fa-solid fa-circle"></i> <p>${v.last_online || ""}</p>`}</td>
                <td><i data-toggle="tooltip" data-placement="top" title="${v.added_date}" class="fa-solid fa-clock"></i></td>
                <td>
                  <div class="d-flex justify-content-end">
                    <button data-toggle="tooltip" data-placement="top" title="${lang("Reset password")}" type="button" data-role="edit-password" type="button" class="btn btn-primary ms-2">
                      <i class="fa-solid fa-lock"></i>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="${lang("Edit")}" data-bs-toggle="modal" data-bs-target="#editProperties" type="button" data-role="edit-properties" type="button" class="btn btn-warning ms-2">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="${lang("Delete")}" type="button" data-role="delete-user" type="button" class="btn btn-danger ms-2">
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


  $(document).on("click", `[data-role="save-entry-limit"]`, function () {
    let id = $(this).parents("tr").data("id"),
        elem = $(this),
        limit = $(this).parents("td").find(`[data-role="entry-limit"]`).val(),
        reset = $(this).data("reset");
    if (+reset === 1) {
      Swal.fire({
        title: lang("Are u sure to reset entries of this user") + "?",
        showCancelButton: true,
        confirmButtonText: lang("Yes"),
        cancelButtonText: lang("No"),
        reverseButtons: true
      }).then( swal => {
        if (swal.isConfirmed) {
          customLoader();
          $.ajax({
            url: `/entries/${id}/entry-limit`,
            type: "PUT",
            data: JSON.stringify({ limit, reset }),
            headers,
            success: function (d) {
              if (d.code === 202) {
                  elem.parents("td").find(`[data-role="used-limit"]`).text("0")
              }
              Swal.fire("", d.message, d.code === 202 ? "success" : "warning")
            },
            error: function (e) {
              console.error(e)
            },
            complete: function () {
              customLoader(true)
            }
          })
        }

      })
    }else{
      customLoader();
      $.ajax({
        url: `/entries/${id}/entry-limit`,
        type: "PUT",
        data: JSON.stringify({ limit, reset }),
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
    }

  })

  $(document).on("change", `[data-text="name"],[data-text="email"],[data-text="phone"]`, function () {
    id = $(this).parents("tr").data("id");
    let data = {
      key: $(this).data("text"),
      value: $(this).val()
    }
    customLoader();
    $.ajax({
      type: "PUT",
      url: `/entries/${id}/edit-detail`,
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
  })

  let offset = 0,
    loading = false,
    content_list = {};
  const listAll = (data, first_time = true) => {
    let counter = null;
    if (first_time) {
      let start_time_interval = new Date().getTime();
      counter = setInterval(() => {
        $(`[data-role="content-result-time"]`).html((new Date().getTime() - start_time_interval) / 1000)
      }, 100);
      customLoader()
    }
    content_list = {};
    $.get({
      url: `/entries/list`,
      data,
      headers,
      success: function (d) {
        let h = '';
        if (d.code === 200) {
          count = d.data && d.data.count ? d.data.count : 0;
          d.data.entries.map((v, i) =>{
            content_list[v.id] = v;
            h += entryComponent(v, i);
          })
          $(`[data-role="content-result-count"]`).html(d.data.count);
          if (count > offset && count > d.data.limit) {
            $(`[data-role="load-more-container"]`).removeClass("d-none").addClass("loading");
          } else {
            $(`[data-role="load-more-container"]`).addClass("d-none");
          }
          if (offset + d.data.entries.length >= count) {
            $(`[data-role="load-more-container"]`).addClass("d-none");
          }
        } else {
          h = warningComponent(d.message);
          $(`[data-role="load-more-container"]`).addClass("d-none");
          $(`[data-role="content-result-count"]`).html("0");
        }


        if (first_time) {
          $(`[data-role="entries-list"]`).html(h);
          $(`[data-role="entries-list"] > tr`)
        } else {
          $(`[data-role="entries-list"]`).append(h);
        }
      },
      error: function (e) {
        console.error(e)
      },
      complete: function () {
        loading = false;
        if (first_time) {
          clearInterval(counter);
          customLoader(true);
        }

        $(document).find('[data-toggle="tooltip"]').tooltip();

        $(document).find('[data-toggle="tooltip"]').click(function () {
          $(document).find('[data-toggle="tooltip"]').tooltip("hide");
        });
      }
    })
  }

  listAll({ keyword, offset, sort_by, search_by_blocks });


  $(document).on("scroll", function () {
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="entries-list"] > tr`).length;
    loading = true;
    listAll({ keyword, offset, sort_by, search_by_blocks }, false);
  });

  $(document).on("click", `[data-role="load-more-container"]`, function () {
    if (!$(`[data-role="load-more-container"]`).isInViewport() || loading) return;
    offset = $(`[data-role="table-list"] > tr`).length;
    loading = true;
    listAll({ keyword, offset, sort_by, search_by_blocks }, false);
  });


  $(document).on("keyup", `[data-role="search-filter"]`, function (e) {
    keyword = $(this).val();
    sort_by = $(`[data-role="sort_by"]`).val();
    search_by_blocks = $(`[data-role="search_by_blocks"]`).val();
    offset = 0;
    if (e.keyCode === 13) {
      filter_url([{ keyword }, {sort_by}, {search_by_blocks}]);
      listAll({ keyword, offset, sort_by, search_by_blocks });
    }
  });

  $(document).on("click", `[data-role="search-btn"]`, function (e) {
    keyword = $(`[data-role="search-filter"]`).val();
    sort_by = $(`[data-role="sort_by"]`).val();
    search_by_blocks = $(`[data-role="search_by_blocks"]`).val();
    offset = 0;
    filter_url([{ keyword }, {sort_by}, {search_by_blocks}]);
    listAll({ keyword, offset, sort_by, search_by_blocks });
  });

  $(document).on("click", `[data-role="delete-user"]`, function () {
    let id = $(this).parents("tr").data("id");
    Swal.fire({
      title: lang("Are u sure to delete this user") + "?",
      showCancelButton: true,
      confirmButtonText: lang("Delete"),
      cancelButtonText: lang("Cancel"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        customLoader();
        $.ajax({
          url: `/entries/${id}/delete`,
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

  const generatePassword = () => {
    let chars = "1234567890",
      string_length = 6,
      randomstring = '',
      charCount = 0,
      numCount = 0;

    for (var i = 0; i < string_length; i++) {
      if ((Math.floor(Math.random() * 2) == 0) && numCount < 3 || charCount >= 5) {
        var rnum = Math.floor(Math.random() * 10);
        randomstring += rnum;
        numCount += 1;
      } else {
        // If any of the above criteria fail, go ahead and generate an alpha character from the chars string
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum, rnum + 1);
        charCount += 1;
      }
    }
    return randomstring;
  }

  $(document).on("click", `[data-role="generate-password"]`, function () {
    let password = generatePassword();
    $(`[data-role="swal-password"]`).val(password);
  })


  // Copy credentials
  $(document).on("click", `[data-role="copy"]`, function () {
    let text = $(`[data-role="login-info"] b`).text().trim();
    if (!text || text == '') {
      return;
    }
    // console.log(text);
    copyTextToClipboard(text,() => {
      Swal.fire("", lang("Copied"), "success").then(v => {
        listAll({ keyword, offset, sort_by, search_by_blocks });
      });
    });
  });

  $(document).on("click", `[data-role="edit-password"]`, function () {
    let id = $(this).parents("tr").data("id"),
      password = generatePassword(),
      email = $(this).parents("tr").find(`[data-text="email"]`).val();

    Swal.fire({
      title: lang("Are u sure to edit password") + "?",
      html: `<div class="input-group">
             <input autocomplete="off" type="text" data-role="swal-password" class="form-control" placeholder="${lang("Password")}" value="${password}">
             <div class="input-group-prepend">
               <span class="input-group-text last" data-role="generate-password"><i class="fas fa-sync-alt"></i></span>
             </div>
           </div>`,
      showCancelButton: true,
      confirmButtonText: lang("Save"),
      cancelButtonText: lang("Cancel"),
      confirmButtonColor: "#399CE3",
      reverseButtons: true
    }).then((res) => {
      if (res.isConfirmed) {
        ModalLoader.start(lang("Loading") + "...")
        $.ajax({
          type: "put",
          url: `/entries/${id}/edit-password`,
          headers,
          data: JSON.stringify({ id, email, password: $(`[data-role="swal-password"]`).val() }),
          success: function (d) {
            if (d.code === 202) {
              Swal.fire({
                title: lang("Login information"),
                confirmButtonText: lang("Close"),
                html: `<div data-role="login-info"><p class="mb-2">${lang("Url")}: <b data-role="url">${$(`[data-role="entries-list"]`).data("base-url")}</b></p>\n<p class="mb-2">${lang("User")}: <b data-role="user">${d.data.email}</b></p>\n<p class="mb-2">${lang("Password")}: <b data-role="password">${d.data.password}</b></p></div>
                      <a href="javascript:void(0)" data-role="copy-all" data-refresh="0">${lang("Copy")}
                      <i class="fa fa-copy"></i></a>`
              })
            } else {
              Swal.fire("", d.message, "warning");
            }
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            ModalLoader.end();
          }
        })
      }

    })

  });

  $(document).on("change", `[data-role="store-show"]`, function(){
    let $th = $(this);
    let id = $th.parents("tr").data("id"),
        is_store_active = $th.prop("checked") ? "1" : "0";

    Swal.fire({
      title: is_store_active === "1" ? lang("Do you want to show 60-90 day products to this user?") : lang("Do you want to hide 60-90 day products to this user?"),
      showCancelButton: true,
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        customLoader();
        $.ajax({
          url: `/entries/${id}/store-active`,
          type: "PUT",
          headers,
          data: JSON.stringify({is_store_active}),
          success: function (d) {
            Swal.fire("", d.message, d.code === 202 ? "success" : "warning");
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            customLoader(true)
          }
        })
      } else {
        $th.prop("checked", !$th.is(":checked"))
      }
    });
  });

  $(document).on("change", `[data-role="stock-show"]`, function () {
    let $th = $(this);
    let id = $th.parents("tr").data("id"),
      stock_show = $th.prop("checked") ? "1" : "0";
    Swal.fire({
      title: lang("Are u sure to change this users stock show status") + "?",
      showCancelButton: true,
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        customLoader();
        $.ajax({
          url: `/entries/${id}/stock-show`,
          type: "PUT",
          headers,
          data: JSON.stringify({ stock_show }),
          success: function (d) {
            Swal.fire("", d.message, d.code === 202 ? "success" : "warning");
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            customLoader(true)
          }
        })
      } else {
        $th.prop("checked", !$th.is(":checked"))
      }
    });
  });

  const supervisorsComponent = (v) => {
    return `<option value="${v.id}">${v.name}</option>`;
  }

  const customersComponent = (v) => {
    return `<option data-id=${v.id} value="${v.remote_id}">${v.name + " - " + "(" + v.remote_id + ")"}</option>`;
  }



  let customers_loaded = false,
      supervisors_loaded = false,
      properties_loaded = false;
  $(document).on("click",`[data-bs-target="#addEntry"]`,function(){
    $(`#addEntry`).find(`[name="password"]`).val(generatePassword());
    if (!customers_loaded) {
      $.get({
        url: `/entries/ava-customers`,
        headers,
        success: function(d){
          let h = `<option value="">${$(`[name="customer"]`).data("text")}</option>`;
          if (d.code === 200) {
            customers_loaded = true;
            $(`#addEntry`).find(`[name="customer"]`).prop("disabled",false)
            d.data.map(v => {
              h += customersComponent(v);
            })
          }
          $(`#addEntry`).find(`[name="customer"]`).html(h)
        },
        error: function(e){
          console.error(e)
        }

      });
    }

    if (!supervisors_loaded) {
      $.get({
        url: `/entries/supervisors`,
        headers,
        success: function(d){
          let h = `<option value="">${$(`[name="supervisor"]`).data("text")}</option>`;
          if (d.code === 200) {
            supervisors_loaded = true;
            $(`#addEntry`).find(`[name="supervisor"]`).prop("disabled",false)
            $(`#editProperties`).find(`[name="supervisor"]`).prop("disabled",false)
            d.data.map(v => {
              h += supervisorsComponent(v);
            })
          }
          $(`#addEntry`).find(`[name="supervisor"]`).html(h)
          $(`#editProperties`).find(`[name="supervisor"]`).html(h)
        },
        error: function(e){
          console.error(e)
        }
      });
    }

    if (!properties_loaded) {
      $.get({
        url: `/entries/properties`,
        headers,
        success: function(d){
          let c_html = `<option value="">${$(`[name="city"]`).data("text")}</option>`;
              w_html = `<option value="">${$(`[name="warehouse"]`).data("text")}</option>`;
          if (d.code === 200) {
            properties_loaded = true;
            $(`#addEntry`).find(`[name="city"]`).prop("disabled",false)
            $(`#editProperties`).find(`[name="city"]`).prop("disabled",false)
            $(`#addEntry`).find(`[name="warehouse"]`).prop("disabled",false)
            $(`#editProperties`).find(`[name="warehouse"]`).prop("disabled",false)

            d.data.cities.map(v => {
              c_html += supervisorsComponent(v);
            })

            d.data.warehouses.map(v => {
              w_html += supervisorsComponent(v);
            })
          }
          $(`#addEntry`).find(`[name="city"]`).html(c_html)
          $(`#addEntry`).find(`[name="warehouse"]`).html(w_html)
          $(`#editProperties`).find(`[name="city"]`).html(c_html)
          $(`#editProperties`).find(`[name="warehouse"]`).html(w_html)
        },
        error: function(e){
          console.error(e)
        }
      });
    }


  })

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

  $(document).on("click",`[data-role="add-entry-button"]`,function(){

    let parent = $(`#addEntry`);
    let customers = parent.find(`[name="customer"]`).val();
    let customer = [];
    customers.map(function(v){
      let value = parent.find(`[name="customer"] option[value="${v}"]`).data("id");
      customer.push(value)
    });
    let data = {
      name: parent.find(`[name="name"]`).val(),
      entry_name: parent.find(`[name="entry_name"]`).val(),
      surname: parent.find(`[name="surname"]`).val(),
      phone: parent.find(`[name="phone"]`).val().replace(/-/g, '').replace(/[()]/g, ''),
      email: parent.find(`[name="email"]`).val(),
      address: parent.find(`[name="address"]`).val(),
      supervisor: parent.find(`[name="supervisor"]`).val(),
      warehouse: parent.find(`[name="warehouse"]`).val(),
      city: parent.find(`[name="city"]`).val(),
      customer,
      password: parent.find(`[name="password"]`).val(),
      limit: parent.find(`[name="limit"]`).val(),
      is_active: parent.find(`[name="is_active"]`).prop("checked") ? "1" : "0",
      stock_show: parent.find(`[name="stock_show"]`).prop("checked") ? "1" : "0",
      is_store_active: parent.find(`[name="is_store_active"]`).prop("checked") ? "1" : "0"
    },
    isInvalid = {
      entry_name: true,
      name: true,
      phone: true,
      surname: true,
      email: true,
    }

    Object.keys(isInvalid).map(v => {
      if (!data[v].trim() || (v === "email" && !validateEmail(data[v].trim()))) {
        parent.find(`[name=${v}]`).addClass("is-invalid");
        isInvalid[v] = true
      }else{
        isInvalid[v] = false;
        parent.find(`[name=${v}]`).removeClass("is-invalid");
      }
    })
    if (!customer.length) {
      parent.find(`[name="customer"]`).addClass("is-invalid")
      return;
    }else{
      parent.find(`[name="customer"]`).addClass("is-invalid")
    }

    if (isInvalid.name || isInvalid.entry_name || isInvalid.surname || isInvalid.email || isInvalid.phone) {
      return;
    }

    customLoader();

    $.post({
      url: `/entries/add`,
      data,
      headers,
      success: function(d){
        if (d.code === 201) {
          parent.modal("hide");
          parent.find(`[name="name"],[name="entry_name"],[name="surname"],[name="phone"],[name="email"],[name="limit"]`).val("")
          parent.find(`[name="customer"],[name="supervisor"]`).val("").change();
          parent.find(`[name="password"]`).val(generatePassword());
          Swal.fire({
            title: lang("Login information"),
            confirmButtonText: lang("Close"),
            html: `<div data-role="login-info"><p class="mb-2">${lang("Url")}: <b data-role="url">${$(`[data-role="entries-list"]`).data("base-url")}</b></p>\n<p class="mb-2">${lang("User")}: <b data-role="user">${d.data.email}</b></p>\n<p class="mb-2">${lang("Password")}: <b data-role="password">${d.data.password}</b> </p></div>
                  <a href="javascript:void(0)" data-role="copy-all">${lang("Copy")}
                  <i class="fa fa-copy"></i></a>`
          }).then(v => {
            listAll({ keyword, offset, sort_by, search_by_blocks });
          });
        } else {
          Swal.fire("",d.message,"warning")
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


  $(document).on("click",`[data-role="generate"]`,function(){
    let pass = generatePassword();
    $("#addEntry").find(`[name="password"]`).val(pass);
  })

  $(document).on("click",`[data-role="copy-all"]`,function(){
    let text = $(`[data-role="login-info"]`).text();
    if(!text || text.trim() === ''){
      return;
    }

    let refresh = ("" + $(this).data("refresh")) !== "0";

    copyTextToClipboard(text,() => {
      Swal.fire("", lang("Copied"), "success").then(v => {
        if (refresh) {
          listAll({ keyword, offset, sort_by, search_by_blocks });
        }
      });
    });
  })


  let add_customer_loaded = false;
  $(document).on("click",`[data-role="add-customer"]`,function(){
    let id = $(this).parents("tr").data("id"),
        exist_customers = content_list[id]["customers"];
        exist_customers = exist_customers.map(v => v.ava_id);
    if (add_customer_loaded) {
      $(`#addCustomer`).find(`[name="customer"]`).val(exist_customers).change()
    }
    $(`#addCustomer [data-role="add-customer-button"]`).data("id",id);
    if (!add_customer_loaded) {
      customLoader();
      $.get({
        url: `/entries/ava-customers`,
        headers,
        success: function(d){
          // console.log(d);
          let h = `<option value="">${$(`[name="customer"]`).data("text")}</option>`;
          if (d.code === 200) {
            add_customer_loaded = true;
            $(`#addCustomer`).find(`[name="customer"]`).prop("disabled",false)
            d.data.map(v => {
              h += customersComponent(v);
            })
          }
          $(`#addCustomer`).find(`[name="customer"]`).html(h).val(exist_customers).trigger("change")
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


  $(document).on("click",`[data-role="add-customer-button"]`,function(){
    let parent = $(`#addCustomer`),
        customers = parent.find(`[name="customer"]`).val(),
        customer = [],
        details = [],
        id = $(this).data("id");

    customers.map(function(v){
      let value = parent.find(`[name="customer"] option[value="${v}"]`).data("id");
          text = parent.find(`[name="customer"] option[value="${v}"]`).text(),
      customer.push(value)
      details.push(text);
    })
    if (!customer.length) {
      Swal.fire("",lang("Select a customer"),"warning")
      return;
    }
    customLoader();
    $.ajax({
      type: `put`,
      url: `/entries/${id}/add-customer`,
      data: JSON.stringify({customer}),
      headers,
      success: function(d){
        if (d.code === 202) {
          parent.modal("hide");
          $(`tr[data-id="${id}"] [data-role="customers-p"]`).html(details.map(v => `${v}<br>` ))
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

  $(document).on("click",`[data-bs-target="#editProperties"]`,function(){
    let edit_id = $(this).parents("tr").data("id"),
        parent = $(`#editProperties`);
    parent.find(`[name="person_name"]`).val(content_list[edit_id]["person_name"]);
    parent.find(`[name="person_surname"]`).val(content_list[edit_id]["person_surname"]);
    parent.find(`[name="address"]`).val(content_list[edit_id]["address"]);
    parent.find(`[data-role="edit-properties-button"]`).data("id",edit_id);
    if (supervisors_loaded) {
      parent.find(`[name="supervisor"]`).val(content_list[edit_id]["curator"] ? content_list[edit_id]["curator"]["id"] : 0).change();
    }else{
      $.get({
        url: `/entries/supervisors`,
        headers,
        success: function(d){
          let h = "";
          if (d.code === 200) {
            supervisors_loaded = true;
            $(`#addEntry`).find(`[name="supervisor"]`).prop("disabled",false)
            parent.find(`[name="supervisor"]`).prop("disabled",false)
            d.data.map(v => {
              h += supervisorsComponent(v);
            })
          }
          $(`#addEntry`).find(`[name="supervisor"]`).html(h)
          parent.find(`[name="supervisor"]`).html(h).val(content_list[edit_id]["curator"] ? content_list[edit_id]["curator"]["id"] : 0).change();
        },
        error: function(e){
          console.error(e)
        }
      });
    }
      if (properties_loaded) {
        parent.find(`[name="city"]`).val(content_list[edit_id]["city"] ? content_list[edit_id]["city"]["id"] : 0).change();
        parent.find(`[name="warehouse"]`).val(content_list[edit_id]["warehouse"] ? content_list[edit_id]["warehouse"]["id"] : 0).change();
      }else{
        $.get({
          url: `/entries/properties`,
          headers,
          success: function(d){
            let c_html = `<option value="">${$(`[name="city"]`).data("text")}</option>`;
                w_html = `<option value="">${$(`[name="warehouse"]`).data("text")}</option>`;
            if (d.code === 200) {
              properties_loaded = true;
              $(`#addEntry`).find(`[name="city"]`).prop("disabled",false)
              parent.find(`[name="city"]`).prop("disabled",false)
              $(`#addEntry`).find(`[name="warehouse"]`).prop("disabled",false)
              parent.find(`[name="warehouse"]`).prop("disabled",false)

              d.data.cities.map(v => {
                c_html += supervisorsComponent(v);
              })

              d.data.warehouses.map(v => {
                w_html += supervisorsComponent(v);
              })
            }
            $(`#addEntry`).find(`[name="city"]`).html(c_html)
            $(`#addEntry`).find(`[name="warehouse"]`).html(w_html)
            parent.find(`[name="city"]`).html(c_html).val(content_list[edit_id]["city"] ? content_list[edit_id]["city"]["id"] : 0).change()
            parent.find(`[name="warehouse"]`).html(w_html).val(content_list[edit_id]["warehouse"] ? content_list[edit_id]["warehouse"]["id"] : 0).change();
          },
          error: function(e){
            console.error(e)
          }
        });
      }
  })

  $(document).on("change", `[data-role="is-blocked"]`, function() {
    let $th = $(this),
        id = $th.parents("tr").data("id"),
        is_blocked = $th.prop("checked") ? "1" : "0",
        are_u_sure_edit_is_blocked_message = is_blocked === "1" ? lang("Are you sure block this user") : lang("Are you sure deblock this user");


    Swal.fire({
      title: are_u_sure_edit_is_blocked_message,
      showCancelButton: true,
      confirmButtonText: lang("Yes"),
      cancelButtonText: lang("No"),
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        customLoader();
        $.ajax({
          url: `/entries/${id}/edit-is-blocked`,
          type: "PUT",
          headers,
          data: JSON.stringify({ is_blocked }),
          success: function (d) {
            Swal.fire("", d.message, d.code === 202 ? "success" : "warning");
            listAll();
          },
          error: function (d) {
            console.error(d);
          },
          complete: function () {
            customLoader(true)
          }
        })
      } else {
        $th.prop("checked", !$th.is(":checked"))
      }
    });
  });


  $(document).on("click",`[data-role="edit-properties-button"]`,function(){
    let parent = $(`#editProperties`),
        id = $(this).data("id");
    let data = {
      person_name: parent.find(`[name="person_name"]`).val(),
      person_surname: parent.find(`[name="person_surname"]`).val(),
      address: parent.find(`[name="address"]`).val(),
      supervisor: parent.find(`[name="supervisor"]`).val(),
      city: parent.find(`[name="city"]`).val(),
      warehouse: parent.find(`[name="warehouse"]`).val(),
    }
    customLoader();

    $.ajax({
      type: "put",
      url: `/entries/${id}/edit-properties`,
      data: JSON.stringify(data),
      headers,
      success: function(d){
        if (d.code === 202) {
          parent.modal("hide");
          content_list[id]["person_name"] = data.person_name;
          content_list[id]["person_surname"] = data.person_surname;
          content_list[id]["address"] = data.address
          content_list[id]["curator"]["id"] = data.supervisor;
          content_list[id]["curator"]["name"] = parent.find(`[name="supervisor"] option:selected`).text();
          content_list[id]["city"]["id"] = data.city;
          content_list[id]["city"]["name"] = parent.find(`[name="city"] option:selected`).text();
          content_list[id]["warehouse"]["id"] = data.warehouse;
          content_list[id]["warehouse"]["name"] = parent.find(`[name="warehouse"] option:selected`).text();
          $(`tr[data-id="${id}"]`).find(`[data-role="curator"]`).text(content_list[id]["curator"]["name"]);
          $(`tr[data-id="${id}"]`).find(`[data-role="warehouse"]`).text(content_list[id]["warehouse"]["name"]);
          $(`tr[data-id="${id}"]`).find(`[data-role="city"]`).text(content_list[id]["city"]["name"]);
          $(`tr[data-id="${id}"]`).find(`[data-role="responsible-person"]`).text((content_list[id]["person_name"] || "") + " " + (content_list[id]["person_surname"] || ""));
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

  $(`[name="customer"]`).select2({
    placeholder: lang("Ava customers"),
    minimumInputLength: 2,
    language: {
        noResults: function () {
             return lang("Result not found");
        },
        inputTooShort: function() {
          return lang("Minimum 2 characters required");
        }
      }
  })
});
