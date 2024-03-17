import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey,isInValid,changeurl,getBase64
} from './../parts.min.js?v=1ba';
import {
	Loader
} from './../loader.min.js?v=2';

$(function () {


	let newsComponent = (id,name,images,description) => {
		let img = images && images[0] ? images[0].small : "/assets/landing/img/no_photo.png";
		return (`<tr data-id="${id}">
							<td>${id}</td>
							<td><img src=${img} style="width:70px"></td>
							<td>${name}</td>
							<td>${description || ""}</td>
							<td><a data-role="deleteCertItem" href="javascript:void(0)"><em class="fa fa-trash"></em></a></td>
						</tr>`);
	}

	// $db.on("click",`[data-role="deleteCertItem"]`,function(){
	// 	console.log($(this).parent().data("id"))
	// })

	let getCertificateList = () => {
		$.get({
			url: `/certificates/list-live`,
			headers: hdKey,
			data: {version: "full",limit: 1000000},
			success: function(d){
				let h = d.code === 200 ? d.data.map(v => newsComponent(v.id,v.name,v.files,v.description)).join(" ") : "";
				$("#certificate_list").html(h).removeClass("load");
			},error: function(d){
				console.error(d);
			}
		});
	}

	$(`#certificate_list`).length ? getCertificateList() : "";

	if ($(`[data-role="add-new-certificate"]`).length) {
		let insertDataImages = [];
		let previewFunction = (images) => {
			let imgs = images.map(v => `<div class="line pvIMG"><span><em class="fa fa-times"></em></span><img src="${v}"></div>`).join("");
			if (imgs) {
				$(`[data-role="certificate-preview-container"]`).removeClass("d-none").html(imgs);
			}else{
				$(`[data-role="certificate-preview-container"]`).addClass("d-none").html("");
			}
		}

		$db.on("click",".pvIMG em",function(){
			let value = $(this).parent().siblings("img").attr("src");
			insertDataImages = insertDataImages.filter(function(item) {
	    	return item !== value
			});
			previewFunction(insertDataImages);
		});

		$db.on(`change`,`[data-role="certificate-file"]`,function(e) {
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

		$db.on("click",`[data-role="add-new-certificate"]`,function(e){
			let t = $(this);
			let data = {
				name: $(`[data-role="certificate-name"]`).val(),
				files: insertDataImages
			}

			Loader.btn({
				element: e.target,
				action: "start",
			});
			$.post({
				url: `/certificates/add`,
				data,headers: hdKey,
				success: function(d){
					console.log(d);
					if (d.code === 201) {
						Loader.btn({
							element: e.target,
							action: "success",
						});
						$(`[data-role="certificate-name"]`).val("")
						insertDataImages.length = 0;
						previewFunction(insertDataImages);
						getCertificateList();
					}else{
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
			});
		});


	}


	$db.on("click",`[data-role="deleteCertItem"]`,function(){
		let t = $(this);
		let data = {
			certificate: t.parents(`tr`).data("id")
		}
		Swal.fire({
		  text: l("Are you sure to delete certificate"),
			showCancelButton: true,
			confirmButtonText: l("Yes"),
			confirmButtonColor: '#d63030',
			cancelButtonText: l("Cancel")
		}).then((res) => {
		  if (res.value) {
				$.post({
					url: `/certificates/delete`,
					data,headers: hdKey,
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


});
