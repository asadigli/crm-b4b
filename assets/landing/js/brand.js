import {
	l,paginateController,getUrlParameter,
	filter_url,$_get,$db,hdKey,pg_lang,path_local
} from './parts.min.js?v=1abc';
import {
	productComponent
} from './components.min.js?v=1';

import {
	Screen
} from './current_screen.min.js?v=1';

$(function () {


	if ($(`[data-role="model-pg-list"]`).length) {
		let modelYear = getUrlParameter("model-year") || "";

		let getModelYears = (year) => {
			$(`input,select,textarea`).attr("disabled",true).addClass("disabled-manually");
			let $bd = $(`[data-role="brand-years"]`);
			let brand = $(`[data-role="model-pg-list"]`).data("brand-id");
			$.ajax({
				url: `/get-car-years`,
				type: "GET",data:{brand},
				headers: hdKey,
				success: function(d){
					// console.log(d);
					let h =	Array.isArray(d) ? `<option value="">- ${l("Choose year")} -</option>${d.map((v,i) => `<option value="${v}"${+year === +v ? " selected" : ""}>${v}</option>`).join("")}` : "";
					$bd.html(h);
					$bd.removeClass("load")
				},error: function(){
					console.error(d)
				},complete: function(){
					$(`.disabled-manually`).attr("disabled",false).removeClass("disabled-manually");
				}
			})
		}

		let getModels = (year) => {
			let $bd = $(`[data-role="model-pg-list"]`);
			let brand = $bd.data("brand-id");
			$.ajax({
				url: `/get-car-models`,
				type: "GET",data:{brand,year},
				headers: hdKey,
				success: function(d){
					let h = ["","",""],
							len = d.length;
					// console.log(d);
					// data-role="brand-years"
					if (Array.isArray(d) ) {
						let part = Math.ceil(len/3);
						d.map((v,i) => {
							h[(i < part ? 0 : (i < part*2 ? 1 : 2))] += `<li>
														<a href="${path_local(`brand/${brand}/models/${v.id}/engine`)}">
															<em class="fas fa-sort-up"></em>
															<p>${v.name}</p>
														</a>
													</li>`;

						});
					}
					for (var i = 0; i < 3; i++) {
						$bd.find(".list-line").eq(i).find("ul").html(h[i]);
					}
					$bd.removeClass("load")
				},error: function(){
					console.error(d)
				},complete: function(){

				}
			})
		}

		getModelYears(modelYear);
		getModels(modelYear);

		$db.on("change",`[data-role="brand-years"]`,function(){
			modelYear = $(this).val();
			filter_url([{'model-year': modelYear}])
			getModels(modelYear);
		})
	}else if($(`[data-role="engine-pg-list"]`).length){
		let engineYear = getUrlParameter("engine-year") || "";

		let getEngines = (year) => {
			let $bd = $(`[data-role="engine-pg-list"]`);
			let brand = $bd.data("brand-id");
			let model = $bd.data("model-id");
			$.ajax({
				url: `/get-car-engine`,headers: hdKey,
				type: "GET",data:{model,year},
				success: function(d){
					let h = ["","",""],len = d.length;
					if (Array.isArray(d) ) {
						let part = Math.ceil(len/3);
						d.map((v,i) => {
							h[(i < part ? 0 : (i < part*2 ? 1 : 2))] += `<li>
														<a href="${path_local(`brand/${brand}/models/${model}/engine/${v.id}/cataloge`)}">
															<em class="fas fa-sort-up"></em>
															<p>${v.name} - ${v.ban} (${v.car_pm})</p>
														</a>
													</li>`;

						});
					}
					for (var i = 0; i < 3; i++) {
						$bd.find(".list-line").eq(i).find("ul").html(h[i]);
					}
					$bd.removeClass("load")
				},error: function(){
					console.error(d)
				},complete: function(){

				}
			})
		}


		getEngines(engineYear);

	}
	// else if($(`[data-role="pg-cataloge"]`).length) {
	// 	$db.on("click",`[data-role="pg-cataloge"] [data-role="cataloge-item"]`,function(){
	// 		console.log($(this).data("id"),$(`[data-role="pg-cataloge"]`).data("engine-id"))
	// 		location.href = `/${pg_lang === "az" ? "" : pg_lang}cataloge/products/${$(this).data("id")}/${$(`[data-role="pg-cataloge"]`).data("engine-id")}`
	// 	})
	// }else if($("#search_products").length){
	// 	$db.on("click",`[data-role="filter-cataloge"] [data-role="cataloge-item"]`,function(){
	// 		let engine = $("#search_products").data("engine-id"),
	// 				category = $(this).data("id");
	// 		location.href = `/${pg_lang === "az" ? "" : `${pg_lang}/`}cataloge/products/${category}/${engine}`
	// 	})
	//
	// }



});
