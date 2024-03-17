import {
	number_format,
	path_local,slugify,
	l,stripHtml,
	str_limit,
	get_date
} from './parts.min.js?v=1.0.1';


export const filterCheckboxSection = (id,name,brand) => {
	return (`<label class="chck">
						<input type="checkbox" data-val="${id}" ${brand && brand.split(",").includes(id) ? " checked" : ""}>
						<span class="checkmark"></span>
						<p data-role="title">${name}</p>
					</label>`);
}

export const productComponent = (key, slug, full_name, brand, image) => {
	let img = image && image[0] ? image[0].small : "/assets/landing/img/no_photo.png";

	return (`<div class="product-card" data-id="${key}">
						<a href="${path_local(`product/` + slug)}" class="product-card-top" title=${full_name}>
							<img src=${img} alt="${full_name}">
						</a>
						<div class="product-card-body">
							<a href="${path_local(`product/` + slug)}" title=${full_name}>
								<h4>${full_name}</h4>
							</a>
							<p>${brand || ""}</p>
						</div>
					</div>`);
}


export const newsComponent = (title,image,slug,details,date) => {
	let img = image && image[0] ? image[0].small : "/assets/landing/img/no_photo.png";
	return (`<div class="card-line">
			<div class="left-side">
				<img src="${img}" alt="">
			</div>
			<div class="right-side">
				<div class="content">
					<h4><a href="${path_local(`news/` + slug)}">${title}<a></h4>
					<p>${details ? str_limit(stripHtml(details),350) : ""}</p>
				</div>
				<div class="d-flex justify-content-between">
					<div class="date-card">
						<img src="/assets/landing/img/icons/png calendar.png" alt="">
						<span>${date}</span>
					</div>
					<a class="card-line-a" href="${path_local(`news/` + slug)}">
						<p>${l('More')}</p>
						<svg version="1.1" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
							xml:space="preserve">
							<g>
								<g>
									<path
										d="M506.134,241.843c-0.006-0.006-0.011-0.013-0.018-0.019l-104.504-104c-7.829-7.791-20.492-7.762-28.285,0.068
			                                c-7.792,7.829-7.762,20.492,0.067,28.284L443.558,236H20c-11.046,0-20,8.954-20,20c0,11.046,8.954,20,20,20h423.557
			                                l-70.162,69.824c-7.829,7.792-7.859,20.455-0.067,28.284c7.793,7.831,20.457,7.858,28.285,0.068l104.504-104
			                                c0.006-0.006,0.011-0.013,0.018-0.019C513.968,262.339,513.943,249.635,506.134,241.843z" />
								</g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
						</svg>
					</a>
				</div>
			</div>
		</div>`);
}


export const promotionComponent = (key, slug, full_name, image, description) => {
	let img = image && image[0] ? image[0].small : "/assets/landing/img/no_photo.png";
	return (`<div class="card-line">
			<div class="left-side">
					<img src="${img}" alt="${full_name}">
			</div>
			<div class="right-side">
				<div class="content">
					<h4><a href="${path_local(`promotion/${slug}`)}">${full_name ? str_limit(full_name,300) : ""}</a></h4>
					<p>${description ? str_limit(description,350) : ""}</p>
				</div>
				<div class="d-flex justify-content-end">
					<a class="card-line-a" href="${path_local(`promotion/${slug}`)}" title="${full_name}">
						<p>${l('More')}</p>
						<svg version="1.1" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
							<g>
								<g>
									<path
										d="M506.134,241.843c-0.006-0.006-0.011-0.013-0.018-0.019l-104.504-104c-7.829-7.791-20.492-7.762-28.285,0.068
			                                c-7.792,7.829-7.762,20.492,0.067,28.284L443.558,236H20c-11.046,0-20,8.954-20,20c0,11.046,8.954,20,20,20h423.557
			                                l-70.162,69.824c-7.829,7.792-7.859,20.455-0.067,28.284c7.793,7.831,20.457,7.858,28.285,0.068l104.504-104
			                                c0.006-0.006,0.011-0.013,0.018-0.019C513.968,262.339,513.943,249.635,506.134,241.843z" />
								</g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
							<g>
							</g>
						</svg>
					</a>
				</div>
			</div>
		</div>`);
}


export const brandComponent = (image, name , slug) => {
	let img = image && image.small ? image.small : "/assets/landing/img/no_photo.png";
	return (`<div class="col-lg-2 col-md-3 col-6">
							<a href="${path_local(`brand/${slugify(name) + '-' + slug}`)}" class="card-img" title="${name}">
								<div class="overlay">
									<p>${name}</p>
								</div>
								<img src="${img}" alt="${name}" loading="lazy">
							</a>
					</div>`);
}

export const certificateComponent = (image, name) => {
	let img = image && image[0].large ? image[0].large : "/assets/landing/img/no_photo.png",
	 		img_large = image && image[0].large ? image[0].large : "/assets/landing/img/no_photo.png";
	return (`<div class="col-md-3 col-6 d-flex">
		<div class="card-img-2">
			<img src="${img}" alt="">
			<div class="overlay-hover">
				<a href="${img_large}" class="svg-cont" data-fancybox="certf">
					<svg version="1.1" viewBox="0 0 513.28 513.28"
						style="enable-background:new 0 0 513.28 513.28;" xml:space="preserve">
						<g>
							<g>
								<path d="M495.04,404.48L410.56,320c15.36-30.72,25.6-66.56,25.6-102.4C436.16,97.28,338.88,0,218.56,0S0.96,97.28,0.96,217.6
																	s97.28,217.6,217.6,217.6c35.84,0,71.68-10.24,102.4-25.6l84.48,84.48c25.6,25.6,64,25.6,89.6,0
																	C518.08,468.48,518.08,430.08,495.04,404.48z M218.56,384c-92.16,0-166.4-74.24-166.4-166.4S126.4,51.2,218.56,51.2
																	s166.4,74.24,166.4,166.4S310.72,384,218.56,384z" />
							</g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
						<g>
						</g>
					</svg>
				</a>
			</div>
		</div>
		</div>`);
}

// old version

// export const changeQuantityComponent = (default_val, cls) => {
// 	return (`<div class="quantity-change-group">
//             <input type="button" value="-" class="quantity-change-minus" data-field="quantity">
//             <input type="number" step="1" max="" value="${default_val ? default_val : 1}" name="quantity" class="quantity-field${cls ? ' ' + cls : ''}">
//             <input type="button" value="+" class="quantity-change-plus" data-field="quantity">
//           </div>`);
// }
//
// export const newsComponent = (title,image,slug) => {
// 	let img = image !== "no_photo.png" ? image : "/assets/landing/img/no_photo.png";
// 	return (`<div class="col-md-4 col-12">
// 						<div class="d-i px-0 pt-0">
// 						<div class="img-d-i border-bottom">
// 						<img src="${img}">
// 						</div>
// 						<a href="/news/${slug}">${title}</a>
// 						</div>
// 					</div>`);
// }
//
// export const reviewComponent = (rating, comment, date, user_name) => {
// 	return (`<div class="comment-line">
// 						<div class="tp-lne">
// 							<ul id='stars'>
// 								<li class='star${rating > 0 ? ' selected' : ''}' title='${l('Poor')}' data-value='1'>
// 									<i class='fa fa-star fa-fw'></i>
// 								</li>
// 								<li class='star${rating > 1 ? ' selected' : ''}' title='${l('Fair')}' data-value='2'>
// 									<i class='fa fa-star fa-fw'></i>
// 								</li>
// 								<li class='star${rating > 2 ? ' selected' : ''}' title='${l('Good')}' data-value='3'>
// 									<i class='fa fa-star fa-fw'></i>
// 								</li>
// 								<li class='star${rating > 3 ? ' selected' : ''}' title='${l('Very_good')}' data-value='4'>
// 									<i class='fa fa-star fa-fw'></i>
// 								</li>
// 								<li class='star${rating > 4 ? ' selected' : ''}' title='${l('Excellent')}' data-value='5'>
// 									<i class='fa fa-star fa-fw'></i>
// 								</li>
// 							</ul>
// 							<p>${comment}</p>
// 						</div>
// 						<div class="bt-lne d-flex align-items-center justify-content-between">
// 							<div class="prsn-dte d-flex align-items-center">
// 								${user_name ? `<p class="m-0 pr-2 border-right">${user_name}</p>` : ''}
// 								<p class="m-0 pl-2">${date ? get_date(date) : '--/--/--'}</p>
// 							</div>
//
// 						</div>
// 					</div>`);
// }
//
// export const storeComponent = (id,image,name,city,slug,carbrands) => {
// 	let def = image === "store-default-avatar.png";
// 	let img = def ? "/assets/landing/img/store-default-avatar.png" : image;
// 	carbrands = carbrands ? (Array.isArray(carbrands) ? carbrands : carbrands.split(",")) : "";
// 	return (`<div class="col-lg-4 col-md-6 col-12 mb-4" data-id="${id}">
// 					<a href="/store/${slug}" class="d h-100 cs_product_pan card_tb_des" data-role="store-d">
// 						<div class="cs_product_card_link_main text-center${def ? " default" : ""}">
// 							<img class="cs_product_gallery_img bg-light" src="${img}"
// 								alt="${name}" title="${name}">
// 						</div>
// 						<div class="d-body pt-0 pb-3 px-3 d-flex flex-column justify-content-between">
// 							<h4 class="d-title mb-0 mt-3">
// 								${name}
// 							</h4>
// 							<div class="d-flex flex-wrap align-items-center">
// 							${carbrands ? carbrands.map((brand,i) => {return i < 3 ? `<span class="product-d-brand-tag">${str_limit(brand,15)}</span>` : '';}).join(" ") : ''}
// 							</div>
// 							<div class="row d-flex align-items-center">
// 								<div class="col-6 cs_card_price">
// 									<span><em class="fa fa-map-marker-alt"></em> ${city}</span>
// 								</div>
// 							</div>
// 						</div>
// 					</a>
// 				</div>`);
// }
//
// export const productComponent = (key, slug, full_name, brand, price, currency, cls, cart,wishlist,image) => {
// 	let img = image ? image : '/assets/landing/img/no_photo.png',cls_img = image ? '' : ' default';
// 	return (`<div class="${cls ? cls : "col-lg-3 col-md-4 col-12"} cs_product_card cs_product_main" data-id="${key}">
// 			  <div class="d h-100 cs_product_pan load">
// 				 	<div class="st-new">
// 				 	 	<label class="product-hearth">
// 					  		<input class="like-button" type="checkbox"${cart ? " checked" : ""} data-role="add_to_wishlist">
// 					  		<svg width="255" height="240" viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
// 			  		</label>
// 		  		</div>
// 					<div class="cs_product_card_link_main text-center${cls_img}">
// 						<img class="cs_product_gallery_img" src="${img}" alt="${full_name}" title="${full_name}">
// 					</div>
// 					<div class="d-body pt-0 pb-3 px-3">
// 				  <h4 class="d-title mb-0 mt-3">
// 						<a href="/product/${slug}" title="${full_name}">
// 						  	${full_name ? str_limit(full_name,60) : ''}
// 						</a>
// 				  </h4>
// 					<div class="row d-flex align-items-center">
// 				  	<div class="col-12">
// 					  ${brand ? `<span class="product-d-brand-tag">${brand}</span>` : ''}
// 					</div>
// 					<div class="col-6 cs_card_price">
// 						<span>
// 							${price && !isNaN(price) ? number_format(price,2,'.','') + ' ' + currency : "--"}
// 						</span>
// 					</div>
// 					<div class="col-6 d-flex justify-content-end">
// 						<label class="product-bag">
// 						<input class="like-button" type="checkbox"${cart ? " checked" : ""} data-role="add_to_cart">
// 											<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
// 												y="0px" viewBox="0 0 279 279" style="enable-background:new 0 0 279 279;" xml:space="preserve">
// 												<path d="M262.421,270.339L246.466,72.896C246.151,69.001,242.898,66,238.99,66h-42.833v-9.495C196.157,25.348,171.143,0,139.985,0
// 												h-0.99c-31.157,0-56.838,25.348-56.838,56.505V66H39.99c-3.908,0-7.161,3.001-7.476,6.896l-16,198
// 												c-0.169,2.088,0.543,4.15,1.963,5.689S21.897,279,23.99,279h231c0.006,0,0.014,0,0.02,0c4.143,0,7.5-3.357,7.5-7.5
// 												C262.51,271.105,262.48,270.717,262.421,270.339z M97.157,56.505C97.157,33.619,116.109,15,138.995,15h0.99
// 												c22.886,0,41.172,18.619,41.172,41.505V66h-84V56.505z" />
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 											</svg>
// 										</label>
//                     </div>
//                   </div>
//                 </div>
//               </div>
//             </div>`);
// 	// <div class="col-12">
// 	// 	<p class="date-d">Bugün, 19:35</p>
// 	// </div>
// 	// <div class="customer-reviews mt-1">
// 	// 	<div class="tp-lne d-flex align-items-center">
// 	// 		<ul id="stars">
// 	// 			<li class="star selected" title="Pis" data-value="1">
// 	// 				<i class="fa fa-star fa-fw"></i>
// 	// 			</li>
// 	// 		</ul>
// 	// 		<span class="stars-count">3.1</span>
// 	// 	</div>
// 	// </div>
// }
//
// export const productListComponent = (key, name, count, link, img) => {
// 	return (`<div class="col-lg-3 col-md-6 col-12 cs_product_card cs_product_main" data-id="${key}">
//               <div class="d h-100 pt-3 cs_product_pan">
//                 <div class="cs_product_card_link_main text-center">
//                   <img class="cs_product_gallery_img" src="${img ? img : environment+'assets/landing/img/product-1.jpg'}" alt="${name}">
//                 </div>
//                 <div class="d-body">
//                   <h4 class="d-title"><a ${link}>${name} (${count})</a></h4>
//                 <div class="col-md-12 cs_quick_col mt-3">
//                   <a href="javascript:void(0)" ${link} class="btn btn-default btn-sm btn-block">
//                     <i class="fa fa-eye" aria-hidden="true"></i> ${l('See_list')}
//                   </a>
//                 </div>
//               </div>
//             </div>
//           </div>
//         </div>`);
// }
//
// export const cartProductComponent = (name, key, slug, code, price, quantity, date, image) => {
// 	let img = image ? image : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJd7Mf_1Z0fInB_A1UJ0Fekw6BsECtInGW5g&amp;usqp=CAU',
// 		prc = price ? number_format(price, 2, '.', '') + ' AZN' : '---';
// 	return (`<div class="line line-d-product" data-date="${date}" data-id="${key}">
// 						<div class="left-side-product">
// 							<img src="${img}" alt="${name}">
// 						</div>
// 						<div class="right-side-product pl-3">
// 							<div class="line-inner border-bottom pb-2">
// 								<h6><a href="/product/${slug}" title="${name}">${str_limit(name,30)}</a></h6>
// 								<i class="fas fa-trash"></i>
// 							</div>
// 							<div class="line-inner">
// 								<p>${l('Product_code')}</p><p>${code}</p>
// 							</div>
// 							<div class="line-inner">
// 								<p>${l('Price')}</p>
// 								<strong>${prc}</strong>
// 							</div>
// 							<div class="line-inner">
// 								<p>${l('Quantity')}</p>
// 								<!-- <div class="tb_input-group">
// 									${changeQuantityComponent(quantity,'wquant')}
// 								</div> -->
// 								<div class="number">
// 									<span class="minus">-</span>
// 									<input type="text" value="1" disabled="">
// 									<span class="plus">+</span>
// 								</div>
// 							</div>
// 						</div>
// 					</div>`);
// }
//
// export const cartHeaderProductComponent = (name, key, slug, code, price, quantity, date, image) => {
// 	let img = image ? image : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJd7Mf_1Z0fInB_A1UJ0Fekw6BsECtInGW5g&amp;usqp=CAU',
// 		prc = price ? number_format(price, 2, '.', '') + ' AZN' : '---';
// 	return (`<div class="line border-0" data-date="${date}" data-id="${key}">
// 						<div class="left-side-product">
// 							<img src="${img}" alt="${name}">
// 						</div>
// 						<div class="right-side-product pl-3">
// 							<h6>${str_limit(name,30)}</h6>
// 							<div class="line-inner">
// 								<p>${l('Price')}:</p>
// 								<p>${prc}</p>
// 							</div>
// 							<div class="line-inner">
// 								<p>${l('Product_code')}:</p>
// 								<p>${code}</p>
// 							</div>
// 							<div class="line-inner">
// 								<p></p>
// 								<div class="tb_input-group" style="width:45px">
// 									<input type="number" class="price-input w-100" value="${quantity}" placeholder="1">
// 								</div>
// 							</div>
// 						</div>
// 					</div>`);
// }
//
// export const wishlistProductComponent = (name, key, slug, code, price, quantity, date, image) => {
// 	let img = image ? image : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJd7Mf_1Z0fInB_A1UJ0Fekw6BsECtInGW5g&amp;usqp=CAU',
// 		prc = price ? number_format(price, 2, '.', '') + ' AZN' : '---';
// 	return (`<div class="col-lg-3 col-md-6 col-12" data-date="${date}">
// 						<div class="favori-d">
// 							<div class="d-top">
// 								<img src="${img}" alt="${name}">
// 							</div>
// 							<div class="d-bottom">
// 								<div class="d-flex justify-content-between align-items-center mb-2">
// 									<h5 class="mb-0" style=""><a href="/product/${slug}" title="${name}">${name ? str_limit(name,30) : "---"}</a></h5>
// 									<div class="delete-favori-item ml-2">
// 								<svg id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"
// 								xmlns="http://www.w3.org/2000/svg">
// 								<g>
// 								<path
// 								d="m424 64h-88v-16c0-26.467-21.533-48-48-48h-64c-26.467 0-48 21.533-48 48v16h-88c-22.056 0-40 17.944-40 40v56c0 8.836 7.164 16 16 16h8.744l13.823 290.283c1.221 25.636 22.281 45.717 47.945 45.717h242.976c25.665 0 46.725-20.081 47.945-45.717l13.823-290.283h8.744c8.836 0 16-7.164 16-16v-56c0-22.056-17.944-40-40-40zm-216-16c0-8.822 7.178-16 16-16h64c8.822 0 16 7.178 16 16v16h-96zm-128 56c0-4.411 3.589-8 8-8h336c4.411 0 8 3.589 8 8v40c-4.931 0-331.567 0-352 0zm313.469 360.761c-.407 8.545-7.427 15.239-15.981 15.239h-242.976c-8.555 0-15.575-6.694-15.981-15.239l-13.751-288.761h302.44z" />
// 								<path
// 								d="m256 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z" />
// 								<path
// 								d="m336 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z" />
// 								<path
// 								d="m176 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z" />
// 								</g>
// 								</svg>
// 								</div>
// 								</div>
// 								<strong>${prc}</strong>
// 								<div class="d-flex align-items-center justify-content-between">
// 								<div class="number">
// 								<span class="minus">-</span>
// 								<input type="text" value="1" disabled="">
// 								<span class="plus">+</span>
// 							</div>
// 								<label class="product-bag justify-content-start" data-role="add_to_cart">
// 											<input class="like-button" type="checkbox" checked="" name="cart_284810">
// 											<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 279 279" style="enable-background:new 0 0 279 279;" xml:space="preserve">
// 												<path d="M262.421,270.339L246.466,72.896C246.151,69.001,242.898,66,238.99,66h-42.833v-9.495C196.157,25.348,171.143,0,139.985,0
// 												h-0.99c-31.157,0-56.838,25.348-56.838,56.505V66H39.99c-3.908,0-7.161,3.001-7.476,6.896l-16,198
// 												c-0.169,2.088,0.543,4.15,1.963,5.689S21.897,279,23.99,279h231c0.006,0,0.014,0,0.02,0c4.143,0,7.5-3.357,7.5-7.5
// 												C262.51,271.105,262.48,270.717,262.421,270.339z M97.157,56.505C97.157,33.619,116.109,15,138.995,15h0.99
// 												c22.886,0,41.172,18.619,41.172,41.505V66h-84V56.505z"></path>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 												<g>
// 												</g>
// 											</svg>
// 										</label>
// 										</div>
// 							</div>
// 						</div>
// 					</div>`);
// }
