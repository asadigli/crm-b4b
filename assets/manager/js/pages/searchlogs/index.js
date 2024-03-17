$(function () {
    let customer = getUrlParameter("customer")
    let offset = 0,
        limit = 100,
        loading = false, // false | true | completed
        logs = []

    const setParam = (key, value) => {
        let url = new URL(location.href)
        url.searchParams.set(key, value)
        history.pushState({}, url, url)
    }

    const getCustomers = () => {
        if (getStorage("customers")) {
            let content_data = JSON.parse(getStorage("customers")),
                h = `<option value="">${lang("Choose customer")}</option>`;
            content_data.map(v => {
                h += `<option value="${v.remote_id}"${+customer === +v.remote_id ? " selected" : ""}>${v.name}</option>`;
            });
            $(`[name="customer"]`).html(h);
            return;
        }
        $.get({
            url: `/customers/simple-list-live`,
            headers,
            success: function (d) {
                // console.log(d);
                if (d.code === 200) {
                    let h = `<option value="">${lang("Choose customer")}</option>`;
                    setStorage("customers", JSON.stringify(d.data), 60 * 60 * 12);

                    d.data.map(v => {
                        h += `<option value="${v.remote_id}"${+customer === +v.remote_id ? " selected" : ""}>${v.name}</option>`;
                    });
                    $(`[name="customer"]`).html(h);
                }
            },
            error: function (d) {
                console.error(d);
            },
            // complete: function(){
            //
            // }
        });
    }


    const trComponent = (index, data) => `
        <tr data-index="${index + offset}" >
            <td> ${index + offset + 1} </td> 
            <td> ${data.search_code} </td>
            <td> ${data.search_brand_name || ""} </td>
            <td> ${data.search_marka_name || ""} </td>
            <td> ${data.search_count || ""} </td> 
            <td> ${Number.parseInt(data.result_count) || words["No result"] } </td>
            <td> 
                <button data-bs-toggle="modal" data-bs-target="#searchLogsCustomersModal" type="button" data-role="show-who-searched" class="btn btn-primary ms-2">
                    ${data.customers_count} 
                  </button>
            </td> 
        </tr>
    `;


    const customerTrComponent = (index, data) =>
        `<tr>
            <td> ${++index} </td> 
            <td> ${data.customer_name} </td>
            <td> ${data.count} </td>              
        </tr>`;


    $(document).on("click", `[data-role="show-who-searched"]`, function () {
        let log = logs[$(this).parents("tr").data("index")]

        let data = {
            start_date : $("[name='start_date']").val(),
            end_date : $("[name='end_date']").val() ,
            customer_id : $("[name='customer']").val() ,
            offset,
            search_code: log.search_code,
            search_brand: log.search_brand,
            search_marka: log.search_marka
        }
        $(`#searchLogsCustomersModal tbody`).html("")

        // console.log({data})
        ModalLoader.start(lang("Loading"))
        $.get({
            url: "/search-logs/only-customers",
            headers, data,
            success: function (response) {
                console.log({response})
                if (response.code === 200) {
                   let html = response.data?.map((item,index) => customerTrComponent(index,item))
                    $(`#searchLogsCustomersModal tbody`).html(html)
                }
                ModalLoader.end()
            }
        })
    })

    function getLogs(from_scratch) {

        if (from_scratch) {
            $("#load_more_div").addClass("loading");
            ModalLoader.start(lang("Loading"));
            offset = 0
        }

        let start_date = $("[name='start_date']").val()
        let end_date = $("[name='end_date']").val()
        let customer_id = $("[name='customer']").val()
        let data = {start_date, end_date, customer_id, offset}
        loading = true
        $("#load_more_div").removeClass("d-none");


        $.get({
            url: "/search-logs",
            headers,
            data,
            success: function (response) {
                if (response.code === 204) {
                    $("#load_more_div").removeClass("loading").addClass("d-none")
                    $(`[data-role="logs-table"] tbody`).html("")
                    loading = "completed"
                    return false;
                }
                if (from_scratch) {
                    logs = response.data?.logs
                } else {
                    logs.push(...response.data.logs)
                }
                let html = response.data?.logs.map(function (item, index) {
                    return trComponent(index, item)
                })
                if (from_scratch) {
                    $(`[data-role="logs-table"] tbody`).html(html)
                } else {
                    $(`[data-role="logs-table"] tbody`).append(html)
                }

                offset += response.data.logs.length
                // console.log("line109",offset,response.data.possible_data_count)
                if (offset >= response.data.possible_data_count) {
                    loading = "completed"
                    $("#load_more_div").addClass("d-none");
                } else {
                    loading = false
                    $("#load_more_div").removeClass("loading");
                }
            },
            complete: function () {
                ModalLoader.end()
            }
        })
    }


    $(document).on("click", `button[data-role="search"]`, function () {
        getLogs(true)
    })

    $(document).on("keyup", `[type="date"]`, function (e) {
        if (e.keyCode === 13) {
            getLogs(true)
        }
    })

    $(document).on("change", `[type="date"]`, function (e) {
        setParam(e.target.name, e.target.value)
    })
    $(document).on("change", `[name="customer"]`, function (e) {
        setParam("customer", e.target.value)
        getLogs(true)
    })

    $(document).on("scroll", function () {
        if (!$("#load_more_div").isInViewport() || loading === true || loading === "completed") return false;
        $("#load_more_div").addClass("loading");
        getLogs(false)
    });

    $(document).on("click", `[data-role="load-more"]`, function () {
        // if (loading_completed || loading) return false;
        // $("#load_more_div").addClass("loading");
        // offset = $(`[data-role="table-list"] > tr`).length;
        // loading = true;
        // getAccount({start_date,end_date,brand_code,brand,oem_code,is_excel_export},true);
    });


    // don't change order of functions
    getCustomers()
    getLogs(true)


})