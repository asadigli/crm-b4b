import {
	notify_once,l,filter_url,btn_spinner,hdKey,isInValid,changeurl,getBase64,path_local
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
			exist_images.push(parent.find(".exist-image-item").eq(v).find("img").data("src"));
		})
	});

	const previewFunction = (images) => {
		let imgs = images.map(v => `<div class="line pvIMG"><span><em class="fa fa-times"></em></span><img src="${v}"></div>`).join("");
		if (imgs) {
			$(`[data-role="news-preview-container"]`).removeClass("d-none").html(imgs);
		}else{
			$(`[data-role="news-preview-container"]`).addClass("d-none").html("");
		}
	}

	$(document).on("click",".pvIMG em",function(){
		let value = $(this).parent().siblings("img").attr("src");
		insertDataImages = insertDataImages.filter(function(item) {
			return item !== value;
		})
		previewFunction(insertDataImages);
	})

	$(document).on(`change`,`[data-role="news-image-container"]`,function(e) {
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
	ClassicEditor.create(document.querySelector("#news_description"),{
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

	$(document).on("click",`[data-role="edit-news"]`,function(e){
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
			title: $(`[data-name="news-title"]`).val(),
			image_link: $(`[data-name="news-image-link"]`).val(),
			details: nsDesc.getData(),
			status: $(`[data-role="is_active_news"]:checked`).val(),
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
		});
	});



	// if ($(`[data-role="add-new-news"]`).length) {
	// 	let nsDesc;
	// 	ClassicEditor.create(document.querySelector("#news_description"),{
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
	// 			$(document).on("click",`[data-role="add-new-news"]`,function(e){
	// 				let t = $(this);
	// 				let cover_image = "";
	// 				let tags = $(`[data-role="tagsinput"]`).val();
	// 				let data = {
	// 					title: $(`[data-name="news-title"]`).val(),
	// 					details: nsDesc.getData(),
	// 					status: $(`[data-role="is_active_news"]`).is(":checked"),
	// 					type: $(`[data-name="news-type"]`).val(),
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
	// 					url: `/news/add-action`,
	// 					type: 'POST',
	// 					data,headers: hdKey,
	// 					success: function(d){
	// 						// console.log(d);
	// 						if (d.code === 201) {
	// 							Loader.btn({
	// 								element: e.target,
	// 								action: "success",
	// 							});
	// 							$(`[data-name="news-title"]`).val("")
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
