import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey,isInValid,getBase64,
	setCookie,getCookie,eraseCookie
} from './../../parts.min.js?v=2342abc';
import {
	Loader
} from './../../loader.min.js?v=sdf12';

$(function () {
		let insertDataImages = [],exist_images = [];

		if (getCookie("notify_once_msg")) {
			notify_once(getCookie("notify_once_msg"),getCookie("notify_once_msg_code"))
			eraseCookie("notify_once_msg");
			eraseCookie("notify_once_msg_code");
		}


		$(document).on("click",`[data-role="delete-exist-image"]`,function(){
			let parent = $(this).parents(".exist-image-items");
			$(this).parents(".exist-image-item").remove();
			parent.find(".exist-image-item").each(v => {
				exist_images.push(parent.find(".exist-image-item").eq(v).find("img").data("src"));
			})
		});

		let product = $(`[data-role="product-edit-container"]`).data("prod-id"),
				isChanged = {
					name: false,
					description: false,
					short_description: false,
				};




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
		});

		if ($(`[data-name="product-group"]`).val()) {
			getCategories($(`[data-name="product-group"]`).val());
		}


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
			$(`[data-role="edit-product"]`).prop("disabled",true);
			if (input.files && input.files.length) {
				for (let i = 0; i < input.files.length; i++) {
					getBase64(input.files[i]).then(
						data => {
							insertDataImages.push(data);
							previewFunction(insertDataImages);
						}
					).finally(v => {
						$(`[data-role="edit-product"]`).prop("disabled",false);
					});
				}
			}
		});

		$db.on("click",`[data-role="edit-product"]`,function(e){

			exist_images.length = 0;
			let parent = $(".exist-image-items");
			parent.find(".exist-image-item").each(v => {
				exist_images.push(parent.find(".exist-image-item").eq(v).find("img").data("src"))
			})

			let th = e.target,$pd = $(`[data-name="product-name"]`);
			let data = {
				product: $(`[data-name="product-id"]`).length ? $(`[data-name="product-id"]`).val() : null,
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
				images: insertDataImages,
				exist_images
			}
			//
			// console.log(data);
			// return;

			if (!data.name) {
				$pd.siblings("small.text-danger").removeClass("d-none").html($pd.data("error"))
				$('html, body').animate({
            scrollTop: $pd.offset().top - 20
        }, 0);
				return;
			}
			$pd.siblings("small.text-danger").addClass("d-none").html("")

			$(`input,select,textarea`).attr("disabled",true).addClass("disabled-manually");
			Loader.btn({
				element: th,
				action: "start",
			});
			$.post({
				url: `/admin/product/update`,data,headers: hdKey,
				success: function (d){
					console.log(d)
					if (d.code === 200) {
						Loader.btn({
							element: th,
							action: "success",
						});
						location.reload();
						$("html, body").animate({scrollTop: 0}, 1000);
						insertDataImages.length = 0;
						previewFunction(insertDataImages);
						setCookie("notify_once_msg",d.message);
						setCookie("notify_once_msg_code","success");
					}else{
						Loader.btn({
							element: th,
							action: "error",
						});
					}
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


});
