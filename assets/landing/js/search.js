import {
	l,paginateController,getUrlParameter,
	filter_url,$_get,$db,hdKey
} from './parts.min.js?v=1a';
import {
	productComponent,filterCheckboxSection
} from './components.min.js?v=1abc';

import {
	Screen
} from './current_screen.min.js?v=1';

const filterList = (input,list) => {
	$db.on("keyup search",`[data-role="${input}"]`,function(){
		let labels = $(`[data-role="${list}"]`).find("label"),
				val = $(this).val().toLowerCase();
		labels.each(function() {
			let child = $(this).find(`[data-role="title"]`);
			if (child.text().toLowerCase().includes(val)) {
				child.parents("label").removeClass("d-none");
			}else{
				child.parents("label").addClass("d-none");
			}
		})
	});
}

const array_unique = (value, index, self) => {
  return self.indexOf(value) === index;
}

const removeItemAll = (arr, value) => {
  var i = 0;
  while (i < arr.length) {
    if (arr[i] === value) {
      arr.splice(i, 1);
    } else {
      ++i;
    }
  }
  return arr;
}

$(function () {

	let keyword = getUrlParameter("keyword") || "",
			filt_brands = getUrlParameter("brands") || "",
			filt_group = getUrlParameter("group") || "",
			filt_categories = getUrlParameter("categories") || "",
			filt_2nd_categories = getUrlParameter("second-categories") || "",
			page = getUrlParameter("page") || "",
			$main_id = $("#search_products"),
			length_of_pagination = 0;



	let getBrandAndCategories = (group_id) => {
		$("input:not(:disabled),select:not(:disabled)").prop("disabled",true).addClass("fbc_disabled");
		$.get({
			url: `/search/get-brands`,
			data: {group_id},headers: hdKey,
			success: function(d){
				let data = d.data;
				let brands = data.brand ? data.brand.map(v => filterCheckboxSection(v.id,v.name,filt_brands)).join(" ") : "",
						categories = data.category ? data.category.map(v => filterCheckboxSection(v.id,v.name,filt_categories)).join(" ") : "",
						second_categories = data.second_category ? data.second_category.map(v => filterCheckboxSection(v.id,v.name,filt_2nd_categories)).join(" ") : "";
				$(`[data-role="filter-brand-list"]`).html(brands);
				$(`[data-role="filter-category-list"]`).html(categories);
				$(`[data-role="filter-second-category-list"]`).html(second_categories);


				filterList('filter-brand-search','filter-brand-list');
				filterList('filter-category-search','filter-category-list');
				filterList('filter-second-category-search','filter-second-category-list');
				// mb
				filterList('mb-filter-brand-search','mb-filter-brand-list');
				filterList('mb-filter-category-search','mb-filter-category-list');
				filterList('mb-filter-second-category-search','mb-filter-second-category-list');
			},error: function(d){
				console.error(d)
			},complete: function(){
				$("input.fbc_disabled,select.fbc_disabled").prop("disabled",false).removeClass("fbc_disabled");
				$(`[role="product-filter"]`).removeClass("load");
				$(`[data-role="filter-brand-search"],[data-role="filter-category-search"],[data-role="filter-second-category-search"]`).parent().removeClass("load")
			}
		});
	}

	getBrandAndCategories(filt_group);

	$db.on("change",`[name="mb-product-group"],[name="product-group"]`,function(){
		filt_group = $(this).data("val") + "";
		filt_brands = "";
		filt_categories = "";
		filt_2nd_categories = "";
		filter_url([{page},{keyword},{group: (filt_group || "")},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
		getBrandAndCategories(filt_group);
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
	});

	$db.on("click",`[data-role="reset-filter"]`,function(){
		keyword = "";
		filt_group = "";
		filt_brands = "";
		filt_categories = "";
		filt_2nd_categories = "";
		$(`[name="mb-product-group"],[name="product-group"]`).prop("checked",false);
		$(`[name="mb-product-group"]`).eq(0).prop("checked",true);
		$(`[name="product-group"]`).eq(0).prop("checked",true);
		filter_url([{page},{keyword},{group: (filt_group || "")},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
		getBrandAndCategories(filt_group);
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
		$("html, body").animate({
				scrollTop: $main_id.offset().top - 130
		}, "slow")
	});

	let getSearchProducts = (page,filt_data,first_time) => {
		$main_id.addClass('load');
		filt_data.keyword ? $(`[data-role="product-search"]`).val(filt_data.keyword) : "";
		let data = Object.assign(filt_data,{page});
		$(`input:not([disabled])`).addClass("disabled-auto").prop("disabled",true);
		$.get({
			url: `/search/live`,headers: hdKey,
			cache: true,data,
			success: function (d) {
				let h = '',empty_msg = l('No_result_found');
				d.products ? d.products.map(v => {h += `<div class="col-lg-4 col-md-6 col-12">${productComponent(v.id, v.slug, v.product_name, v.brand, v.images)}</div>`;}) : "";
				length_of_pagination = d.count ? Math.ceil(d.count / 12) : 0;
				if (length_of_pagination) {
					$main_id.html(h).removeClass('load');
				} else {
					$main_id.html('');
				}
				if (first_time) {
					page = 1;
					paginateController(length_of_pagination,null,page)
				}else{
					$("html, body").animate({
							scrollTop: $main_id.offset().top - 130
					}, "slow")
				}
				$(".search_count").html(d.count);
			},
			error: function (d) {console.error(d)},
			complete: function () {
				$(`.disabled-auto`).prop("disabled",false).removeClass("disabled-auto");
			}
		});
	}

	getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);

	$db.on("click", `[data-role="pagination"] a:not([disabled])`, function () {
		page = paginateController(length_of_pagination,$(this));
		filter_url([{page: (page + "")},{keyword},{group: filt_group},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories});
	});

	$db.on("keypress", `[data-role="product-search"]`, function (e) {
		if (e.which == 13) {
			keyword = $(this).val();
			page = "";
			filter_url([{page},{keyword},{group: filt_group},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
			getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
		}
	});


	$db.on("click",`[data-role="filter-products"]`,function(){
		page = 1;
		filter_url([{page},{keyword},{group: filt_group},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
		$("html, body").animate({
				scrollTop: $main_id.offset().top - 130
		}, "slow")
	});

	let getListForUrl = (t,list) => {
		let val = t.data("val") + "";
		if (list) {
			list = list + "";
			let new_list = list.split(",");
			if (t.is(":checked")) {
				new_list.push(val)
			}else{
				new_list = filterList(new_list,val)
			}
			new_list = new_list ? new_list.filter(array_unique) : [];
			list = new_list.join(",");
		}else{
			list = t.is(":checked") ? val : "";
		}
		return list;
	}

	$db.on("change",`[data-role="filter-brand-list"] input[type="checkbox"]`,function(){
		filt_brands = getListForUrl($(this),filt_brands);
		page = "";
		filter_url([{page},{keyword},{group: filt_group},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}]);
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
	});


	$db.on("change",`[data-role="filter-category-list"] input[type="checkbox"]`,function(){
		filt_categories = getListForUrl($(this),filt_categories);
		page = "";
		filter_url([{page},{keyword},{group: filt_group},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
	});

	$db.on("change",`[data-role="filter-second-category-list"] input[type="checkbox"]`,function(){
		filt_2nd_categories = getListForUrl($(this),filt_2nd_categories);
		page = "";
		filter_url([{page},{keyword},{group: filt_group},{brands: filt_brands},{categories: filt_categories},{"second-categories": filt_2nd_categories}])
		getSearchProducts(page,{keyword,filt_brands,filt_group,filt_categories,filt_2nd_categories},true);
	});


});
