import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey,isInValid,changeurl,getBase64,path_local
} from './../../parts.min.js?v=1abc';
import {
	Loader
} from './../../loader.min.js?v=2';

$(function () {



		let insertDataImages = [],
				exist_images = [];
		if ($(`[data-role="edit-news"]`).length) {
			$(`[data-role="news-preview-container"]`).find(".pvIMG").each(v => {
				let ch = $(`[data-role="news-preview-container"]`).find(".pvIMG").eq(v).find("img");
				insertDataImages.push(ch.attr("src"));
				exist_images.push({name: ch.data("src"),code: ch.attr("src")});
			})
		}
		// console.log(insertDataImages)
		// console.log(exist_images)

		let previewFunction = (images) => {
			let imgs = images.map(v => `<div class="line pvIMG"><span><em class="fa fa-times"></em></span><img src="${v}"></div>`).join("");
			if (imgs) {
				$(`[data-role="news-preview-container"]`).removeClass("d-none").html(imgs);
			}else{
				$(`[data-role="news-preview-container"]`).addClass("d-none").html("");
			}
		}

		$db.on("click",".pvIMG em",function(){
			let value = $(this).parent().siblings("img").attr("src");
			insertDataImages = insertDataImages.filter(function(item) {
	    	return item !== value
			})
			previewFunction(insertDataImages);
		})

		$db.on(`change`,`[data-role="news-image-container"]`,function(e) {
			let input = e.target;
			if (input.files && input.files.length) {
				for (let i = 0; i < input.files.length; i++) {
					getBase64(input.files[i]).then(
				  	data => {
							insertDataImages.push(data);
							previewFunction(insertDataImages);
						}
					);
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
					.catch(e => {console.error(e);});


				$db.on("click",`[data-role="add-new-news"]`,function(e){
					let t = $(this);
					let cover_image = "";
					let tags = $(`[data-role="tagsinput"]`).val();
					let data = {
						title: $(`[data-name="news-title"]`).val(),
						details: nsDesc.getData(),
						lang: $(`[data-role="data-lang"]`).val(),
						status: $(`[data-role="is_active_news"]`).is(":checked"),
						type: $(`[data-name="news-type"]`).val(),
						image_link: $(`[data-name="news-image-link"]`).val(),
						tags: tags,
						images: insertDataImages
					}
					// console.log(data)

					Loader.btn({
						element: e.target,
						action: "start",
					});
					$.post({
						url: `/news/add-action`,
						data,headers: hdKey,
						success: function(d){
							// console.log(d);
							if (d.code === 201) {
								Loader.btn({
									element: e.target,
									action: "success",
								});
								$(`[data-name="news-title"],[data-name="news-image-link"]`).val("")
								nsDesc.setData("");
								$(`[data-role="tagsinput"]`).tagsinput('removeAll');
								insertDataImages.length = 0;
								previewFunction(insertDataImages);
							}else{
								console.log(d);
								Loader.btn({
									element: e.target,
									action: "error",
								});
							}
							notify_once(d.message,d.code === 201 ? "success" : "error")
						},error: function(d){
							console.log(d)
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



});
