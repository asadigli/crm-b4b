import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey,isInValid,changeurl,getBase64,path_local
} from './../parts.min.js?v=1abcs';
import {
	Loader
} from './../loader.min.js?v=2';

$(function () {


	let promotionComponent = (id,title,images,slug,date,status) => {
		let img = images && images[0] ? images[0].small : "/assets/landing/img/no_photo.png";
		return (`<tr data-id="${id}">
							<td>${id}</td>
							<td><img src=${img} style="width:70px"></td>
							<td>${title}</td>
							<td>${date}</td>
							<td><input type="checkbox"${status ? " checked" : ""} data-role="change-news-status"></td>
							<td>
								<div class="btn-group">
									<a class="btn btn-primary" href="${path_local(`admin/promotion/${id}/edit`)}"><em class="fa fa-edit"></em></a>
									<a class="btn btn-danger" data-role="deleteNewItem" href="javascript:void(0)"><em class="fa fa-trash"></em></a>
								</div>
							</td>
						</tr>`);
	}

	let getPromotionList = () => {
		$.ajax({
			url: `/promotion/list-live`,
			type: 'GET',headers: hdKey,
			data: {version: "full",limit: 1000000},
			success: function(d){
				let h = d.code === 200 ? d.data.list.map(v => promotionComponent(v.id,v.title,v.images,v.slug,v.date,v.status)).join(" ") : "";
				$("#admin_promotion_list").html(h).removeClass("load");
			},error: function(d){
				console.error(d);
			}
		});
	}

	$(`#admin_promotion_list`).length ? getPromotionList() : "";

	$db.on("click",`[data-role="update-news"]`,function(e){
		let t = $(this);
		let data = {
			title: $(`[data-name="news-title"]`).val(),
			details: nsDesc.getData(),
			news: $("#newsID").val(),
			status: $(`[data-role="is_active_news"]`).is(":checked") ? "1" : "0"
		}


		Loader.btn({
			element: e.target,
			action: "start",
		});
		$.ajax({
			url: `/news/update`,
			type: "POST",
			data,headers: hdKey,
			success: function(d){
				console.log(d)
				if (d.code === 200) {
					Loader.btn({
						element: e.target,
						action: "success"
					});
					// data.status === "1" ? $("#admin_promotion_list").prepend(promotionComponent(d.data.id,stripHtml(data.title),data.cover_image,d.data.slug)) : "";
					// $(`[data-role="go-to-view-link"]`).attr("href","/news/" + d.data.slug + "?action=view")
					// changeurl(location.href,"/news/" + d.data.slug + "?action=edit");
				}else{
					Loader.btn({
						element: e.target,
						action: "error",
					});
				}
				notify_once(d.message,d.code === 200 ? "success" : "error")
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


	$db.on("click",`[data-role="deleteNewItem"]`,function(){
		let t = $(this);
		let data = {
			news: t.parents(`tr`).data("id")
		}
		Swal.fire({
		  text: l("Are you sure to delete promotion"),
			showCancelButton: true,
			confirmButtonText: l("Yes"),
			confirmButtonColor: '#d63030',
			cancelButtonText: l("Cancel")
		}).then((res) => {
		  if (res.value) {
				$.ajax({
					url: `/news/delete`,
					type: 'POST',
					data: data,headers: hdKey,
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
