import { $ld,redMess,pg_lang,isInValid,notify_once,l,isset,path_local,
					filter_url,$_get,number_format,$db,btn_spinner,getUrlParameter,
						hdKey as headers,environment } from './../../../parts.min.js?v=1ab';
import {
	Loader
} from './../../../loader.min.js?v=2';

$(function() {

	let config = {
		ISVALID_TIMEOUT_TIME: 4000,
		ISVALID_CLASS: "is-valid",
		INVALID_CLASS: "is-invalid"
	}

	let catType = $(`[name="catType"]:checked`).val(),
			group = $(`[name="productGroup"]`).val(),
			catlist = [],catvaldefault = '',
			brands = [],categories = [],
			subcategories = [],catlistSub = [];

	let cat_tr = (id,name) => {
			return (`<tr data-id="${id}">
						<td>${id}</td>
						<td><input type="text" value="${name}" data-role="category-name" class="form-control"></td>
						<td style="width:140px;">
							<a href="javascript:void(0)" class="btn btn-danger" data-role="delete-category"><em class="fa fa-times"></em></a>
						</td>
					</tr>`);
					// <a href="javascript:void(0)" class="btn btn-primary"><em class="fa fa-save"></em></a>
		}


		$db.on("click",`[data-role="add-new-category"]`,function(e){
			let t = $(this);
			let val = $(`input[name="new_category_name"]`).val(),
					target_type = $("#category_type").val();
			Loader.btn({
				element: e.target,
				action: "start"
			})
			let data = {status:"1",group,name:val,type:target_type};
			// console.log(data);
			$.post({
				url: `${environment}admin/product/category/add`,
				headers,data,
				success:function(d){
					// console.log(d);
					if (d.code === 200) {
						// $('#categoryModel').modal('toggle');
						$("#categoryModel .close").click();

						$(`[name="new_category_name"]`).val("");
						notify_once(d.message,'success');
						// console.log("target_type",target_type)
						// if (target_type === 'brand') {
						// 	let h = '<option value="" selected>-- Brend seç --</option>';
						// 	brands.push({id:d.data.id,name:data.name});
						// 	brands.map(v => {
						// 		h += `<option value="${v.id}">${v.name}</option>`;
						// 	});
						// 	// $brSelect.html(h);
						// }else if(target_type === 'category'){
						// 	let h = '<option value="" selected>-- Kateqoriya seç --</option>';
						// 	categories.push({id:d.data.id,name:data.name})
						// 	categories.map(v => {
						// 		h += `<option value="${v.id}">${v.name}</option>`;
						// 	});
						// 	// $ctSelect.html(h);
						// }else if(target_type === 'subcat'){
						// 	let h = '<option value="" selected>-- Alt kateqoriya seç -- </option>';
						// 	subcategories.push({id:d.data.id,name:data.name})
						// 	subcategories.map(v => {
						// 		h += `<option value="${v.id}">${v.name}</option>`;
						// 	});
						// 	// $subCtSelect.html(h);
						// }
						getCatlist(group,catType);

						// h += v.group_id === group && v.type === type ? cat_tr(v.id,v.name) : "";

						Loader.btn({
							element: e.target,
							action: "success"
						});
						getProdCats();
					}
				},error: function(d){
					Loader.btn({
						element: e.target,
						action: "error"
					})
				},complete:function(){
					Loader.btn({
						element: e.target,
						action: "end"
					})
				}
			});
		});

		let getProdCats = () => {
			$("body").addClass("loader");
			$.get({
				url: `${environment}admin/product/category`,
				headers,
				success:function(d){
					d.code === 200 ? catlistSub = d.data : "";
				},complete:function(){
					$("body").removeClass("loader");
					$(`select[name="productGroup"]`).removeAttr("disabled")
				}
			})
		}

		$db.on("click",`[data-target="#categoryModel"]`,function(){
			$("#category_type").val($(this).data("type"));
			$("#categoryTitle").html($(this).html())
		})

		let getCatlist = (group,type) => {
			// $("#catlisttable").siblings(".loading").removeClass("d-none");
			$("body").addClass("loader");
			$.get({
				url: `${environment}admin/product/category`,
				headers,
				success:function(d){
					let h = '';
					// console.log(d)
					if (d.code === 200) {
						catlist = d.data;
						// catlist.map(v => {
						// 	h += v.group_id === group && v.type === type ? cat_tr(v.id,v.name) : "";
						// });
						catlist.map(v => {
							if (+v.group_id === +group && v.type === 'brand') {
								brands.push({id:v.id,name:v.name});
								// h_brand += `<option value="${v.id}">${v.name}</option>`;
							}else if (+v.group_id === +group && v.type === 'category') {
								categories.push({id:v.id,name:v.name})
								// h_category += `<option value="${v.id}">${v.name}</option>`;
							}else if (+v.group_id === +group && v.type === 'subcat') {
								subcategories.push({id:v.id,name:v.name})
								// h_subcat += `<option value="${v.id}">${v.name}</option>`;
							}
							h += v.group_id === group && v.type === type ? cat_tr(v.id,v.name) : "";
						});
						$("#catlisttable tbody").html(h);
					}
				},complete:function(){
					$("body").removeClass("loader");
					$(`select[name="productGroup"]`).removeAttr("disabled")
				}
			})
		}

		getCatlist(group,catType);

		$db.on('focusin',`[data-role="category-name"]`,function(){
			catvaldefault = $(this).val();
		});
		$db.on('focusout',`[data-role="category-name"]`,function(){
			let t = $(this);
			let name = t.val(),id = t.parents("tr").data("id");
			if (t.val().trim() !== catvaldefault.trim()) {
				$.post({
					url: `${environment}admin/product/category/update`,
					headers,
					data:{id,name},
					success:function(d){
						// console.log(d);
						if (d.code === 200) {
							t.addClass(config.ISVALID_CLASS);
							setTimeout(function(){
								t.removeClass(config.ISVALID_CLASS);
							},config.ISVALID_TIMEOUT_TIME);
						}
						notify_once(d.message,(d.code === 200 ? "success" : "error"))
					},error:function(d){
						console.error(d);
					},complete:function(){

					}
				});
			}
		});

		// $db.on("click",`[data-role="delete-category"]`,function(){
		// 	let t = $(this);
		// 	let id = t.parents("tr").data("id"),
		// 			cnfm = confirm("Kateqoriyanı silməkdə əminsinizmi?"),
		// 			txt = t.html();
		// 	t.html(btn_spinner);
		// 	if (cnfm == true) {
		//
		// 	} else {
		// 	  console.log("You canceled");
		// 	}
		//
		// })

		$db.on("click",`[data-role="delete-category"]`,function(){
			let t = $(this);
			let id = t.parents("tr").data("id")
			Swal.fire({
				text: "Kateqoriyanı silməkdə əminsinizmi?",
				showCancelButton: true,
				confirmButtonText: l("Yes"),
				confirmButtonColor: '#d63030',
				cancelButtonText: l("Cancel")
			}).then((res) => {
				if (res.value) {
					$.ajax({
						url: `/admin/product/category/${id}/delete`,
						type: "DELETE",
						headers,
						success:function(d){
							// console.log(d);
							if(d.code === 200){
								Swal.fire(d.message, '', 'success');
								t.parents("tr").remove();
							}
						},
					});

				} else {
					// Swal.fire('Changes are not saved', '', 'info')
				}
			})
		})

		$db.on("change",`[name="productGroup"]`,function(){
			let h= '';
			group = $(this).val();
			filter_url([
				{group},
				{type: catType}
			]);
			catlist.map((v, i) => {
				h += +v.group_id === +group && v.type === catType ? cat_tr(v.id,v.name) : "";
			});
			$("#catlisttable tbody").html(h);
		});
		$db.on("change",`[name="catType"]`,function(){
			let h= '';
			catType = $(this).val();
			$(`[data-type="${catType}"]`).removeClass("d-none").siblings(`[data-type]`).addClass("d-none");
			filter_url([
				{group},
				{type: catType}
			]);
			catlist.forEach(v => {
				h += v.group_id === group && v.type === catType ? cat_tr(v.id,v.name) : "";
			});
			$("#catlisttable tbody").html(h);
		});

		$(`[data-type="${catType}"]`).removeClass("d-none").siblings(`[data-type]`).addClass("d-none");


});
