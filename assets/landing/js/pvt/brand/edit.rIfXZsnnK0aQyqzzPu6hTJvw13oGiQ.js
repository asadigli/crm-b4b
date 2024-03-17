import {
	notify_once,l,filter_url,hdKey
} from './../../parts.min.js?v=1bc';

import {
	Loader
} from './../../loader.min.js?v=2';

$(function () {
	let brandDescValue;

	$(document).on("click",`[data-role="brand-image-remove"]`,function(){
		$(this).parent().addClass("d-none").find("img").attr("src","");
		$(`[data-role="brand-image-uploader"]`).removeClass("d-none");
		data["image"] = null;
	});

	ClassicEditor.create( document.querySelector('#editor'))
	.then(editor => {
		brandDescValue = editor;
	})
	.catch(error => {
		console.error(error);
	});

	let data = [];

	$(document).on("click",`[data-role="delete-exist-image"]`,function(){
		let flup = new Flup({selector:"#file_uploader",limit:1});
		let parent = $(this).parents(".exist-image-items");
		$(this).parents(".exist-image-item").remove();
		parent.find(".exist-image-item").each(v => {
			exist_images.push(parent.find(".exist-image-item").eq(v).find("img").data("src"));
		})
	});

	$(document).on(`change`,`[data-role="brand-image"]`,function(e) {
		let input = e.target;
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(`[data-role="brand-preview-img"]`).removeClass("d-none").find("img").attr('src', e.target.result);
				$(`[data-role="brand-image-uploader"]`).addClass("d-none")
				data["image"] = e.target.result;
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			$(`[data-role="brand-preview-img"] img`).attr('src', '').parent().addClass("d-none");
		}
	});

	$(document).on("click",`[data-role="update-brand"]`,function(e){
		e.preventDefault();
		let url = $(this).parents("form").attr("action")
		data["name"] 	= $(`[data-name="brand-name"]`).val();
		data["image"] = $(`[name="file_uploader"]`).val();
		data["description"] = brandDescValue.getData();
		// if (!data["image"]) {
		// 	 data["image"] = $(`[data-role="exist-image"]`).attr("src");
	 	// }
		if(!data.name) {
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
			url,
			data: {name:data.name,description:data.description,image: data.image},
			headers: hdKey,
			success: function(d){
				if (d.code === 202) {
					Loader.btn({
						element: e.target,
						action: "success",
					});
					$(`[data-role="brand-image-preview"]`).attr('src', '').parent().addClass("d-none");
					location.reload();
				}
				notify_once(d.message,d.code === 202 ? "success" : "warning")
			},
			error: function(d){
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
	})
});
