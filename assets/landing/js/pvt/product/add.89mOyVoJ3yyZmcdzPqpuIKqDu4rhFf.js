import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey,isInValid,getBase64
} from './../../parts.min.js?v=asdv1';
import {
	Loader
} from './../../loader.min.js?v=sdf12';

$(function () {
		let storeImageUpload = [],
				storeImageUploadName = [],
				store_id = $(`#storeImage`).data("store");

		let product = $(`[data-role="product-edit-container"]`).data("prod-id"),
				isChanged = {
					name: false,
					description: false,
					short_description: false,
				};


		// async function logFetch(url) {
		// 	let new_list = [];
		// 	for (let i = 0; i < 10000000; i++) {
		// 		new_list.push(i + "yes test");
		// 	}
		// 	return await fetch("https://jsonplaceholder.typicode.com/posts")
		//     .then(response => response.text())
		//     .then(text => {
		// 			return new_list;
		// 			return JSON.parse(text);
		//     }).catch(err => {
		//       return err;
		//     });
		// }
		//
		// setTimeout(function(){
		// 	console.log("started")
		// 	let start_time = new Date().getTime();
		// 	logFetch().then(v => {
		// 		console.log((new TextEncoder().encode(v.join(""))).length)
		// 	}).finally(v => {
		// 		var request_time = new Date().getTime() - start_time;
		// 		console.log("ended",request_time/1000)
		// 	});
		// },3000)

		let getCategories = (group) => {
			$(`input:not(:disabled),select:not(:disabled),textarea:not(:disabled)`).attr("disabled",true).addClass("disabled-manually");
			$.ajax({
				url: `/product/all-categories`,
				type: "GET",data: {group},
				headers: hdKey,
				success: function(d){
					let $pb = $(`[data-name="product-brand"]`),
							$pc = $(`[data-name="product-category"]`),
							$psc = $(`[data-name="product-second-category"]`);
					if (d.code === 200) {
						$pb.html(`<option value=""${!$pb.data("val") || $pb.data("val") === "" ? " selected" : ""}> - ${l("Choose product brand")} - </option>${d.data.brand ? d.data.brand.map(v => `<option value="${v.id}"${$pb.data("val") == v.id ? " selected" : ""}>${v.name}</option>`).join("") : ""}`)
									.attr("disabled",(group ? false : true));
						$pc.html(`<option value=""${!$pc.data("val") || $pc.data("val") === "" ? " selected" : ""}> - ${l("Choose category")} - </option>${d.data.category ? d.data.category.map(v => `<option value="${v.id}"${$pc.data("val") == v.id ? " selected" : ""}>${v.name}</option>`).join("") : ""}`)
									.attr("disabled",(group ? false : true));
						$psc.html(`<option value=""${!$psc.data("val") || $psc.data("val") === "" ? " selected" : ""}> - ${l("Choose second category")} - </option>${d.data.second_category ? d.data.second_category.map(v => `<option value="${v.id}"${$psc.data("val") == v.id ? " selected" : ""}>${v.name}</option>`).join("") : ""}`)
									.attr("disabled",(group ? false : true));
					}
				},
				complete: function(){
					$(`.disabled-manually`).attr("disabled",false).removeClass("disabled-manually");
					// console.log("completed")
				}
			});
		}

		$db.on("change",`[data-name="product-group"]`,function(){
			let val = $(this).val()
			if (val) {
				getCategories(val);
			}else{
				$(`[data-name="product-brand"]`).html(`<option value=""> - ${l("Choose product brand")} - </option>`).attr("disabled",true);
				$(`[data-name="product-category"]`).html(`<option value=""> - ${l("Choose category")} - </option>`).attr("disabled",true);
				$(`[data-name="product-second-category"]`).html(`<option value=""> - ${l("Choose second category")} - </option>`).attr("disabled",true);
			}
		})

		if ($(`[data-name="product-group"]`).val()) {
			getCategories($(`[data-name="product-group"]`).val());
		}

		let insertDataImages = [];

		let previewFunction = (images) => {
			let imgs = images.map(v => `<div class="line pvIMG"><span><em class="fa fa-times"></em></span><img src="${v}"></div>`).join("");
			if (imgs) {
				$(`[data-role="product-preview-container"]`).removeClass("d-none").html(imgs);
			}else{
				$(`[data-role="product-preview-container"]`).addClass("d-none").html("");
			}
		}

		$db.on("click",".pvIMG em",function(){
			let value = $(this).parent().siblings("img").attr("src");
			insertDataImages = insertDataImages.filter(function(item) {
				return item !== value
			})
			previewFunction(insertDataImages);
		})

		$db.on(`change`,`[data-role="product-image-container"]`,function(e) {
			let input = e.target;
			$(`[data-role="add-product"]`).prop("disabled",true);
			if (input.files && input.files.length) {
				for (let i = 0; i < input.files.length; i++) {
					getBase64(input.files[i]).then(
						data => {
							insertDataImages.push(data);
							previewFunction(insertDataImages);
						}
					).finally(v => {
						$(`[data-role="add-product"]`).prop("disabled",false);
					});
				}
			}
		});

		$db.on("click",`[data-role="add-product"]`,function(e){
			let th = e.target,$pd = $(`[data-name="product-name"]`);
			let data = {
				// product: $(`[data-name="product-id"]`).length ? $(`[data-name="product-id"]`).val() : null,
				name: $(`[data-name="product-name"]`).val(),
				brand_code: $(`[data-name="brand-code"]`).val(),
				oem: $(`[data-name="product-oem"]`).val(),
				status: $(`[data-name="product-status"]`).is(":checked") ? "1" : "0",
				group_id: $(`[data-name="product-group"]`).val(),
				category_id: $(`[data-name="product-category"]`).val(),
				second_category_id: $(`[data-name="product-second-category"]`).val(),
				description: $(`[data-name="product-description"]`).val(),
				short_description: $(`[data-name="product-short-description"]`).val(),
				quantity: $(`[data-name="product-quantity"]`).val(),
				price: $(`[data-name="product-price"]`).val(),
				brand: $(`[data-name="product-brand"]`).val(),
				carbrand: $(`[data-name="product-carbrand"]`).val(),
				home_product: $(`[data-name="is-home-product"]`).is(":checked") ? "1" : "0",
				images: insertDataImages
			}

			console.log(data);
			// return;

			if (!data.name) {
				$pd.siblings("small.text-danger").removeClass("d-none").html($pd.data("error"))
				$('html, body').animate({
            scrollTop: $pd.offset().top - 20
        }, 0);
				return;
			}else{
				$pd.siblings("small.text-danger").addClass("d-none").html("")
			}

			$(`input,select,textarea`).attr("disabled",true).addClass("disabled-manually");
			Loader.btn({
				element: th,
				action: "start",
			});
			$.post({
				url: `/admin/product/add-action`,
				data,
				headers: hdKey,
				success: function (d){
					console.log(d)
					if (d.code === 201) {
						Loader.btn({
							element: th,
							action: "success",
						});
						if (d.code === 201) {
							// location.href = `/product/edit-manual/${(d.data.id + 2342342342) * 123123}`
							$(`[data-name="product-name"],[data-name="brand-code"],[data-name="product-oem"],[data-name="product-short-description"],
									[data-name="product-group"],[data-name="product-category"],[data-name="product-second-category"],[data-name="product-description"],
										[data-name="product-quantity"],[data-name="product-price"],[data-name="product-brand"],[data-name="product-carbrand"]`).val("")
						}

						$("html, body").animate({scrollTop: 0}, 1000);
						insertDataImages.length = 0;
						previewFunction(insertDataImages)
					}else{
						Loader.btn({
							element: th,
							action: "error",
						});
					}
					notify_once(d.message,d.code === 201 ? "success" : "error")
				},
				error: function (d){
					console.error(d)
					Loader.btn({
						element: th,
						action: "error",
					});
				},
				complete: function (){
					// console.log(d)
					$(`.disabled-manually`).attr("disabled",false).removeClass("disabled-manually");
					Loader.btn({
						element: th,
						action: "end",
					});
				}
			})


		});





		// Swal.fire({
		// 	title: 'Məhsul düzəliş səhifəsinə yönləndirlirsiniz ',
		//   // text: '<p class="count-back">0.05</p>',
		//   showCancelButton: true,
		//   confirmButtonText: `Keç`,
		//   cancelButtonText: `Burda qal`,
		// }).then((result) => {
		//   if (result.isConfirmed) {
		//     Swal.fire('Saved!', '', 'success')
		//   } else if (result.isDenied) {
		//     Swal.fire('Changes are not saved', '', 'info')
		//   }
		// })
		//
		// $("#storeImageUpload").change(function () {
		// 	let cnt = $(this)[0].files.length,
		// 		dvPreview = $("#prodImgPreview");
		// 	if (typeof (FileReader) != "undefined") {
		// 		let regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
		// 		let extensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
		// 		$($(this)[0].files).each(function () {
		// 			let file = $(this);
		// 			if (extensions.includes(file[0].name.split('.').pop())) {
		// 				storeImageUpload.push(file[0]);
		// 				storeImageUploadName.push(file[0].name);
		// 				cnt ? dvPreview.css({
		// 					'background-image': 'none'
		// 				}) : dvPreview.attr("style") ? dvPreview.removeAttr("style") : "";
		// 				let reader = new FileReader();
		// 				reader.onload = function (e) {
		// 					let img = $("<img />");
		// 					let div = $("<div></div>");
		// 					let times = $("<em class='fa fa-times'></em>");
		// 					img.attr("data-img-realname", file[0].name);
		// 					img.attr("style", "width: 100px;object-fit:contain;");
		// 					div.attr("style", "position: relative;height:100px;width: 100px;margin: 9px;display:inline-flex");
		// 					times.attr("style", "position: absolute;right: 5px;top: 5px;color: #ffffff;cursor:pointer");
		// 					times.addClass("removeProdImg");
		// 					img.attr("src", e.target.result);
		// 					div.append(img);
		// 					div.append(times);
		// 					dvPreview.append(div);
		// 					$("#storeImageUpload").files = storeImageUpload;
		// 				}
		// 				reader.readAsDataURL(file[0]);
		// 			} else {
		// 				alert(file[0].name + " is not a valid image file.");
		// 				// dvPreview.html("");
		// 				return false;
		// 			}
		// 		});
		// 	} else {
		// 		alert("This browser does not support HTML5 FileReader.");
		// 	}
		// });
		// $("#prodImgPreview").on("click", function (e) {
		// 	if (e.target.classList.contains('removeProdImg') || e.target.tagName.toLowerCase() == 'img') {
		// 		return;
		// 	}
		// 	$("#storeImageUpload").click()
		// })
		//
		//
		// $db.on("click", ".removeProdImg", function () {
		// 	let dvPreview = $("#prodImgPreview");
		// 	let this_img = $(this).siblings("img").data("img-realname");
		// 	storeImageUploadName.splice(storeImageUploadName.indexOf(this_img), 1);
		// 	storeImageUpload.forEach((v, i) => {
		// 		if (v.name === this_img) {
		// 			storeImageUpload.splice(storeImageUpload.indexOf(i), 1);
		// 		}
		// 	});
		// 	$(this).parent().closest('div').remove();
		// 	if (storeImageUpload.length) {
		// 		dvPreview.css({
		// 			'background-image': 'none'
		// 		})
		// 	} else {
		// 		dvPreview.attr("style") ? dvPreview.removeAttr("style") : "";
		// 	}
		// });
		//
		//
		//
		// $db.on("click", "#prodImgUploadBtn button", function () {
		// 	let t = $(this);
		// 	let icon = t.find("em");
		// 	let txt_class = icon.attr("class");
		// 	icon.attr("class", "fa fa-circle-notch fa-spin");
		// 	let form_data = new FormData();
		//
		// 	let totalfiles = storeImageUpload.length;
		// 	for (let i_img_loop = 0; i_img_loop < totalfiles; i_img_loop++) {
		// 		form_data.append("store_photos[]", storeImageUpload[i_img_loop]);
		// 	}
		// 	form_data.append("store_id", store_id);
		// 	$.ajax({
		// 		url: `${environment}store/upload-photo`,
		// 		type: 'POST',headers: hdKey,
		// 		data: form_data,
		// 		dataType: 'json',
		// 		contentType: false,
		// 		processData: false,
		// 		success: function (d) {
		// 			// console.log(d);
		// 			if (d.code == 200) {
		// 				storeImageUpload = [];
		// 				$("#prodImgPreview").html("");
		// 				$("#storeImageUpload").val("");
		// 				$("#prodImgPreview").attr("style") ? $("#prodImgPreview").removeAttr("style") : "";
		// 				let new_photos = '';
		// 				d.images.forEach((v_img, i) => {
		// 					new_photos += `<div class="col-2">
		// 													<div class="store-image first">
		// 														<a href="javascript:void(0)" data-big-img="/assets/v1/uploads/store-photos/big/${v_img}"
		// 																	data-toggle="modal" data-target="#viewImage">
		// 															<img src="/assets/v1/uploads/store-photos/${v_img}" alt="...">
		// 														</a>
		// 														<a class="deleteStoreImage" href="javascript:void(0)" data-key="${v_img}" data-img="${v_img}"><em class="fa fa-times" ></em></a>
		// 													</div>
		// 												</div>`;
		// 				});
		// 				$("#storePhotoList").prepend(new_photos)
		// 			}
		// 		},error: function (d) {console.log(d);
		// 		},complete: function () {icon.attr("class", txt_class);}
		// 	});
		// });


	// var script = document.createElement('script');
	// script.src = '/assets/v1/js/libs/bootbox.min.js?v=5b9d5f6360bacd88d70c6390cd2f39e0';
	// document.body.appendChild(script);
	// console.log($(`[data-role="page-js"]`).attr("src"))
	// $(`head *`).each(function() {
	// 	// [data-role="page-js"]
	//   $.each(this.attributes, function() {
	//     if(this.specified) {
	//       console.log(this.name, this.value);
	//     }
	//   });
	// 	console.log("- - - - - - - - - - - - - - -");
	// });

});
