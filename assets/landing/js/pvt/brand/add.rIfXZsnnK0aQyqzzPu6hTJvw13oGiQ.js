import {
	notify_once,l,filter_url,btn_spinner,hdKey
} from './../../parts.min.js?v=1bc';
import {
	Screen
} from './../../current_screen.min.js?v=2';

import {
	Loader
} from './../../loader.min.js?v=2';

$(function () {

	let brandsLoaded = false;

	let popBtn = $('.popBtn')

	$(document).on('click', '.popBtn', function (e) {
		let data = $(this).data('id')
		let targetPopup = $(`.popUpStyle`);
		e.preventDefault();
		$(`[data-name="hidden-brand-id"]`).val($(this).data("id"))
		$(targetPopup).toggleClass('active');
		$('body').toggleClass('overflow-hidden');
	})

	$(document).on('click', '.popUpStyle .close-icon', function () {
		$('.popUpStyle').removeClass('active');
		$('body').removeClass('overflow-hidden');
	});

	let getBrandList = () => {
		if(brandsLoaded) return;
		brandsLoaded = true;
		$.get({
			url: `/brand/list-live`,
			data: {version: "full"},headers: hdKey,
			success: function(d){
				let h = d.code === 200 ?
										d.data.map((v,i) => `<tr data-id=${v.id}>
																					<td>${i + 1}</td>
																					<td><img src="${v.image && v.image.small ? v.image.small : "/assets/landing/img/no_photo.png"}" style="width:85px"></td>
																					<td>${v.name || "---"}</td>
																					<td>${v.description || "---"}</td>
																					<td>
																						<a href="/admin/brand/${v.id}/edit">
																							<em class="fa fa-edit"></em>
																						</a>
																						<a href="javascript:void(0)" data-role="deleteTag">
																							<em class="fa fa-trash"></em>
																						</a>
																					</td>
																				 </tr>`).join(" ") : "";
				$("#brand_container_v2").html(h).removeClass("load");
			},error: function(d){
				console.error(d);
				brandsLoaded = false;
			}
		});
	}

	if ($("#brand_container_v2").length) {
		 Screen.view($('#brand_container_v2'), false) ? getBrandList() : "";
		$(window).scroll(function () {
				Screen.view($('#brand_container_v2'), false) ? getBrandList() : ""
		});
	}

	$(document).on("click",`[data-role="deleteTag"]`,function(){
		let t = $(this);
		let data = {
			brand: t.parents("tr").data("id")
		}
		Swal.fire({
		  text: l("Are you sure to delete brand"),
			showCancelButton: true,
			confirmButtonText: l("Yes"),
			confirmButtonColor: '#d63030',
			cancelButtonText: l("Cancel")
		}).then((res) => {
		  if (res.value) {
				$.post({
					url: `/brand/delete`,data,headers: hdKey,
					success: function (d){
						// console.log(d)
						if(d.code === 200){
							Swal.fire('Brend silindi', '', 'success');
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

	$(document).on("click",`[data-role="editTag"]`,function(){

	});

	$(document).on("click",`[data-role="brand-image-remove"]`,function(){
		$(this).parent().addClass("d-none").find("img").attr("src","");
	});

	let brandDescValue;
	if ($(`[data-role="add-new-brand"]`).length || $(`[data-role="update-brand"]`).length) {
		ClassicEditor
		.create( document.querySelector('#editor'))
		.then(editor => {
			brandDescValue = editor;
		})
		.catch(error => {
			console.error(error);
		});
	}

	let flup = new Flup({selector:"#file_uploader",limit:1});

	if ($(`[data-role="add-new-brand"]`).length) {
		let insertData = [];

			$(document).on("click",`[data-role="add-new-brand"]`,function(e){
				insertData["name"] = $(`[data-name="brand-name"]`).val();
				insertData["image"] = $(`[name="file_uploader"]`).val(),
				insertData["description"] = brandDescValue.getData();
				console.log(insertData);
				if(!insertData.name || !insertData.image) {
					$(`[data-name="brand-name"]`).addClass("is-invalid");
					return;
				}else{
					$(`[data-name="brand-name"]`).removeClass("is-invalid");
				}
				Loader.btn({
					element: e.target,
					action: "start"
				})
				$.post({
					url: `/admin/brand/add-new`,
					data: {name:insertData.name,description:insertData.description,image: insertData.image},headers: hdKey,
					success: function(d){
						if (d.code === 201) {
							Loader.btn({
								element: e.target,
								action: "success",
							});
							$(`[data-name="brand-name"]`).val("")
							brandDescValue.setData('');
							$(`[name="file_uploader"]`).val("");
           		$(`[name="file_uploader"]`).next(".imgs-line").addClass("d-none").empty();
							// $(`[data-role="brand-image-preview"]`).attr('src', '').parent().addClass("d-none");
						}
						notify_once(d.message,d.code === 201 ? "success" : "warning")
					},error: function(d){
						console.error(d)
						Loader.btn({
							element: e.target,
							action: "error",
						})
					},complete: function(){
						Loader.btn({
							element: e.target,
							action: "end",
						})
					}
				})
			})
	}
});
