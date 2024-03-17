$(function () {

  //#region Close element on click out
	let container = $(".user-box, .nav-item, .custom-tab-sidebar-list-item");

	$(document).click(function (e) {
		e.stopPropagation();
		if (container.has(e.target).length === 0) {
			closeItems();
		}
	});

	$(window).scroll(function () {
		closeItems();
	});

	function closeItems() {
    $(".user-box-dropdown").removeClass("d-block");
    $("ul.sm-nowrap").removeClass("d-block");
    // $(".custom-dropdown").removeClass("d-block");
	}
	//#endregion

  $(document).on("scroll", function () {
    if ($(document).scrollTop() > 50) {
      $('body').addClass('header-fixed');
    } else {
      $('body').removeClass('header-fixed');
    }
  });

  // $(".numeric").inputmask('9');
  $(document.body).on(`keyup`, `.numeric`, function (e) {
    let t = $(this);
    let next_in = t.next('input'),
      prev_in = t.prev('input');
    if (t.val().length) {
      t.blur();
      next_in.attr('disabled', false);
      next_in.focus();
    }

    if (e.keyCode == 8) {
      t.val('').blur();
      prev_in.attr('disabled', false);
      prev_in.focus();
    }
  });

  // $(".custom-select").select2({
  //   minimumResultsForSearch: 15
  // });

  // $(document).on("click", "table tr", function(){
  //     $(this).addClass("tr-focused");
  //     $("table tr").not($(this)).removeClass("tr-focused");
  // });


  let ft = 'focused-tr';

  $(document).on("click", "tr", function () {
    $(this).trigger("trClassChange");
    $(this).find("td").length
      ? $(this).hasClass(ft)
        ? $(this).removeClass(ft)
        : $(this).addClass(ft).siblings().removeClass(ft)
      : "";
  });

  $(document).on("keyup", "body", function (e) {
    let thtt = ".table tbody tr";
    if ($("." + ft).length) {
      if (!$("body").hasClass("modal-open")) {
        if (e.keyCode == 38) {
          if ($("." + ft).prev("tr").length) {
            let index = $(".table tbody ." + ft).index();
            $(thtt + ":eq(" + (index - 1) + ")").addClass(ft).trigger("trClassChange");
            $(thtt + ":eq(" + index + ")").removeClass(ft);
            $("html, body").animate(
              {
                scrollTop: $("." + ft).position().top,
              },
              0
            );
          }
        }
        if (e.keyCode == 40) {
          if ($("." + ft).next("tr").length) {
            let index = $(".table tbody ." + ft).index();
            $(thtt + ":eq(" + (index + 1) + ")").addClass(ft).trigger("trClassChange");
            $(thtt + ":eq(" + index + ")").removeClass(ft);
            $("html, body").animate(
              {
                scrollTop: $("." + ft).position().top,
              },
              0
            );
          }
        }
      }
    }
  });

  $('[data-toggle="tooltip"]').click(function () {
    $('[data-toggle="tooltip"]').tooltip("hide");
  });

  $(document).on("click", ".sm .nav-item", function () {
    $(this).find(".sm-nowrap").toggleClass("d-block");
    $(".sm-nowrap").not($(this).find(".sm-nowrap")).removeClass("d-block");
  });

  $(document).on("click", ".scroll-top", function () {
		$(window).scrollTop(0);
	});

	$(document).scroll(function () {
		if ($(document).scrollTop() > 400) {
			$(".scroll-top").addClass("d-block");
		} else {
			$(".scroll-top").removeClass("d-block");
		}
	});

	if ($(document).scrollTop() > 700) {
		$(".scroll-top").addClass("d-block");
	} else {
		$(".scroll-top").removeClass("d-block");
	};

  function menuToggle(){
    if($("#main-menu-state").is(":checked")){
      $(`[data-role="navigation"]`).removeClass("d-none");
      // $("#main-menu-state").prop("checked", false);
    }else{
      $(`[data-role="navigation"]`).addClass("d-none");
      // $("#main-menu-state").prop("checked", true);
    }
  }

  // data-role="navigation"
  $(document).on("change", "#main-menu-state", function(){
    $("body").toggleClass("overflow-hidden");
    menuToggle();
  });

  // $(document).on("scroll", function(){
  //   $(`[data-role="navigation"]`).addClass("d-none");
  //   $("#main-menu-state").prop("checked", false);
  // });

  $(document).on("click", ".user-box-main-side", function(){
    $(".user-box .user-box-dropdown").toggleClass("d-block");
  });

  $(document).on("click", ".custom-dropdown-box button", function(){
    $(this).next().toggleClass("d-block");
    $(".custom-dropdown").not($(this).next()).removeClass("d-block");
  });

  $(document.body).on("click", `[data-role="add-to-cart"]`, function(){
    $(this).toggleClass("selected");
  });

  $(document.body).on("click", ".minus", function () {
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.change();
    return false;
  });
  $(document.body).on("click", ".plus", function () {
    var $input = $(this).parent().find('input');
    $input.val(parseInt($input.val()) + 1);
    $input.change();
    return false;
  });
});