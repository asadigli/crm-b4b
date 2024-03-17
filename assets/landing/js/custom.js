$(document).ready(function () {

	// gb vbs
	let db = $(document.body)
	let body = $('body')

	body.removeClass('preload');


	if($(".fancybox").length){
		$('.fancybox').fancybox();
	}

	// ------------- DECLARE ON CLICK BODY CLOSE ITEM ------------- //
	$(document).click(function (e) {
		e.stopPropagation();
		let container = $(".container-select-language");
		if (container.has(e.target).length === 0) {
			$('.drop-down-language').removeClass('active');
		};
	});


	// $(window).bind("load", function() {
	AOS.init();
	//    });

	$('.wallp-section .owl-carousel').owlCarousel({
		items: 1,
		autoplay: true,
		dots: false,
		loop: true
	})

	$('.card h6 p').each(function () {
		$(this).prop('Counter', 0).animate({
			Counter: $(this).text()
		}, {
			duration: 3000,
			easing: 'swing',
			step: function (now) {
				$(this).text(Math.ceil(now));
			}
		});
	});

	$('.carousel-img-card .owl-carousel').owlCarousel({
		items: 1,
		autoplay: true,
		dots: true,
		loop: true
	});


	$(".tab_content").hide();
	$(".tab_content:first").show();

	$("ul.tabs li").click(function () {

		$(".tab_content").hide();
		var activeTab = $(this).attr("rel");
		$("#" + activeTab).fadeIn();

		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");

		$(".tab_drawer_heading").removeClass("d_active");
		$(".tab_drawer_heading[rel^='" + activeTab + "']").addClass("d_active");

	});

	$(".tab_drawer_heading").click(function () {

		$(".tab_content").hide();
		var d_activeTab = $(this).attr("rel");
		$("#" + d_activeTab).fadeIn();

		$(".tab_drawer_heading").removeClass("d_active");
		$(this).addClass("d_active");

		$("ul.tabs li").removeClass("active");
		$("ul.tabs li[rel^='" + d_activeTab + "']").addClass("active");
	});

	$('ul.tabs li').last().addClass("tab_last");

	db.on('click', '.menu-button', function () {
		body.toggleClass('open--menu');
		body.toggleClass('overflow-hidden');
	})

	db.on('click', '.filter-btn', function () {
		body.toggleClass('filter-opened');
		body.toggleClass('overflow-hidden');
	});

	db.on('click', '.filter-overlay', function () {
		body.removeClass('filter-opened');
		body.removeClass('overflow-hidden');
	});

	db.on('click', '.map-btn', function () {
		body.toggleClass('contact-closed');
	});

	//#region Accordion
	$('#faq .faq-inner .card-hedaer').click(function () {
		$('#faq .faq-inner .card-hedaer').not($(this)).removeClass('active');
		$('#faq .faq-inner .card-body').not($(this).next()).slideUp();

		$(this).toggleClass('active');
		$(this).next().slideToggle();
	})
	//#endregion

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


	// $('.drop-down-language li a.selected').each(function () {

	// 	if ($('.drop-down-language li a').hasClass('selected')) {
	// 		// let imgSrc = $(this).parent().find('img').attr('src');
	// 		// $(this).parents('.drop-down-language').prev().find('img').attr('src', imgSrc)
	// 		$(this).parents('.drop-down-language').prev().find('p').text($(this).text().trim());
	// 	}

	// });

	db.on('click', '.top-line .nav-line .list .list-p, .top-line .nav-line .list em', function () {
		$(this).parent().toggleClass('active');
		$('.top-line .nav-line .list').not($(this).parent()).removeClass('active');

	});

	db.on('click', '.top-line .nav-line .list-inner-child p', function(){
		$(this).parent().toggleClass('active');
		$('.top-line .nav-line list-inner-child').not($(this).parent()).removeClass('active');
	})


	//#region POP UP / s
	const popBtn = $('.popBtn')

	// db.on('click', '.popBtn', function (e) {
	// 	const data = $(this).data('id')
	// 	const targetPopup = $(`.popUpStyle[data-id=${data}]`);
	// 	e.preventDefault();
	// 	$(targetPopup).toggleClass('active');
	// 	// $('body').toggleClass('overflow-hidden');
	// })
	//
	// db.on('click', '.popUpStyle .close-icon', function () {
	// 	$('.popUpStyle').removeClass('active');
	// 	// $('body').removeClass('overflow-hidden');
	// })

	db.on('click', '.overlay-pop-up, .popUpStyle .close-icon', function () {
		$(this).parent().removeClass('active');
		$('body').removeClass('overflow-hidden');
	})

});
