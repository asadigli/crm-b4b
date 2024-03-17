import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey,isInValid,changeurl,getBase64,path_local
} from './../../parts.min.js?v=1abc';
import {
	Loader
} from './../../loader.min.js?v=2';

$(function () {


	let insertDataImages = [],exist_images = [];

	$(document).on("click",`[data-role="delete-exist-image"]`,function(){
		let parent = $(this).parents(".exist-image-items");
		$(this).parents(".exist-image-item").remove();
		parent.find(".exist-image-item").each(v => {
			exist_images.push(parent.find(".exist-image-item").eq(v).find("img").data("src"))
		})
	});

	let previewFunction = (images) => {
		let imgs = images.map(v => `<div class="line pvIMG"><span><em class="fa fa-times"></em></span><img src="${v}"></div>`).join("");
		if (imgs) {
			$(`[data-role="promotion-preview-container"]`).removeClass("d-none").html(imgs);
		}else{
			$(`[data-role="promotion-preview-container"]`).addClass("d-none").html("");
		}
	}

	$db.on("click",".pvIMG em",function(){
		let value = $(this).parent().siblings("img").attr("src");
		insertDataImages = insertDataImages.filter(function(item) {
			return item !== value
		})
		previewFunction(insertDataImages);
	})

	$db.on(`change`,`[data-role="promotion-image-container"]`,function(e) {
		let input = e.target;
		if (input.files && input.files.length) {
			for (let i = 0; i < input.files.length; i++) {
				getBase64(input.files[i]).then(data => {
					insertDataImages.push(data);
					previewFunction(insertDataImages);
				});
			}
		}
	});

	let nsDesc;
	ClassicEditor.create(document.querySelector("#promotion_description"),{
		toolbar: {
			items: ['heading','|','bold','italic','|','bulletedList','numberedList','|','undo','redo']
		},
		image: {
			toolbar: ['imageStyle:full','imageStyle:side','|','imageTextAlternative']
		},
		table: {
			contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
		},
		language: 'az'
	}).then(editor => {nsDesc = editor;})
			.catch(e => {console.error(e);})
				.finally(e => {
					$("body").removeClass("loader");
				});


	$("body").addClass("loader");

	$db.on("click",`[data-role="edit-promotion"]`,function(e){
		e.preventDefault();

		exist_images.length = 0;
		let parent = $(".exist-image-items");
		parent.find(".exist-image-item").each(v => {
			exist_images.push(parent.find(".exist-image-item").eq(v).find("img").data("src"))
		})

		let t = $(this);
		let url = t.parents("form").attr("action");
		let tags = $(`[data-role="tagsinput"]`).val();
		let data = {
			title: $(`[data-name="promotion-title"]`).val(),
			details: nsDesc.getData(),
			status: $(`[data-role="is_active_promotion"]:checked`).val(),
			images: insertDataImages,
			exist_images,
			tags: tags ? tags.split(",") : null
		}


		Loader.btn({
			element: e.target,
			action: "start",
		});
		$.post({
			url,data,headers: hdKey,
			success: function(d){
				if (d.code === 200) {
					Loader.btn({
						element: e.target,
						action: "success"
					});
					location.reload();
				}else{
					Loader.btn({
						element: e.target,
						action: "error",
					});
				}
				notify_once(d.message,d.code === 200 ? "success" : "error")
			},error: function(d){
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
	});



	// if ($(`[data-role="add-new-promotion"]`).length) {
	// 	let nsDesc;
	// 	ClassicEditor.create(document.querySelector("#promotion_description"),{
	// 			toolbar: {
	// 				items: ['heading','|','bold','italic','|','color','|','bulletedList','numberedList','|','undo','redo']
	// 			},
	// 			image: {
	// 				toolbar: ['imageStyle:full','imageStyle:side','|','imageTextAlternative']
	// 			},
	// 			table: {
	// 				contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
	// 			},
	// 			language: 'az'
	// 		}).then(editor => {nsDesc = editor;})
	// 				.catch(e => {console.error(e);});
	//
	//
	// 			$db.on("click",`[data-role="add-new-promotion"]`,function(e){
	// 				let t = $(this);
	// 				let cover_image = "";
	// 				let tags = $(`[data-role="tagsinput"]`).val();
	// 				let data = {
	// 					title: $(`[data-name="promotion-title"]`).val(),
	// 					details: nsDesc.getData(),
	// 					status: $(`[data-role="is_active_promotion"]`).is(":checked"),
	// 					type: $(`[data-name="promotion-type"]`).val(),
	// 					tags: tags,
	// 					images: insertDataImages
	// 				}
	// 				// console.log(data)
	//
	// 				Loader.btn({
	// 					element: e.target,
	// 					action: "start",
	// 				});
	// 				$.ajax({
	// 					url: `/promotion/add-action`,
	// 					type: 'POST',
	// 					data,headers: hdKey,
	// 					success: function(d){
	// 						// console.log(d);
	// 						if (d.code === 201) {
	// 							Loader.btn({
	// 								element: e.target,
	// 								action: "success",
	// 							});
	// 							$(`[data-name="promotion-title"]`).val("")
	// 							nsDesc.setData("");
	// 							$(`[data-role="tagsinput"]`).tagsinput('removeAll');
	// 							insertDataImages.length = 0;
	// 							previewFunction(insertDataImages);
	// 						}else{
	// 							Loader.btn({
	// 								element: e.target,
	// 								action: "error",
	// 							});
	// 						}
	// 						notify_once(d.message,d.code === 201 ? "success" : "error")
	// 					},error: function(d){
	// 						console.log(d)
	// 						Loader.btn({
	// 							element: e.target,
	// 							action: "error",
	// 						});
	// 					},complete: function(){
	// 						Loader.btn({
	// 							element: e.target,
	// 							action: "end",
	// 						});
	// 					}
	// 				})
	// 			});
	//
	//
	// }


});
