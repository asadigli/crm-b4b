import { $ld,redMess,pg_lang,isInValid,notify_once,l,isset,path_local,
					filter_url,$_get,number_format,$db,btn_spinner,getUrlParameter,
						hdKey,environment,paginateController } from './../../parts.min.js?v=1cb';
import {
	Loader
} from './../../loader.min.js?v=2';

$(function () {
		let group = getUrlParameter("group") || $(`select[data-role="product-groups"]`).val(),
				product_list = [],
				selectedProduct = [],
				length_of_pagination = 0,
				keyword = getUrlParameter("keyword") || "",
				brands = getUrlParameter("brands") || "",
				page = getUrlParameter("page") || 1;
		let getBrands = (brand,carbrand) => {
			$("#dtBasicExample_wrapper").addClass("d-none")
			$.get({
				url: `/admin/get-brands`,headers: hdKey,
				success: function(d){
					let brands = `<option value="">- ${l("Choose product brand")} -</option>`;
					d.data.map(v => {
						brands += v.type === "brand" ? `<option value="${v.name}"${v.name === brand ? " selected" : ""}>${v.name}</option>` : "";
					});
					// let carbrands = d.carbrands.map(v => `<option value="${v.name}"${carbrand.split(",").includes(v.name.trim()) ? " selected" : ""}>${v.name}</option>`).join(" ");
					// $(`[data-name="prod_brand"]`).html(brands).attr("disabled",false)
					$(`[data-name="br_keyword"]`).attr("disabled",false)
					// .select2({
					// 	placeholder: l("Choose product brand")
					// });
				},error: function(d){
					console.error(d)
				},complete: function(){
					$("#dtBasicExample_wrapper").removeClass("d-none")
					$(".search-page-filter").removeClass("load")
				}
			});
		}

		const get1CBrands = () => {
			$.get({
				url: `/admin/product/get-1c-brands`,
				headers: hdKey,
				success: function(d){
					let brands = `<option value="">- ${l("Choose product brand")} -</option>` + (d.code === 200 ? d.data.brands.map(v => `<option value=${v}>${v}</option>`).join("") : "");
					$(`[data-name="prod_brand"]`).html(brands).attr("disabled",false)
				},
				complete: function(d){
					// console.log(d)
				}
			})
		}

		get1CBrands();

		getBrands();

		let get_manual_products = (e,keyword,brands,page,first_time) => {
			$(`[data-role="select-all"]`).prop("checked",false)
			$("body").addClass("loader");
			let start_time = new Date().getTime(),
					interval = null,
					$loader = $(`p[data-role="loading-text"]`);
			$loader.removeClass("d-none");
      clearInterval(interval);
      interval = setInterval(function(){$loader.html(`${$loader.data("text")} ${Math.round((new Date().getTime() - start_time)/1000)} san`);},1000);
			// console.log("interval",interval)
			let data = {main_brands:brands,keyword,page};
			// console.log(data)
			$.get({
				url: `/admin/product/list-live`,headers: hdKey,
				data,
				success:function(d){
					let h = ``;
					// console.log(d);
					if (d.code === 200) {
						// $("#mn_product_list").DataTable().destroy()
						product_list = d.data.products;
						product_list.map(v => {
							h += `<tr data-id="${v.id}">
												<td>
													<label class="chck m-0">
														<input type="checkbox" data-role="inline-select">
														<span class="checkmark"></span>
													</label>
												</td>
												<td><input type="text" value="${v.product_name}" data-role="change-prod-name" class="form-control"></td>
												<td>${v.brand || "---"}</td>
												<td>
													<label class="chck m-0">
														<input type="checkbox" data-role="product-home-status-checkbox"${v.home_product ? " checked" : ""}>
														<span class="checkmark"></span>
													</label>
												</td>
												<td>
													<label class="chck m-0">
														<input type="checkbox" data-role="product-status-checkbox"${v.status ? " checked" : ""}>
														<span class="checkmark"></span>
													</label>
												</td>
												<td>
													${v.is_local ? `<a href="javascript:void(0)" class="btn btn-danger" data-role="deleteProduct"><em class="fa fa-trash"></em></a>` : ""}
													<a class="btn btn-danger" href="${path_local(`admin/product/${v.id}/edit`)}"><em class="fa fa-edit"></em></a>
												</td>
											</tr>`;
						});

						length_of_pagination = d.data.count ? Math.ceil(d.data.count / 12) : 0;
						if (length_of_pagination) {
							$("#mn_product_list tbody").html(h).removeClass('load');
						} else {
							$("#mn_product_list tbody").html('');
						}
						if (first_time) {
							// page = 1;
							paginateController(length_of_pagination,null,page)
						}
					}
					// $("#mn_product_list tbody").html(h);
					// $("#mn_product_list").dataTable({bPaginate: false,searching: false,bDestroy: true})
				},complete:function(){
					e ? Loader.btn({element: e,action: "end",time: 0}) : "";
					$("body").removeClass("loader");
					clearInterval(interval);
					$("#load_time").html(`${Math.round((new Date().getTime() - start_time)/1000)} san`);
				}
			});
		}

		let changeName = (name,product,th) => {
			$.ajax({
				url: `/product/name/update`,
				type: "POST",data: {name,product},
				headers: hdKey,
				success: function (d) {
					notify_once(d.message,(d.code === 200 ? "success" : "error"))
					if (d.code === 200 && th) {
						th.addClass(config.ISVALID_CLASS)
						setTimeout(function(){
							th.removeClass(config.ISVALID_CLASS)
						},config.ISVALID_TIMEOUT_TIME)
					}
				},complete: function(){

				}
			})
		}

		let oldProdName = "";
		$db.on("focusin",`[data-role="change-prod-name"]`,function(){
			oldProdName = $(this).val().trim();
		})

		$db.on("focusout",`[data-role="change-prod-name"]`,function(){
			if (oldProdName !== $(this).val().trim()) {
				changeName($(this).val(),$(this).parents("tr").data("id"),$(this))
			}
		});

		$(document).on("change",`[data-role="product-home-status-checkbox"]`,function(){
			let status = $(this).is(":checked") ? "1" : "0",
					url = `/admin/product/home-list/update`;
			let data = {product: $(this).parents("tr").data("id"),status};
			$(`input:not([disabled]),select:not([disabled])`).addClass("auto-disabled-cls").prop("disabled",true);
			$.post({
				url,data,headers: hdKey,
				success: function(d){
					notify_once(d.message,d.code === 200 ? "success" : "warning")
				},error: function(d){
					console.error(d);
				},complete: function(){
					$(`.auto-disabled-cls`).prop("disabled",false).removeClass("auto-disabled-cls");
				}
			})
		});

		$(document).on("change",`[data-role="product-status-checkbox"]`,function(){
			let status = $(this).is(":checked") ? "1" : "0",
					url = `/admin/product/status/update`;
			let data = {product: $(this).parents("tr").data("id"),status};
			$(`input:not([disabled]),select:not([disabled])`).addClass("auto-disabled-cls").prop("disabled",true);
			$.post({
				url,data,headers: hdKey,
				success: function(d){
					notify_once(d.message,d.code === 200 ? "success" : "warning")
				},error: function(d){
					console.error(d);
				},complete: function(){
					$(`.auto-disabled-cls`).prop("disabled",false).removeClass("auto-disabled-cls");
				}
			})
		});

		$db.on("change",`[data-role="select-all"]`,function(){
			let cs = $(this).is(":checked");
			selectedProduct = [];
			$(`[data-role="inline-select"]`).prop('checked', cs);
			for (var i = 0; i < $(`[data-role="inline-select"]`).length; i++) {
				selectedProduct.push({
					product: $(`[data-role="inline-select"]:eq(${i})`).parents("tr").data("id"),
					group_id: $(`[data-name="group-id"]`),
					category_id: $(`[data-name="category-id"]`),
					second_category_id: $(`[data-name="second-category-id"]`)
				})
				// data-name="category-id"
				// data-name="second-category-id"
				// data-name="brand-name"
			}
			selectedProduct = !cs ? [] : selectedProduct;
			let len = selectedProduct.length;
			if (len) {
				$(`[data-role="show-selected-ones"]`).removeClass("d-none").find("strong").html(len);
			}else{
				$(`[data-role="show-selected-ones"]`).addClass("d-none").find("strong").html("0");
			}
			// console.log(selectedProduct)
		})

		let getCategories = (group) => {
			$(`input,select,textarea`).attr("disabled",true).addClass("disabled-manually");
			$.ajax({
				url: `/product/all-categories`,
				type: "GET",data: {group},
				headers: hdKey,
				success: function(d){
					let $pb = $(`[data-name="brand-id"]`),
							$pc = $(`[data-name="category-id"]`),
							$psc = $(`[data-name="second-category-id"]`);
					if (d.code === 200) {
						$pb.html(`<option value=""${!$pb.data("val") || $pb.data("val") === "" ? " selected" : ""}> - ${l("Choose product brand")} - </option>${d.data.brand ? d.data.brand.map(v => `<option value="${v.id}"${$pb.data("val") == v.id ? " selected" : ""}>${v.name}</option>`).join("") : ""}`)
						$pc.html(`<option value=""> - ${l("Choose category")} - </option>${d.data.category ? d.data.category.map(v => `<option value="${v.id}">${v.name}</option>`).join("") : ""}`)
						$psc.html(`<option value=""> - ${l("Choose second category")} - </option>${d.data.second_category ? d.data.second_category.map(v => `<option value="${v.id}">${v.name}</option>`).join("") : ""}`)
					}
				},
				complete: function(){
					$(`.disabled-manually`).attr("disabled",false).removeClass("disabled-manually");
					// console.log("completed")
				}
			});
		}

		$db.on("change",`[data-name="group-id"]`,function(){
			let val = $(this).val()
			if (val) {
				getCategories(val);
			}else{
				$(`[data-name="brand-id"]`).html(`<option value=""> - ${l("Choose product brand")} - </option>`)
				$(`[data-name="category-id"]`).html(`<option value=""> - ${l("Choose category")} - </option>`)
				$(`[data-name="second-category-id"]`).html(`<option value=""> - ${l("Choose second category")} - </option>`)
			}
		})

		$db.on("change",`[data-role="inline-select"]`,function(){
			selectedProduct = [];
			for (var i = 0; i < $(`[data-role="inline-select"]:checked`).length; i++) {
				selectedProduct.push({
					product: $(`[data-role="inline-select"]:checked`).eq(i).parents("tr").data("id"),
					group_id: $(`[data-name="group-id"]`),
					category_id: $(`[data-name="category-id"]`),
					second_category_id: $(`[data-name="second-category-id"]`)
				})
			}

			let len = selectedProduct.length;
			$(`[data-role="select-all"]`).prop("checked",$(`[data-role="inline-select"]`).length === len);

			if (len) {
				$(`[data-role="show-selected-ones"]`).removeClass("d-none").find("strong").html(len);
			}else{
				$(`[data-role="show-selected-ones"]`).addClass("d-none").find("strong").html("0");
			}
			// console.log($(`[data-role="inline-select"]:checked`).length)
		});

		$db.on('keypress',`[data-name="br_keyword"]`,function(e) {
			if(e.which == 13) {
				keyword = $(`[data-name="br_keyword"]`).val();
				brands = $(`[data-name="prod_brand"]`).val();
				filter_url([{keyword},{brands},{page: (page + "")}])
				get_manual_products(null,keyword,brands,page,true);
			}
		});

		if (keyword || brands) {
			get_manual_products(null,keyword,brands,page,true);
		}

		$db.on("click","#filter-products",function(e){
			keyword = $(`[data-name="br_keyword"]`).val();
			brands = $(`[data-name="prod_brand"]`).val();
			// console.log(document.getElementById("filter-products").innerHTML);
			// console.log(e.target);
			Loader.btn({
				element: e.target,
				action: "start",
				time: 0
			});
			filter_url([{keyword},{brands},{page: (page + "")}])
			get_manual_products(e.target,keyword,brands,page,true);
		});

		$db.on("click", `[data-role="pagination"] a:not([disabled])`, function () {
			page = paginateController(length_of_pagination,$(this));
			filter_url([{keyword},{brands},{page: (page + "")}]);
			$("html, body").animate({
					scrollTop: $("body").offset().top - 130
			}, "fast")
			get_manual_products(null,keyword,brands,page);
		});

		$db.on("click",`[data-role="update-product-all"]`,function(e){
			let new_list = [];
			selectedProduct = selectedProduct.map(v => {
					return {
						product: v.product,
						group_id: $(`[data-name="group-id"]`).val() ? parseInt($(`[data-name="group-id"]`).val()) : null,
						category_id: $(`[data-name="category-id"]`).val() ? parseInt($(`[data-name="category-id"]`).val()) : null,
						second_category_id: $(`[data-name="second-category-id"]`).val() ? parseInt($(`[data-name="second-category-id"]`).val()) : null,
					}
			});

			let data = {
				list: selectedProduct
			}
			Loader.btn({
				element: e.target,
				action: "start",
				time: 0
			});
			$.ajax({
				url: `/product/details/update`,
				type: "POST",headers: hdKey,data: data,
				success: function(d) {
					// console.log(d)
					if (d.code === 200) {
						Loader.btn({
							element: e.target,
							action: "success",
						});
						selectedProduct = [];
						$(`[data-role="show-selected-ones"]`).addClass("d-none").find("strong").html("0");
						$(`[data-role="select-all"],[data-role="select-all"]`).prop("checked",false);
					}
					notify_once(d.message,(d.code === 200 ? "success" : "error"))
				},
				error: function(d) {
					console.error(d)
					Loader.btn({
						element: e.target,
						action: "error",
					});
				},complete: function(){
					Loader.btn({
						element: e.target,
						action: "end",
					});
				}
			})
			// console.table(selectedProduct);
		})


		$db.on("click",`[data-role="deleteProduct"]`,function(){
			let t = $(this);
			Swal.fire({
				text: "Məhsulu silməkdə əminsinizmi?",
				showCancelButton: true,
				confirmButtonText: l("Yes"),
				confirmButtonColor: '#d63030',
				cancelButtonText: l("Cancel")
			}).then((res) => {
				if (res.value) {
					$.ajax({
						url: `/admin/product/${t.parents(`tr`).data("id")}/delete`,
						type: "DELETE",
						headers: hdKey,
						success: function (d){
							// console.log(d)
							if(d.code === 200){
								Swal.fire(d.message, '', 'success');
								t.parents(`tr`).remove();
							}
						},error: function(d){
							console.error(d)
						}
					})
				} else {
					// Swal.fire('Changes are not saved', '', 'info')
				}
			})
		});
});
