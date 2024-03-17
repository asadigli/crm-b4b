import {
	l,filter_url,$db,hdKey,path_local,notify_once
} from './../../parts.min.js?v=1.0.1';
import {
	Loader
} from './../../loader.min.js?v=2';

$(function () {


	let promotionComponent = (id,title,images,slug,date,status) => {
		let img = images && images[0] ? images[0].small : "/assets/landing/img/no_photo.png";
		return (`<tr data-id="${id}">
							<td>${id}</td>
							<td><img src=${img} style="width:70px" loading="lazy"></td>
							<td>${title}</td>
							<td>${date}</td>
							<td><input type="checkbox"${status ? " checked" : ""} data-role="change-promotion-status"></td>
							<td>
								<div class="btn-group">
									<a class="btn btn-primary" href="${path_local(`admin/promotion/${id}/edit`)}"><em class="fa fa-edit"></em></a>
									<a class="btn btn-danger" data-role="deleteNewItem" href="javascript:void(0)"><em class="fa fa-trash"></em></a>
								</div>
							</td>
						</tr>`);
	}

	let getPromotionList = () => {
		$("body").addClass("loader");
		$.ajax({
			url: `/promotion/list-live`,
			type: 'GET',headers: hdKey,
			data: {version: "full",limit: 1000000},
			success: function(d){
				let h = d.code === 200 ? d.data.list.map(v => promotionComponent(v.id,v.title,v.images,v.slug,v.date,v.status)).join(" ") : "";
				$("#admin_promotion_list").html(h).removeClass("load");
			},error: function(d){
				console.error(d);
			},complete: function(){
				$("body").removeClass("loader");
			}
		});
	}

	$(`#admin_promotion_list`).length ? getPromotionList() : "";

	$db.on("click",`[data-role="deleteNewItem"]`,function(){
			let t = $(this);
			Swal.fire({
				text: l("Are you sure to delete promotion"),
				showCancelButton: true,
				confirmButtonText: l("Yes"),
				confirmButtonColor: '#d63030',
				cancelButtonText: l("Cancel")
			}).then((res) => {
				if (res.value) {
					$.ajax({
						url: `/admin/promotion/${t.parents(`tr`).data("id")}/delete`,
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
		})


		$(document).on("change",`[data-role="change-promotion-status"]`,function(){
			$(`input:not([disabled]),select:not([disabled])`).addClass("auto-disabled-cls").prop("disabled",true);
			let data = {status: $(this).is(":checked") ? "1" : "0"};
			$.post({
				url: `/admin/promotion/${$(this).parents("tr").data("id")}/change-status`,
				headers: hdKey,data,
				success: function (d){
					console.log(d);
					notify_once(d.message,d.code === 200 ? "success" : "warning");
				},error: function(d){
					console.error(d);
				},complete: function(){
					$(`.auto-disabled-cls`).prop("disabled",false).removeClass("auto-disabled-cls");
				}
			})
		})


});
