import { $ld,redMess,pg_lang,isInValid,notify_once,l,isset,path_local,
					filter_url,$_get,number_format,$db,btn_spinner,getUrlParameter,
						hdKey,environment } from './parts.min.js?v=1ab';
import {
	Loader
} from './loader.min.js?v=2';

$(function() {
	// ........
	$('body').removeClass('preload');
	// ........

	// label group starts

	// for (var i = 0; i < $(".label-group label").length; i++) {
	// 	let perc = (100 - 3)/$(".label-group label").length;
	// 	$(".label-group label:eq("+i+")").css("width",perc + "%");
	// }
	// $(".label-group").removeClass("d-none");
	// label group ends



	$db.on("click",".configListFT",function(){
		let t = $(this);
		let id = t.data("href");
		$(".panel-body").removeClass("d-none").addClass("d-none");
		$(id).hasClass("d-none") ? $(id).removeClass("d-none") : $(id).addClass("d-none");
	});

	$db.on("change",".configClass",function(){
		let t = $(this);
		let list = {key:t.data("key"),status:t.val()},
				cl = $(".spinner");
		cl.hasClass("d-none") ? cl.removeClass("d-none") : "";
		$.ajax({
			url:`${environment}admin/config/update-footer-action`,
			headers:hdKey,type: 'POST',data:list,
			success:function(d){
				console.log(d)
			},
			complete:function(){
				!cl.hasClass("d-none") ? cl.addClass("d-none") : "";
			}
		});
	});



	$('.drop-down-key').on('click', function () {
		$(this).next().toggleClass('active');
	});

	$('.drop-down-language li').on('click', function (e) {
		// $(this).parents('.drop-down-language').prev().find('p').text($(this).text().trim());
		$(this).parents('.drop-down-language').toggleClass('active');
		$(this).find('a').toggleClass('selected');
		let selectedDataId = $(this).data('id');

		// let imgSrc = $(this).find('img').attr('src');
		// $(this).parents('.drop-down-language').prev().find('img').attr('src', imgSrc)
	});

	//
	$db.on('click', '.top-line .nav-line .list .list-p, .top-line .nav-line .list em', function () {
		$(this).parent().toggleClass('active');
		$('.top-line .nav-line .list').not($(this).parent()).removeClass('active');
	});
	//
	$db.on('click', '.top-line .nav-line .list-inner-child p', function(){
		$(this).parent().toggleClass('active');
		$('.top-line .nav-line list-inner-child').not($(this).parent()).removeClass('active');
	})

	// $(document).on("click",`[data-role="admin-header-list"] .list`,function(){
	// 	let hasClass = $(this).hasClass("active");
	// 	$(`[data-role="admin-header-list"] .list`).removeClass("active");
	// 	!hasClass && $(this).addClass("active");
	// });


	//#region POP UP / s
	const popBtn = $('.popBtn')

	$db.on('click', '.popBtn', function (e) {
		const data = $(this).data('id')
		const targetPopup = $(`.popUpStyle[data-id=${data}]`);
		e.preventDefault();
		$(targetPopup).toggleClass('active');
		$('body').toggleClass('overflow-hidden');
		// console.log($(this).data('id'))
	})

	$db.on('click', '.popUpStyle .close-icon', function () {
		$('.popUpStyle').removeClass('active');
		$('body').removeClass('overflow-hidden');
	})

	$db.on('click', '.overlay-pop-up', function () {
		$(this).parent().removeClass('active');
		$('body').removeClass('overflow-hidden');
	});

});
