import { notify_once,l,path_local,filter_url,$_get,$db,getUrlParameter,hdKey as headers } from './../parts.min.js?v=234abc';
import {Loader} from './../loader.min.js?v=2';

$(function() {

	let config = {
		ISVALID_TIMEOUT_TIME: 4000,
		ISVALID_CLASS: "is-valid",
		INVALID_CLASS: "is-invalid"
	}

	let role = getUrlParameter('role') || 'admin';


	let getUsers = (role,txt) => {
		$("body").addClass("loader");
		$.get({
			url: `/admin/users-live`,
			headers,cache:true,
			data: {role},
			success:function(d){
				// console.log(d);
				let h = '';
				if (d.code === 200) {
						d.data.map((v, i) => {
							h += `<tr data-id="${v.id}" data-token="${v.token}" class="userListInput" data-role="user-item">
											<td title="${v.username}">${v.name} ${v.surname}</td>
											<td>
												<div class="form-group">
													<input value="${v.email || ''}" data-type="email" data-previous-value="${v.email}">
												</div>
											<td>
												<div class="form-group">
													<select data-type="gender" data-previous-value="${v.gender}" style="width:auto">
														<option${v.gender === 'male' ? ' selected' : ''}>male</option>
														<option${v.gender === 'female' ? ' selected' : ''}>female</option>
													</select>
												</div>
											</td>
											<td>
												<div class="form-group">
												<select class="form-control" data-type="role" data-previous-value="${v.role}">
														<option${v.role === 'user' ? ' selected' : ''} value="user">user</option>
														<option${v.role === 'admin' ? ' selected' : ''} value="admin">admin</option>
														<option${v.role === 'main_admin' ? ' selected' : ''} value="main_admin">main admin</option>
														<option${v.role === 'developer' ? ' selected' : ''} value="developer">developer</option>
														</select>
												</div>
											</td>
											<td>
												<label class="chck m-0">
													<input type="checkbox" data-role="block-user"${v.blocked === "1" ? " checked" : ""}>
													<span class="checkmark"></span>
												</label>
											</td>
											<td class="tac">
												<button class="btn btn-danger" data-role="deleteUser"><em class="fa fa-trash"></em></button>
												<button class="btn" data-role="save_user_information" disabled>
													<em class="fa fa-save"></em>
												</button>
											</td>
										</tr>`;
										 // data-role="save_user_information"

						});
					}
					$("#usersList").html(h).removeClass("load");
					// $('[data-toggle="tooltip"]').tooltip()
				},complete:function(){
					// txt ? $(".refreshProdList").html(txt) : "";
					$("body").removeClass("loader");
				}
			});
		}
		getUsers(role);



		$(document).on("change",`[data-role="block-user"]`,function(){
			let blocked = $(this).is(":checked") ? "1" : "0";
			$(`input:not([disabled]),select:not([disabled])`).addClass("disabled-auto").prop("disabled",true);
			$.post({
				url: `/admin/user/${$(this).parents("tr").data("id")}/status/update`,data:{blocked},
				headers,
				success: function(d){
					// console.log(d)
					notify_once(d.message,d.code === 200 ? "success" : "warning");
				},
				error: function(d) {
					console.error(d);
				},
				complete: function(d) {
					$(`.disabled-auto`).prop("disabled",false).removeClass("disabled-auto");
				}
			})
		});


		$db.on("input",`.userListInput input,.userListInput select`,function(){
			let t = $(this);
			let val = t.val(),
					prv_val = t.data("previous-value"),
					t_parent = t.parents("tr"),
					all_inputs = t_parent.find(`input,select`),
					boolen_btn = true;
			let btn = t_parent.find(`[data-role="save_user_information"]`);
			val !== prv_val ? t.addClass(config.ISVALID_CLASS) : t.removeClass(config.ISVALID_CLASS);
			for (var i = 0; i < all_inputs.length; i++) {
				boolen_btn *= all_inputs[i].value === all_inputs[i].getAttribute("data-previous-value");
			}
			btn.prop("disabled",boolen_btn);
		});

		$db.on("click",`[data-role="save_user_information"]`,function(e){
			let t_parents = $(this).parents("tr");
			let user = t_parents.data("id");
			if ($(this).is('[disabled]')) {return;}
			Loader.btn({
				element: e.target,
				action: "start"
			});
			let data = {
				email: t_parents.find(`input[data-type='email']`).val(),
				gender: t_parents.find(`select[data-type='gender']`).val(),
				role: t_parents.find(`select[data-type='role']`).val(),
			};

			// console.log(data)
			$.post({
				url: `/admin/user/${user}/update`,
				headers,data,
				success:function(d){
					// console.log(d);
					notify_once(d.message,d.code === 200 ? "success" : "warning");
					if (d.code == 200) {
						getUsers(role);
						Loader.btn({
							element: e.target,
							action: "success"
						});
					}
				},error:function(d){
					console.error(d);
					Loader.btn({
						element: e.target,
						action: "error"
					});
				},complete:function(){
					Loader.btn({
						element: e.target,
						action: "end"
					});
				}
			});
		})


		$(document).on("click",`.userRole button`,function(){
			role = $(this).data("user-role");
			let role_name = $(this).text();
			!$(this).hasClass("active") ? $(this).addClass("active").siblings().removeClass("active") : "";
			filter_url([{role:role}]);
			getUsers(role);
			$(".user-list-title").html(role_name);
		});


		$db.on("click",`[data-role="deleteUser"]`,function(){
			let t = $(this);
			Swal.fire({
				text: "İstifadəçini silməkdə əminsinizmi?",
				showCancelButton: true,
				confirmButtonText: l("Yes"),
				confirmButtonColor: '#d63030',
				cancelButtonText: l("Cancel")
			}).then((res) => {
				if (res.value) {
					$.ajax({
						url: `/admin/user/${t.parents(`tr`).data("id")}/delete`,
						type: "PUT",
						headers,
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
		});




		// add new user
		let isValidParams = {
			email: false,
			phone: true,
			name: false,
			surname: false,
			password: false
		};

		if ($(`#reg_phone`).length) {
			$(`#reg_phone`).inputmask({
				mask: '(99) 999-99-99',
				autoUnmask: true,
				removeMaskOnSubmit: true
			});
		}
		let validateEmail = (email) => {
			const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(String(email).toLowerCase());
		}

		let validateRegBTN = () => {
			// console.log(isValidParams)
			if (isValidParams.email && isValidParams.phone && isValidParams.name && isValidParams.surname && isValidParams.password) {
				$(`[data-role="add-new-user"]`).prop("disabled",false);
			}else{
				$(`[data-role="add-new-user"]`).prop("disabled",true);
			}
		}

		$(document).on("keyup",`[type="email"]`,function(){
			if (validateEmail($(this).val())) {
				$(this).removeClass("is-invalid");
				$(this).siblings(".text-danger").addClass("d-none").html("");
				isValidParams.email = true;
			}else{
				$(this).addClass("is-invalid");
				$(this).siblings(".text-danger").removeClass("d-none").html($(this).data("error"));
				isValidParams.email = false;
			}
			validateRegBTN();
		});

		$(document).on("keyup",`#reg_password`,function(){
			if ($(this).val().length > 5) {
				$(this).removeClass("is-invalid");
				$(this).siblings(".text-danger").addClass("d-none").html("");
				isValidParams.password = true;
			}else{
				$(this).addClass("is-invalid");
				$(this).siblings(".text-danger").removeClass("d-none").html($(this).data("error"));
				isValidParams.password = false;
			}
			validateRegBTN();
		});

		$(document).on("keyup",`#reg_name,#reg_surname`,function(){
			if ($(this).val().trim().length > 2) {
				isValidParams[$(this).attr("name")] = true;
			}else{
				isValidParams[$(this).attr("name")] = false;
			}
			validateRegBTN();
		});

		$(document).on("keyup",`#reg_phone`,function(){
			if (!$(this).val().trim().length || $(this).val().trim().length > 8) {
				isValidParams.phone = true;
			}else{
				isValidParams.phone = false;
			}
			validateRegBTN();
		});

		$(document).on("click",`[data-role="add-new-user"]`,function(e){
			e.preventDefault();
			let name = $("#reg_name").val(),
					surname = $("#reg_surname").val(),
					email = $("#reg_email").val(),
					password = $("#reg_password").val(),
					phone = $("#reg_phone").val(),
					gender = $("#reg_gender").val(),
					role = $("#reg_role").val();
			let data = {name,surname,email,password,phone,gender,role},
					url = $(this).parents("form").attr("action");
			Loader.btn({
				element: e.target,
				action: "start"
			});
			$.post({
				url,headers,data,
				success: function(d){
					// console.log(d);
					if (d.code === 201) {
						Loader.btn({
							element: e.target,
							action: "success"
						});
						$("#addUserModal .close").click();
						$("#reg_name,#reg_surname,#reg_email,#reg_password,#reg_phone").val("");
						getUsers(role);
					}
				},error: function(d){
					console.error(d)
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
			})
		})




		// $db.on("click",`[data-role="save_user_information"]`,function(e){
		// 	// console.log($(this).parents(`[data-role="user-item"]`).data("id"))
		// 	Loader.btn({
		// 		element: e.target,
		// 		action: "start"
		// 	});
		//
		// });

			// t_parents.find('input,select').removeClass(config.ISVALID_CLASS);
			// t_parents.find(`input[data-type='email']`).data("previous-value",data.email);
			// t_parents.find(`select[data-type='gender']`).data("previous-value",data.gender);
			// t_parents.find(`select[data-type='role']`).data("previous-value",data.role);
			// t_parents.find('button').prop("disabled",true);

		// $db.on("click",".refreshProdList",function(){
		// 	let t = $(this);
		// 	let txt = t.html();
		// 	t.html(btn_spinner);
		// 	myproducts(txt);
		// });

		// h += `<div data-id="${v.key}" data-token="${v.token}" class="col-4 cs_product_card cs_product_main" data-role="user-item">
		// 			<div class="card h-100 cs_product_pan">
		// 				<div class="cs_product_card_link_main d-flex p-3">
		// 					<div class="d-flex flex-column txt-left-side">
		// 						<p class="mb-1">${v.name} ${v.surname} (${v.role})</p>
		// 						<p>${v.created_at}</p>
		// 					</div>
		//
		// 					<div class="cs_product_card_link_main text-center">
		// 						<img class="cs_product_gallery_img"
		// 							src="/assets/v1/img/default.png"
		// 							alt="${v.name} ${v.surname}" title="${v.name} ${v.surname}">
		// 					</div>
		// 				</div>
		// 				<div class="card-body pt-3 pb-3 px-3">
		// 					<h4 class="card-title mb-3 mt-3">
		// 						<strong>${v.name} ${v.surname}</strong>
		// 					</h4>
		// 					<div class="tbIG tb_input_group">
		// 						<input class="form-control" value="${v.email || ''}" data-type="email" data-previous-value="${v.email}" style="min-width:120px">
		// 					</div>
		// 					<div class="tbIG tb_input_group">
		// 					<select class="form-control" data-type="gender" data-previous-value="${v.gender}">
		// 						<option${v.gender === 'male' ? ' selected' : ''}>male</option>
		// 						<option${v.gender === 'female' ? ' selected' : ''}>female</option>
		// 					</select>
		// 					</div>
		// 					<div class="tbIG tb_input_group">
		// 					${$("#admin_users_list").length ?
		// 						`<select class="form-control" data-type="role" data-previous-value="${v.role}">
		// 							<option${v.role === 'user' ? ' selected' : ''} value="user">user</option>
		// 							<option${v.role === 'admin' ? ' selected' : ''} value="admin">admin</option>
		// 							<option${v.role === 'main_admin' ? ' selected' : ''} value="main_admin">main admin</option>
		// 							<option${v.role === 'developer' ? ' selected' : ''} value="developer">developer</option>
		// 							</select>` :
		// 						v.role}
		// 					</div>
		//
		// 					<div class="row d-flex align-items-center">
		// 						<div class="col-md-8 cs_card_price"><span>${v.created_at}</span></div>
		// 						<div class="col-md-4 d-flex justify-content-end">
		// 							<a class="add_to_cart position-sticky shopping-bag-product-cart" data-role="save_user_information">
		// 								<svg enable-background="new 0 0 512.007 512.007" height="512"
		// 									viewBox="0 0 512.007 512.007" width="512" xmlns="http://www.w3.org/2000/svg">
		// 									<g>
		// 										<path
		// 											d="m511.927 126.537c-.279-2.828-1.38-5.666-3.315-8.027-.747-.913 6.893 6.786-114.006-114.113-2.882-2.882-6.794-4.395-10.612-4.394-9.096 0-329.933 0-338.995 0-24.813 0-45 20.187-45 45v422c0 24.813 20.187 45 45 45h422c24.813 0 45-20.187 45-45 .001-364.186.041-339.316-.072-340.466zm-166.927-96.534v98c0 8.271-6.729 15-15 15h-19v-113zm-64 0v113h-139c-8.271 0-15-6.729-15-15v-98zm64 291h-218v-19c0-8.271 6.729-15 15-15h188c8.271 0 15 6.729 15 15zm-218 161v-131h218v131zm355-15c0 8.271-6.729 15-15 15h-92c0-19.555 0-157.708 0-180 0-24.813-20.187-45-45-45h-188c-24.813 0-45 20.187-45 45v180h-52c-8.271 0-15-6.729-15-15v-422c0-8.271 6.729-15 15-15h52v98c0 24.813 20.187 45 45 45h188c24.813 0 45-20.187 45-45v-98h2.787l104.213 104.214z" />
		// 									</g>
		// 								</svg>
		//
		// 							</a>
		// 						</div>
		// 					</div>
		// 				</div>
		// 			</div>
		// 		</div>`;

});
