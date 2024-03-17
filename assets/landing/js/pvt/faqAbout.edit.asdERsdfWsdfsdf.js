import {
	notify_once,l,filter_url,$db,btn_spinner,hdKey
} from './../parts.min.js?v=1bc';
import {
	Screen
} from './../current_screen.min.js?v=2';

import {
	Loader
} from './../loader.min.js?v=2';

$(function () {

	let faqLoaded = false,lang = $(`[data-role='data-lang']`).val();

	let getPageData = (success,type,lang) => {
		$.get({
			url: '/admin/pages/list',data: {lang,type},headers: hdKey,
			success,
			error: function(d){
				console.error(d);
			},
			complete: function(){

			}
		})
	}

	let getFAQList = (lang) => {
		$("#faq_list tbody").html("");
		getPageData(function(d){
			let h = '';
			if (d.code === 200) {
				d.data.map(v => {
					h += `<tr data-id="${v.id}">
									<td>${v.title}</td>
									<td>${v.details}</td>
									<td>
										<a href="javascript:void(0)" data-role="about-pop-delete-btn">
											<em class="fas fa-trash"></em>
										</a>
										<a href="javascript:void(0)" class="popBtn" data-id="about-pop-edit" data-role="about-pop-edit-btn">
											<em class="fas fa-edit"></em>
										</a>
									</td>
								</tr>`;
				})
			}
			$("#faq_list tbody").html(h);
		},"faq",lang);
	}



	$(document).on("click",`[data-role="about-pop-edit-btn"]`,function(){
		let tr = $(this).parents("tr");
		let fef = $(`[data-role="faq-edit-form"]`);
		fef.find(`[name="faq_id"]`).val(tr.data("id"));
		fef.find(`[name="language"]`).val(lang);
		fef.find(`[name="faq_title"]`).val(tr.find("td").eq(0).text());
		fef.find(`[name="faq_body"]`).val(tr.find("td").eq(1).text());
	});

	let nsDesc;
	let getAboutList = (lang) => {
		$("#about-page").html("");
		getPageData(function(d){
			let txt = $("#about-page").data("btn-text");
			let h = `<form action="/admin/pages/about/add" method="POST" data-role="edit-page-about">
									<div class="form-group">
										<input type="text" id="page-about-title" value="" class="form-control">
									</div>
									<div class="form-group">
										<textarea id="page-about-area"></textarea>
									</div>
									<div class="form-group">
										<button data-role="update-page-about" class="def-btn">${txt}</button>
									</div>
								</form>`;
			if (d.code === 200) {
				h = `<form action="/admin/pages/about/${d.data.id}/edit" method="POST" data-role="edit-page-about">
										<div class="form-group">
											<input type="text" id="page-about-title" value="${d.data.title}" class="form-control">
										</div>
										<div class="form-group">
											<textarea id="page-about-area">${d.data.details || ""}</textarea>
										</div>
										<div class="form-group">
											<button data-role="update-page-about" class="def-btn">${txt}</button>
										</div>
									</form>`;
			}
			$("#about-page").html(h);
			ClassicEditor.create(document.querySelector("#page-about-area"),{
					toolbar: {
						items: ['heading','|','bold','italic','|','bulletedList','numberedList','|','undo','redo']
					},
					language: 'az'
				}).then(editor => {nsDesc = editor;})
						.catch(e => {console.error(e);});
		},"about",lang);
	}

	$(document).on("click",`[data-role="update-page-about"]`,function(e){
		e.preventDefault();
		let title = $("#page-about-title").val(),
				url = $(this).parents("form").attr("action");
		let data = {lang,title,details: nsDesc.getData()};
		if (!data.title || !data.details) return;
		Loader.btn({
			element: e.target,
			action: "start"
		});
		$.post({
			url,data,headers: hdKey,success: function(d){
				console.log(d)
				if ([200,201].includes(d.code)) {
					Loader.btn({
						element: e.target,
						action: "success"
					});
					// form.find(`#page-about-title,#page-about-area`).val("")
					getAboutList(lang);
				}
				notify_once(d.message,[200,201].includes(d.code) ? "success" : "warning")
			}, error: function(d) {
				Loader.btn({
					element: e.target,
					action: "error"
				});
				console.error(d)
			},
			complete: function(){
				Loader.btn({
					element: e.target,
					action: "end"
				});
			}
		})
	})

	$(document).on("change",`[data-role="data-lang"]`,function(){
		lang = $(this).val();
		filter_url([{'data-lang': lang}])
		getFAQList(lang);
		getAboutList(lang);
	});

	$(document).on("click",`[data-role="add-new-faq"]`,function(e){
		e.preventDefault();
		let form = $(this).parents("form");
		let language = form.find(`[name="language"]`).val(),
				title = form.find(`[name="faq_title"]`).val(),
				details = form.find(`[name="faq_body"]`).val(),
				status = form.find(`[name="faq_status"]`).is(":checked") ? "1" : "0",
				url = form.attr("action");
		let data = {lang: language,title,details,status};
		if (!data.title || !data.details) return;
		// console.log(data)
		Loader.btn({
			element: e.target,
			action: "start"
		});
		$.post({
			url,data,headers: hdKey,success: function(d){
				console.log(d)
				if (d.code === 201) {
					Loader.btn({
						element: e.target,
						action: "success"
					});
					form.find(`[name="faq_title"],[name="faq_body"]`).val("")
					form.find(`[name="faq_status"]`).prop("checked",true);
					$(`.popUpStyle[data-id="add-faq-popup"]`).removeClass('active');
					$('body').removeClass('overflow-hidden');
					if (lang === data.lang) {
						getFAQList(lang);
					}
				}
				notify_once(d.message,d.code === 201 ? "success" : "warning")
			}, error: function(d) {
				Loader.btn({
					element: e.target,
					action: "error"
				});
				console.error(d)
			},
			complete: function(){
				Loader.btn({
					element: e.target,
					action: "end"
				});
			}
		})
	});


	$(document).on("click",`[data-role="edit-faq"]`,function(e){
		e.preventDefault();
		let form = $(this).parents("form");
		let language = form.find(`[name="language"]`).val(),
				title = form.find(`[name="faq_title"]`).val(),
				details = form.find(`[name="faq_body"]`).val(),
				status = form.find(`[name="faq_status"]`).is(":checked") ? "1" : "0",
				url = `/admin/pages/faq/${form.find(`[name="faq_id"]`).val()}/edit`;
		let data = {lang: language,title,details,status};
		if (!data.title || !data.details) return;
		Loader.btn({
			element: e.target,
			action: "start"
		});
		$.post({
			url,data,headers: hdKey,success: function(d){
				console.log(d)
				if (d.code === 201) {
					Loader.btn({
						element: e.target,
						action: "success"
					});
					$(`.popUpStyle[data-id="about-pop-edit"]`).removeClass('active');
					$("body").removeClass('overflow-hidden');
					getFAQList(lang);
				}
				notify_once(d.message,d.code === 201 ? "success" : "warning")
			}, error: function(d) {
				Loader.btn({
					element: e.target,
					action: "error"
				});
				console.error(d)
			},
			complete: function(){
				Loader.btn({
					element: e.target,
					action: "end"
				});
			}
		})
	});


	getFAQList(lang);
	getAboutList(lang);




	$(document).on("click",`[data-role="about-pop-delete-btn"]`,function(){
		let t = $(this);
		Swal.fire({
		  text: "FAQ-dan sualı silməkdə əminsinizmi?",
			showCancelButton: true,
			confirmButtonText: l("Yes"),
			confirmButtonColor: '#d63030',
			cancelButtonText: l("Cancel")
		}).then((res) => {
		  if (res.value) {
				$.ajax({
					url: `/admin/pages/faq/${t.parents("tr").data("id")}/delete`,
					type: "DELETE",
					headers: hdKey,
					success: function (d){
						console.log(d)
						if(d.code === 201){
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
	});


});
