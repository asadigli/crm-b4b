import {
	$ld,notify_once,l,paginateController,getUrlParameter,
	filter_url,$_get,number_format,$db,hdKey,path_local,slugify
} from './parts.min.js?v=1.0.5';
import {productComponent,newsComponent,promotionComponent,brandComponent,certificateComponent} from './components.min.js?v=1.0.3';
import {Screen} from './current_screen.min.js?v=2';
import {Loader} from './loader.min.js?v=2';
// import {disableScroll,enableScroll} from './scrollController.min.js?v=1';
// left: 37, up: 38, right: 39, down: 40,
// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
// var scroll_keys = {37: 1, 38: 1, 39: 1, 40: 1};

// function preventDefault(e) {
//   e.preventDefault();
// }

// function preventDefaultForScrollKeys(e) {
//   if (scroll_keys[e.keyCode]) {
//     preventDefault(e);
//     return false;
//   }
// }

// modern Chrome requires { passive: false } when adding event
// var supportsPassive = false;
// try {
//   window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
//     get: function () { supportsPassive = true; }
//   }));
// } catch(e) {}

// var wheelOpt = supportsPassive ? { passive: false } : false;
// var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

// call this to Disable
// export const disableScroll = () => {
//   window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
//   window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
//   window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
//   window.addEventListener('keydown', preventDefaultForScrollKeys, false);
// }

// call this to Enable
// export const enableScroll = () => {
//   window.removeEventListener('DOMMouseScroll', preventDefault, false);
//   window.removeEventListener(wheelEvent, preventDefault, wheelOpt);
//   window.removeEventListener('touchmove', preventDefault, wheelOpt);
//   window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
// }

(function(func ) {
		$.fn.addClass = function() {
				func.apply( this, arguments );
				this.trigger('classChanged');
				return this;
		}
})($.fn.addClass);

(function( func ) {
		$.fn.removeClass = function() {
				func.apply( this, arguments );
				this.trigger('classChanged');
				return this;
		}
})($.fn.removeClass);


$(function () {

	let similarProdLoaded = false,
			prodDetailsLoaded = false,
			crossReferenceLoaded = false,
			prodSimilarOEMs = false,
			brandsLoaded = false,
			regionsLoaded = false,
			prodCompCars = false;


	$(document).on('click', '.popBtn', function (e) {
		const data = $(this).data('id')
		const targetPopup = $(`.popUpStyle[data-id=${data}]`);
		e.preventDefault();
		$(targetPopup).toggleClass('active');
		$('body').toggleClass('overflow-hidden');
	});

	$(document).on("click",`[data-role="prod-img-thumbnail"]`,function(){
		// console.log($(this).data("src"));
		$(`[data-role="main-img-product"]`).attr("style",`background-image: url('${$(this).data("src")}')`);
	})

	$(document).on('click', '.popUpStyle .close-icon', function () {
		$('.popUpStyle').removeClass('active');
		// $('body').removeClass('overflow-hidden');
	})

	// $("#be-partner").on('classChanged', function(){
	// 	$(this).hasClass("active") ? disableScroll() : enableScroll();
	// });

	if ($(`input[name="person_number"]`).length) {
		$(`input[name="person_number"]`).inputmask({
			mask: '(99) 999-99-99',
			autoUnmask: true,
			removeMaskOnSubmit: true
		});
	}

	if($("#g-recaptcha-response").length){
		$("#g-recaptcha-response").prop("required",true);
		$(document).on("click",`[data-role="send-contact-btn"]`,function(e){
			e.preventDefault();
			let form = $(this).parents("form");
			let url = form.attr("action"),
					inps = form.find("input,textarea"), data = {},
					msg = $(this).data("error-text"),aa = 0;
			inps.each(v => {
				let ch = inps.eq(v);
				data[ch.attr("name")] = ch.val()
				ch.siblings(`[data-role="input-error-mess"]`).eq(0).remove();
				ch.removeClass('invalid');
				if (ch.attr("required") && !ch.val()) {
					ch.addClass('invalid');
					ch.after(`<span data-role="input-error-mess">${msg}</span>`);
					aa++;
				}else{
				}
			});
			if(aa) return;
			Loader.btn({
				element: e.target,
				action: "start"
			});

			$.post({
				url,data,headers: hdKey,
				success: function(d){
					// console.log(d);
					if (d.code === 200) {
						inps.val("")
						notify_once(l("Thank you for keeping in touch with us"),"success")
						Loader.btn({
							element: e.target,
							action: "success"
						});
					}else{
						console.error(d);
						notify_once(l("Warning"),"warning")
						Loader.btn({
							element: e.target,
							action: "warning"
						});
					}
				},error: function(d){
					console.error(d);
					Loader.btn({
						element: e.target,
						action: "error"
					});
				},complete: function(){
					Loader.btn({
						element: e.target,
						action: "end"
					});
				}
			});
		});
	}

	$(document).on("click",`button[data-id="be-partner"]`,function(){
		if(regionsLoaded) return;
		regionsLoaded = true;
		$.get({
			headers: hdKey,url: "/product/regions",
			success: function(d){
				let h = d.length ? d.map(v => `<option value="${v.name}">${v.name}</option>`) : "";
				$("#formCityList").html(`<option value="">${$("#formCityList").data("text")}</option>${h}`);
				regionsLoaded = d.length ? true : false;
			},
			error: function (){regionsLoaded = false;}
		});

		$(`input[name="comp_phone"]`).inputmask({
			mask: '(99) 999-99-99',
			autoUnmask: true,
			removeMaskOnSubmit: true
		});

	});

	$(document).on("keyup",`[type="email"]`,function(){
		let email = $(this).val();
		$(this).siblings(`[data-role="input-error-mess"]`).eq(0).remove();
		if ($(this).attr("data-error-text")) {
			const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    	if (!re.test(String(email).toLowerCase())) {
				let msg = $(this).data("error-text");
				$(this).after(`<span data-role="input-error-mess">${msg}</span>`);
    	}
		}
	})

	let isValidClass = "is-invalid form-control";

	$(document).on("click","#formSend",function(e){
		e.preventDefault();
		let form = $(`#formSend`).parents("form");
		let inp = form.find(`input,textarea,select`),
				msg = $(this).data("error-text"),data = {},url = form.attr("action"),
				aa = 0;
		inp.each(v => {
			let ch = inp.eq(v);
			data[ch.attr("name")] = ch.val()
			ch.siblings(`[data-role="input-error-mess"]`).eq(0).remove();
			ch.removeClass('invalid');
			if (ch.attr("required") && !ch.val()) {
				ch.addClass('invalid');
				ch.after(`<span data-role="input-error-mess">${msg}</span>`);
				aa++;
			}else{
			}
		});

		if(aa) return;
		Loader.btn({
			element: e.target,
			action: "start"
		});
		$.post({
			url,data,headers: hdKey,
			success: function(d){
				if (d.code === 200) {
					Loader.btn({
						element: e.target,
						action: "success"
					});
					inp.val("");
					$('.popUpStyle').removeClass('active');
					$('body').removeClass('overflow-hidden');
					notify_once(l("We will contact you soon"),"success");
				}
			},error: function (d) {
				Loader.btn({
					element: e.target,
					action: "error"
				});
			},complete: function (){
				Loader.btn({
					element: e.target,
					action: "end"
				});
			}
		});

		// ch.addClass("is-invalid form-control");
		// ch.removeClass("is-invalid form-control");
	});

	$(`.carousel-product-detail .owl-carousel`).owlCarousel({
		items: 3,
		// autoplay: true,
		dots: true,
		margin: 15,
		navText: ["<img src='/assets/landing/img/icons/arrow-point-to-left.svg'>", "<img src='/assets/landing/img/icons/arrow-point-to-right.svg'>"],
		responsive: {
			0: {
				// items: 4,
				// navText: ["<img src='/assets/landing/img/icons/arrow-point-to-left-white.svg'>", "<img src='/assets/landing/img/icons/arrow-point-to-right-white.svg'>"],
			}
		}
	});


	let startProductOwlCarusel = (selector) => {
		$(selector).owlCarousel({
			items: 4,
			dots: false,
			nav: true,
			margin: 20,
			autoplay: true,
			autoplayTimeout: 3000,
			autoplayHoverPause: true,
			navText: ["<img src='/assets/landing/img/icons/arrow-point-to-left.svg'>", "<img src='/assets/landing/img/icons/arrow-point-to-right.svg'>"],
			responsive: {
				1281: {
					stagePadding: false
				},

				768: {
					items: 4,
				},

				0: {
					items: 2,
					navText: ["<img src='/assets/landing/img/icons/arrow-point-to-left-white.svg'>", "<img src='/assets/landing/img/icons/arrow-point-to-right-white.svg'>"],
				}
			}
		})
	}

	let panelSlider = $(`[data-role="panel-section-slider"]`).owlCarousel({
		items: 1,
		dots: false,
		nav: false,
		autoplay: true,
		autoplayTimeout: 5000,
		autoplayHoverPause: true,
		loop: true,
	});

	let panelSliderImagesLoaded = false;

	panelSlider.on("changed.owl.carousel", function(e) {
		if(panelSliderImagesLoaded) return;
		panelSliderImagesLoaded = true;
		$(`[data-role="panel-section-slider"]`).find("img").attr("src",$(`[data-role="panel-section-slider"]`).data("src")).removeAttr("data-src")
  });

	let prod_br_code = $("#productDetail").data("brand-code");
	let no_data_msg = $("#productDetail").data("no-info-text");

	let getSimilars = (product) => {
		if(!product || similarProdLoaded) return;
		similarProdLoaded = true;
		$.get({
			url: `/product/similar/list`,headers: hdKey,data: {product},cache: true,
			success: function(d){
				let h = Array.isArray(d) ? d.map(v => product !== v.slug ? productComponent(v.id, v.slug, v.name, v.brand, v.image) : "").join(" ") : "";
				if (h) {
					$("#similarProducts > div > div").html(h).removeClass("fake-card-line").addClass("owl-carousel , owl-theme");
					$("#similarProducts").removeClass("load")
					startProductOwlCarusel("#similarProducts > div > div");
				}else{
					$("#similarProducts > div > div").html(h).removeClass("fake-card-line");
				}
			},
			error: function(d){
				console.error(d)
				similarProdLoaded = false;
				$("#similarProducts > div > div").html(h).removeClass("fake-card-line");
			},
			complete: function(){}
		})
	}

	let getProductDetails = (product,selector) => {
		if(!product || prodDetailsLoaded) return;
		prodDetailsLoaded = true;
		let code = product ? (product + "").replace(/[^A-Z0-9]/ig, "") : "",
				$slc = $(selector ? selector : "#productDescription");
		let no_data = `<p class="text-center text-danger">${no_data_msg}</p>`,
				table = $slc.find("table");
		if (!code) {
			table.addClass("d-none").after(no_data);
		}
		$.get({
			url: `/product/details`,cache: true,
			headers: hdKey,data: {code},
			success: function(d){
				let h = "";
				if (d.code === 200) {
					let brands = [],cnt = 0, data = d.data;
					data.map(v => {
						Object.keys(brands).includes(v.brand) ? cnt++ : cnt = 1;
						brands[v.brand] = cnt;
					});
					data.map((v,i) => {
						h += `<tr>
										${i === 0 || v.brand !== data[i - 1].brand ? `<td rowspan="${brands[v.brand]}">${v.brand}</td>` : ""}
										<td>${v.namecriteria}</td>
										<td>${v.valuecriteria}</td>
									</tr>`;
 					});
				}

				if (h) {
					$slc.removeClass("load").find("tbody").html(h);
				}else{
					$slc.removeClass("load");
					table.addClass("d-none").after(no_data)
				}
			},
			error: function(d){
				console.error(d)
				prodDetailsLoaded = false;
				$slc.removeClass("load");
				table.addClass("d-none").after(no_data)
			},
			// complete: function(){}
		})
	}

	let getCrossReference = (product, selector) => {
		if(!product || crossReferenceLoaded) return;
		crossReferenceLoaded = true;
		let code = product ? (product + "").replace(/[^A-Z0-9]/ig, "") : "",
				$slc = $(selector ? selector : "#crossreference_list");
		// -
		let no_data = `<p class="text-center text-danger">${no_data_msg}</p>`,
				table = $slc.find("table");
		if (!code) {
			table.addClass("d-none").after(no_data);
		}
		$.get({
			url: `/product/cross-references`,
			headers: hdKey,cache: true,
			data: {code},
			success: function(d){
				let h = "",imgByCode = c => `<a href="https://www.google.com/images?q=${c}" target="_blank" onclick="window.event.preventDefault();this.newWindow = window.open('https://www.google.com/images?q=0024773001&amp;url='+escape(document.location.href)+'&amp;referrer='+escape(document.referrer), 'webim', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=800,height=500,top=20, left=400,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;">
										<i class="fa fa-camera cs_camera_icon" aria-hidden="true"></i></a>`;
				if (d.code === 200) {
					let brands = [],
							cnt = 0,
							data = d.data;
					data = data.sort(function (a, b) {
					    return ('' + a.car_brand).localeCompare(b.car_brand);
					})
					data.map(v => {
						brands[v.car_brand.trim()] = !Object.keys(brands).includes(v.car_brand.trim()) ? 1 : (brands[v.car_brand.trim()] + 1);
					});
					data.map((v,i) => {
						h += `<tr>
										${i === 0 || v.car_brand.trim() !== data[i - 1].car_brand.trim() ? `<td rowspan="${brands[v.car_brand.trim()]}">${v.car_brand}</td>` : ""}
										<td>${imgByCode(v.OEM)}</td>
										<td>${v.OEM}</td>
										<td>${v.brand}</td>
										<td>${imgByCode(v.brand_code)}</td>
										<td>${v.brand_code}</td>
										<td>${v.group || "--"}</td>
										<td>${v.product || "--"}</td>
									</tr>`;
 					});
				}
				if (h) {
					$slc.removeClass("load").find("tbody").html(h);
				}else{
					$slc.removeClass("load");
					table.addClass("d-none").after(no_data)
				}
			},
			error: function(d){
				console.error(d)
				crossReferenceLoaded = false;
				$slc.removeClass("load");
				table.addClass("d-none").after(no_data)
			},
			// complete: function(){}
		})
	}

	let getSimilarOEMs = (product, selector) => {
		if(!product || prodSimilarOEMs) return;
		prodSimilarOEMs = true;
		let code = product ? (product + "").replace(/[^A-Z0-9]/ig, "") : "",
				$slc = $(selector ? selector : "#oems_list");
		let no_data = `<p class="text-center text-danger">${no_data_msg}</p>`,
				table = $slc.find("table");
		if (!code) {
			table.addClass("d-none").after(no_data);
		}
		$.get({
			url: `/product/similar-oems`,
			headers: hdKey,cache: true,data: {code},
			success: function(d){
				let h = ""
				if (d.code === 200) {
					d.data.map((v,i) => {
						h += `<tr><td>${v}</td></tr>`;
					});
				}
				if (h) {
					$slc.removeClass("load").find("tbody").html(h);
				}else{
					$slc.removeClass("load");
					table.addClass("d-none").after(no_data)
				}
			},
			error: function(d){
				console.error(d)
				prodSimilarOEMs = false;
				$slc.removeClass("load");
				table.addClass("d-none").after(no_data)
			},
			complete: function(){}
		})
	}

	let getCompatibleCars = (product, selector) => {
		if(!product || prodCompCars) return;
		prodCompCars = true;
		let code = product ? (product + "").replace(/[^A-Z0-9]/ig, "") : "",
				$slc = $(selector ? selector : "#compatible_cars_list");
		let no_data = `<p class="text-center text-danger">${no_data_msg}</p>`,
				table = $slc.find("table");
		if (!code) {
			table.addClass("d-none").after(no_data);
		}
		$.get({
			url: `/product/compatible-cars`,
			headers: hdKey,cache: true,data: {code},
			success: function(d){
				let h = ""
				// console.log(d);return;
				if (d.code === 200) {
					d.data.map((v,i) => {
						h += `<tr>
										<td>${v.CAR_BRAND}</td>
										<td>${v.CAR_MODEL}</td>
										<td>${v.CAR_TYP}</td>
										<td>${v.CAR_BODY}</td>
										<td>${v.CAR_OF_YEAR}</td>
										<td>${v.CAR_TO_YEAR}</td>
										<td>${v.CAR_KW}</td>
										<td>${v.CAR_PM}</td>
										<td>${v.CAR_CC}</td>
									</tr>`;
					});
				}
				if (h) {
					$slc.removeClass("load").find("tbody").html(h);
				}else{
					$slc.removeClass("load");
					table.addClass("d-none").after(no_data)
				}
			},
			error: function(d){
				console.error(d)
				prodCompCars = false;
				$slc.removeClass("load");
				table.addClass("d-none").after(no_data)
			},
			complete: function(){}
		})
	}

	let getBrandList = (data) => {
		if(brandsLoaded) return;
		brandsLoaded = true;
		$.get({
			url: `/brand/list-live`,headers: hdKey,data,
			success: function(d){
				let h_home = "";
				let h = d.code === 200 ? d.data.map(v => brandComponent(v.image,v.name,v.id)).join("") : "";
				d.code === 200 ? d.data.map((v,i) => {
					h_home += i < 10 ? `<a href="${path_local(`brand/${slugify(v.name) + '-' + v.id}`)}" class="brand-card card-img"><img src="${v.image && v.image.small ? v.image.small : "/assets/landing/img/no_photo.png"}" alt="${v.name}" loading="lazy"></a>` : ""
				}) : "";
				// `<img src="${v.image && v.image[0] ? v.image[0].small : "/assets/landing/img/no_photo.png"}" alt="${v.name}" loading="lazy">`
				$("#brand_container").html(h).removeClass("load");
				// .find(".load").html(h).addClass("owl-carousel").removeClass("fake-card-line load")
				$(`#home_brands [data-role="home-br-list"]`).html(h_home).addClass("owl-carousel").removeClass("fake-card-line load")
				.owlCarousel({
					items: 6,
					// loop: true,
					margin: 30,
					dots: true,
					dotsEach: 1,
					autoplay: true,
					autoplayTimeout: 3000,
					responsive: {
						768: {
							items: 6,
						},

						0: {
							items: 2,
						}
					}
				});

			},error: function(d){
				console.error(d);
				brandsLoaded = false;
			}
		});
	}

	let getCertificate = (data) => {
		$.get({
			url: `/certificates/list-live`,data,headers: hdKey,
			success: function(d){
				let h = d.code === 200 ? d.data.map(v => certificateComponent(v.files,v.name)).join("") : "";
				$(`#certificate_list`).html(h).removeClass("load");
				$('.fancybox').fancybox();
			},error: function(d){
				console.error(d);
			}
		});
	}

	$("#certificate_list").length ? getCertificate() : "";

	if ($("#news_list").length) {
		let newsCount = 0,newCurrentPage = $_get("page") || 1;
		let getNewsList = (page) => {
			$.get({
				url: `/news/list-live`,data: {page},
				headers: hdKey,
				success: function(d){
					let h = d.code === 200 ? d.data.list.map(v => newsComponent(v.title,v.images,v.slug,v.details,v.date)).join(" ") : "";
					newsCount = Math.ceil(d.data.count / 5);
					paginateController(newsCount,null,page)
					$("#news_list").html(h).removeClass("load");
					if (parseInt(page) !== 1) {
						$('html, body').animate({
							scrollTop: $("#news_list").offset().top - 130
						}, 'slow')
					}
				},error: function(d){
					console.error(d)
				}
			});
		}


		$db.on("click", `[data-role="pagination"] a:not([disabled])`, function () {
			newCurrentPage = paginateController(newsCount,$(this));
			filter_url([
				{
					page: newCurrentPage + ""
				}
			]);
			location.reload()
		});
		getNewsList(newCurrentPage);
	}

	if ($("#promotions_list").length) {
		let promotionsCount = 0,prmPage = $_get("page") || 1;
		let getPromotionsList = (page) => {
			$.get({
				url: `/promotion/list-live`,data: {page},
				headers: hdKey,
				success: function(d){
					if (parseInt(page) !== 1 && d.code === 200) window.scroll(0, 0);
					let h = d.code === 200 ? d.data.list.map(v => promotionComponent(v.id, v.slug, v.title, v.images, v.details)).join(" ") : "";
					$("#promotions_list").html(h).removeClass("load");
					promotionsCount = Math.ceil(d.data.count / 5);
					paginateController(promotionsCount,null,page)
				},
				error: function(d){}
			});
		}
		$db.on("click", `[data-role="pagination"] a:not([disabled])`, function () {
			prmPage = paginateController(promotionsCount,$(this));
			filter_url([
				{page: prmPage + ""}
			]);
			location.reload()
		});
		getPromotionsList(prmPage);
	}

	if ($("#crossreference_list").length) {
		let isInView = Screen.view($('#crossreference_list'), false);
		isInView ? getCrossReference(prod_br_code) : "";
		$db.on("click",`#cross-reference-tab`,function(){
			getCrossReference(prod_br_code);
		})
		$(window).scroll(function (event) {
				Screen.view($('#crossreference_list'), false) ? getCrossReference(prod_br_code) : ""
		});
	}

	$(document).on("click",`[data-bs-target="#CrossReferenceMobileTab"]`,function(){
		getCrossReference(prod_br_code,"#CrossReferenceMobileTab");
	});


	// OEM section starts
	if ($("#oems_list").length) {
		let isInView = Screen.view($('#oems_list'), false);
		isInView ? getSimilarOEMs(prod_br_code) : "";
		$db.on("click",`#oem-codes-tab`,function(){
			getSimilarOEMs(prod_br_code);
		})
		$(window).scroll(function (event) {
				Screen.view($('#oems_list'), false) ? getSimilarOEMs(prod_br_code) : ""
		});
	}

	if ($("#compatible_cars_list").length) {
		let isInView = Screen.view($('#compatible_cars_list'), false);
		isInView ? getCompatibleCars(prod_br_code) : "";
		$db.on("click",`#compatible-cars-tab`,function(){
			getCompatibleCars(prod_br_code);
		})
		$(window).scroll(function (event) {
				Screen.view($('#compatible_cars_list'), false) ? getCompatibleCars(prod_br_code) : ""
		});
	}

	$(document).on("click",`[data-bs-target="#oemListMobile"]`,function(){
		getSimilarOEMs(prod_br_code,"#oemListMobile");
	});

	$(document).on("click",`[data-bs-target="#compatibleCars"]`,function(){
		getCompatibleCars(prod_br_code,"#compatibleCars");
	});

	$db.on("click",`#oem-codes-tab`,function(){
		getSimilarOEMs(prod_br_code);
	})

	// OEM section ends


	if ($("#productDescription").length) {
		let isInView = Screen.view($('#productDescription'), false);
		isInView ? getProductDetails(prod_br_code) : "";
		$(window).scroll(function (event) {
				Screen.view($('#productDescription'), false) ? getProductDetails(prod_br_code) : ""
		});
	}

	$(document).on("click",`[data-bs-target="#prodDetailsTab"]`,function(){
		getProductDetails(prod_br_code,"#prodDetailsTab")
	});

	if ($("#similarProducts").length) {
		let isInView = Screen.view($('#similarProducts'), false);
		isInView ? getSimilars($(`#productDetail`).data("product-slug")) : "";
		$(window).scroll(function (event) {
				let isInView = Screen.view($('#similarProducts'), false);
				isInView ? getSimilars($(`#productDetail`).data("product-slug")) : ""
		});
	}

	if ($("#productDetail").length && window.history && window.history.pushState) {
  	$(window).on('popstate', function() {
			let tab = getUrlParameter("tab");
			$("#nav-tab").find(`#${tab}-tab`).addClass("active").siblings().removeClass("active")
			$(`#${tab}`).addClass("show active").siblings().removeClass("show active")
			if (tab === "more-details") {
				getProductDetails(prod_br_code)
			}else if(tab === "cross-reference"){
				getCrossReference(prod_br_code);
			}
  	});
	}

	if ($("#brand_container").length) {
		 Screen.view($('#brand_container'), false) ? getBrandList() : "";
		$(window).scroll(function (event) {
				Screen.view($('#brand_container'), false) ? getBrandList() : ""
		});
	}

	if ($("#home_brands").length) {
		 Screen.view($('#home_brands'), false) ? getBrandList({limit: 36}) : "";
		$(window).scroll(function (event) {
				Screen.view($('#home_brands'), false) ? getBrandList({limit: 36}) : ""
		});
	}

	$db.on('change', `input[type="checkbox"]`, function () {
		$(this).val() == 0 ? $(this).val(1) : $(this).val(0);
	});

	$db.on("change", `input[type='number']`, function () {
		if ($(this).attr('max')) {
			let max = parseInt($(this).attr('max'));
			$(this).val() > max ? $(this).val(max) : "";
		}
		if ($(this).attr('min')) {
			let min = parseInt($(this).attr('min'));
			$(this).val() < min ? $(this).val(min) : "";
		}
	});

	let getHomeProductsLoaded = false;
	let getHomeProducts = () => {
		if(getHomeProductsLoaded) return;
		getHomeProductsLoaded = true;
		$.get({
			url: `/product/home-products`,
			headers: hdKey,cache: true,
			// data: {limit: 8},
			success: function (d) {
				if (Array.isArray(d) && d.length) {
					let h = d.map(v => productComponent(v.id, v.slug, v.prod_name, v.brand,v.image)).join(" ");
					$("#home_pg_products > div > div").html(h).removeClass("fake-card-line").addClass("owl-carousel owl-theme mt-3");
					$("#home_pg_products").removeClass("load")
					startProductOwlCarusel("#home_pg_products > div > div");
				}else{
					$("#home_pg_products > div > div").html("").parents("#home_pg_products").addClass("d-none")
				}
			},
			error: function (d) {console.error(d)},
			complete: function () {
				$ld.addClass("d-none");
			}
		});
	}


	if ($("#home_pg_products").length) {
		let isInView = Screen.view($('#home_pg_products'), false);
		isInView ? getHomeProducts() : "";
		$(window).scroll(function (event) {
				let isInView = Screen.view($('#home_pg_products'), false);
				isInView ? getHomeProducts() : ""
		});
	}


	$("#brand_list").length ? $(".overlay-filter").addClass("d-none") : "";

	$db.on("click", `a[data-target="#viewImage"]`, function () {
		$("#viewImage .modal-body img").attr("src", $(this).data("big-img"));
	})

	$db.on("click", `#nav-tab .nav-item`, function () {
		filter_url([{action: getUrlParameter('action')},{tab: $(this).attr("href").replace("#", "")}]);
	});
	let $imgs = $("img[data-src]");
	for (var i = 0; i < $imgs.length; i++) {
		$imgs.eq(i).attr("src", $imgs.eq(i).data("src"))
	}


	$("link[href$='bootstrap-select.css']").length ? $('.multiple-select').selectpicker() : ""


	$db.on("click", `.edit-by-lang a[data-toggle="tab"]`, function () {
		let t = $(this);
		let id = t.attr("href").replace("#", "");
		let lang = id.replace("tab_", "");
		t.addClass("active").siblings().removeClass("active")
		$(".tab-content #" + id).addClass("active show").siblings("div").removeClass("active show");
		$(`input[name='language']`).val(lang);
		filter_url([{
			language: lang
		}]);
	});
});
