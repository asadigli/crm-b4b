<?php
$this->page_title = isset($data['name']) ? $data['name'] . ' ' . $data['surname'] : '';
$this->load->view('layouts/head');
$this->load->view('layouts/menu');
$myProfile = $data['token'] === $this->session->userdata('token');
?>
<div class="container">
	<div class="page-card-header">

	</div>
	<div class="page-card-inside my-5">
		<div class="row d-flex justify-content-between">
			<div class="col-sm-3 p-0">
				<div class="left-side-page-card px-0">
					<div class="profile-img-edit">
						<div class="overlay"> + </div>
						<img <?php if ($myProfile): ?> src="<?= $this->user_avatar; ?>" role='user-avatar'
						<?php else: ?> src="<?= $data['user_avatar']; ?>" <?php endif; ?> />
						<input type="file" name="avatar" id="userAvatar">
					</div>
					<!-- <p class="profile-image-txt">Profil şəkilinizi yükləyin</p> -->
					<ul class="profile-links-nav">
						<li>
							<a href="">
								<svg height="512pt" viewBox="-21 0 512 512" width="512pt"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="m453.332031 229.332031c-8.832031 0-16-7.167969-16-16 0-61.269531-23.847656-118.847656-67.15625-162.175781-6.25-6.25-6.25-16.382812 0-22.632812s16.382813-6.25 22.636719 0c49.34375 49.363281 76.519531 115.007812 76.519531 184.808593 0 8.832031-7.167969 16-16 16zm0 0">
									</path>
									<path
										d="m16 229.332031c-8.832031 0-16-7.167969-16-16 0-69.800781 27.179688-135.445312 76.542969-184.789062 6.25-6.25 16.386719-6.25 22.636719 0s6.25 16.386719 0 22.636719c-43.328126 43.304687-67.179688 100.882812-67.179688 162.152343 0 8.832031-7.167969 16-16 16zm0 0">
									</path>
									<path
										d="m234.667969 512c-44.117188 0-80-35.882812-80-80 0-8.832031 7.167969-16 16-16s16 7.167969 16 16c0 26.476562 21.523437 48 48 48 26.472656 0 48-21.523438 48-48 0-8.832031 7.167969-16 16-16s16 7.167969 16 16c0 44.117188-35.882813 80-80 80zm0 0">
									</path>
									<path
										d="m410.667969 448h-352c-20.589844 0-37.335938-16.746094-37.335938-37.332031 0-10.925781 4.757813-21.269531 13.058594-28.375 32.445313-27.414063 50.941406-67.261719 50.941406-109.480469v-59.480469c0-82.34375 66.988281-149.332031 149.335938-149.332031 82.34375 0 149.332031 66.988281 149.332031 149.332031v59.480469c0 42.21875 18.496094 82.066406 50.730469 109.332031 8.511719 7.253907 13.269531 17.597657 13.269531 28.523438 0 20.585937-16.746094 37.332031-37.332031 37.332031zm-176-352c-64.707031 0-117.335938 52.628906-117.335938 117.332031v59.480469c0 51.644531-22.632812 100.414062-62.078125 133.757812-.746094.640626-1.921875 1.964844-1.921875 4.097657 0 2.898437 2.433594 5.332031 5.335938 5.332031h352c2.898437 0 5.332031-2.433594 5.332031-5.332031 0-2.132813-1.171875-3.457031-1.878906-4.054688-39.488282-33.386719-62.121094-82.15625-62.121094-133.800781v-59.480469c0-64.703125-52.628906-117.332031-117.332031-117.332031zm0 0">
									</path>
									<path
										d="m234.667969 96c-8.832031 0-16-7.167969-16-16v-64c0-8.832031 7.167969-16 16-16s16 7.167969 16 16v64c0 8.832031-7.167969 16-16 16zm0 0">
									</path>
								</svg>
								Bildirisler
							</a>
						</li>
						<li>
							<a href="">
								<svg height="511pt" viewBox="0 -10 511.98685 511" width="511pt"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="m114.59375 491.140625c-5.609375 0-11.179688-1.75-15.933594-5.1875-8.855468-6.417969-12.992187-17.449219-10.582031-28.09375l32.9375-145.089844-111.703125-97.960937c-8.210938-7.167969-11.347656-18.519532-7.976562-28.90625 3.371093-10.367188 12.542968-17.707032 23.402343-18.710938l147.796875-13.417968 58.433594-136.746094c4.308594-10.046875 14.121094-16.535156 25.023438-16.535156 10.902343 0 20.714843 6.488281 25.023437 16.511718l58.433594 136.769532 147.773437 13.417968c10.882813.980469 20.054688 8.34375 23.425782 18.710938 3.371093 10.367187.253906 21.738281-7.957032 28.90625l-111.703125 97.941406 32.9375 145.085938c2.414063 10.667968-1.726562 21.699218-10.578125 28.097656-8.832031 6.398437-20.609375 6.890625-29.910156 1.300781l-127.445312-76.160156-127.445313 76.203125c-4.308594 2.558594-9.109375 3.863281-13.953125 3.863281zm141.398438-112.875c4.84375 0 9.640624 1.300781 13.953124 3.859375l120.277344 71.9375-31.085937-136.941406c-2.21875-9.746094 1.089843-19.921875 8.621093-26.515625l105.472657-92.5-139.542969-12.671875c-10.046875-.917969-18.6875-7.234375-22.613281-16.492188l-55.082031-129.046875-55.148438 129.066407c-3.882812 9.195312-12.523438 15.511718-22.546875 16.429687l-139.5625 12.671875 105.46875 92.5c7.554687 6.613281 10.859375 16.769531 8.621094 26.539062l-31.0625 136.9375 120.277343-71.914062c4.308594-2.558594 9.109376-3.859375 13.953126-3.859375zm-84.585938-221.847656s0 .023437-.023438.042969zm169.128906-.0625.023438.042969c0-.023438 0-.023438-.023438-.042969zm0 0" />
								</svg>
								Secilenler
							</a>
						</li>
						<li>
							<a href="">
								<svg viewBox="-35 0 512 512.00102" xmlns="http://www.w3.org/2000/svg">
									<path
										d="m443.054688 495.171875-38.914063-370.574219c-.816406-7.757812-7.355469-13.648437-15.15625-13.648437h-73.140625v-16.675781c0-51.980469-42.292969-94.273438-94.273438-94.273438-51.984374 0-94.277343 42.292969-94.277343 94.273438v16.675781h-73.140625c-7.800782 0-14.339844 5.890625-15.15625 13.648437l-38.9140628 370.574219c-.4492192 4.292969.9453128 8.578125 3.8320308 11.789063 2.890626 3.207031 7.007813 5.039062 11.324219 5.039062h412.65625c4.320313 0 8.4375-1.832031 11.324219-5.039062 2.894531-3.210938 4.285156-7.496094 3.835938-11.789063zm-285.285157-400.898437c0-35.175782 28.621094-63.796876 63.800781-63.796876 35.175782 0 63.796876 28.621094 63.796876 63.796876v16.675781h-127.597657zm-125.609375 387.25 35.714844-340.097657h59.417969v33.582031c0 8.414063 6.824219 15.238282 15.238281 15.238282s15.238281-6.824219 15.238281-15.238282v-33.582031h127.597657v33.582031c0 8.414063 6.824218 15.238282 15.238281 15.238282 8.414062 0 15.238281-6.824219 15.238281-15.238282v-33.582031h59.417969l35.714843 340.097657zm0 0">
									</path>
								</svg>
								Sebetim
							</a>
						</li>
						<li>
							<a href="?action=change-password" class="active">
								<svg id="_x31__x2C_5" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24"
									width="512" xmlns="http://www.w3.org/2000/svg">
									<path
										d="m18.75 24h-13.5c-1.24 0-2.25-1.009-2.25-2.25v-10.5c0-1.241 1.01-2.25 2.25-2.25h13.5c1.24 0 2.25 1.009 2.25 2.25v10.5c0 1.241-1.01 2.25-2.25 2.25zm-13.5-13.5c-.413 0-.75.336-.75.75v10.5c0 .414.337.75.75.75h13.5c.413 0 .75-.336.75-.75v-10.5c0-.414-.337-.75-.75-.75z" />
									<path
										d="m17.25 10.5c-.414 0-.75-.336-.75-.75v-3.75c0-2.481-2.019-4.5-4.5-4.5s-4.5 2.019-4.5 4.5v3.75c0 .414-.336.75-.75.75s-.75-.336-.75-.75v-3.75c0-3.309 2.691-6 6-6s6 2.691 6 6v3.75c0 .414-.336.75-.75.75z" />
									<path
										d="m12 17c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zm0-2.5c-.275 0-.5.224-.5.5s.225.5.5.5.5-.224.5-.5-.225-.5-.5-.5z" />
									<path
										d="m12 20c-.414 0-.75-.336-.75-.75v-2.75c0-.414.336-.75.75-.75s.75.336.75.75v2.75c0 .414-.336.75-.75.75z" />
								</svg>
								<?= lang("Change_password"); ?>
							</a>
						</li>
						<li>
							<a href="">
								<svg height="512pt" viewBox="0 0 511 512" width="512pt"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="m361.5 392v40c0 44.113281-35.886719 80-80 80h-201c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h201c44.113281 0 80 35.886719 80 80v40c0 11.046875-8.953125 20-20 20s-20-8.953125-20-20v-40c0-22.054688-17.945312-40-40-40h-201c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h201c22.054688 0 40-17.945312 40-40v-40c0-11.046875 8.953125-20 20-20s20 8.953125 20 20zm136.355469-170.355469-44.785157-44.785156c-7.8125-7.8125-20.476562-7.8125-28.285156 0-7.8125 7.808594-7.8125 20.472656 0 28.28125l31.855469 31.859375h-240.140625c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h240.140625l-31.855469 31.859375c-7.8125 7.808594-7.8125 20.472656 0 28.28125 3.90625 3.90625 9.023438 5.859375 14.140625 5.859375 5.121094 0 10.238281-1.953125 14.144531-5.859375l44.785157-44.785156c19.496093-19.496094 19.496093-51.214844 0-70.710938zm0 0">
									</path>
								</svg>
								Cix
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="right-side-page-card">
					<div class="page-card-header">
						<div class="cs_whc_title px-3 py-2">
							<h1>
								<?= isset($data['name']) ? $data['name'] . ' ' . ($data['surname'] ? $data['surname'] : '') : ($this->session->userdata('name'). ' ' . $this->session->userdata('surname')); ?>
								<?php if ((!empty($data['type']) && $data['type'] === 'seller') || !empty($this->session->userdata('name')) && $this->session->userdata('name') === 'seller'): ?>
								<em class="fa fa-check verified-store" data-toggle="tooltip" data-placement="right"
									title="<?= lang('Store'); ?>"></em>
								<?php endif; ?>
							</h1>
							<p><?= lang("Change_password"); ?></p>
						</div>
					</div>
					<div class="bottom-side-right-page-card pt-0">
						<!-- <?php if ($myProfile): ?>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-item nav-link active" id="nav-tab-1" data-toggle="tab"
								href="#profile_settings" role="tab" aria-controls="profile_settings"
								aria-selected="true">Şəxsi məlumat</a>
							<a class="nav-item nav-link" id="nav-tab-2" data-toggle="tab" href="#password_change"
								role="tab" aria-controls="password_change" aria-selected="false">Şifrənin
								yenilənməsi</a>
						</div>
						<?php endif; ?> -->

						<div class="" id="profile_settings" role="tabpanel" aria-labelledby="nav-tab-1">
							<?php if ($myProfile): ?>
							<form class="form" action="##" method="post">
								<?php if ($this->session->flashdata("message")) { echo '<div class="alert alert-'.$this->session->flashdata("type").'">'.$this->session->flashdata("message").'</div>'; } ?>
								<div class="form-group">
									<label><?= lang('Name'); ?></label>
									<input type="text" class="form-control"
										value="<?= isset($data['name']) ? $data['name'] : ''; ?>"
										placeholder="Adınzı daxil edin" />
								</div>
								<div class="form-group">
									<label><?= lang('Surname'); ?></label>
									<input type="text" class="form-control" name="xxx"
										placeholder="Soyadınızı daxil edin"
										value="<?= isset($data['surname']) ? $data['surname'] : ''; ?>" />
								</div>
								<div class="form-group">
									<label><?= lang('Phone'); ?></label>
									<input type="text" class="form-control"
										value="<?= isset($data['phone']) ? $data['phone'] : ''; ?>"
										placeholder="Mobil nömrənizi daxil edin" />
								</div>

								<div class="form-group">
									<label><?= lang('Email'); ?></label>
									<input type="text" class="form-control"
										value="<?= isset($data['email']) ? $data['email'] : ''; ?>" disabled
										name="xxx" placeholder="E-mail ünvanınız" />
								</div>
								<div class="form-group">
									<button class="btn def-btn py-2"
										type="submit"><?= lang('Save'); ?></button>
								</div>
							</form>
							<?php else: ?>
							<table class="table table-hovered">
								<tr>
									<td class="bold"><?= lang('Name'); ?></td>
									<td><?= $data['name']; ?></td>
								</tr>
								<tr>
									<td class="bold"><?= lang('Surname'); ?></td>
									<td><?= $data['surname']; ?></td>
								</tr>
								<tr>
									<td class="bold"><?= lang('Phone'); ?></td>
									<td><?= $data['phone']; ?></td>
								</tr>
								<tr>
									<td class="bold"><?= lang('Email'); ?></td>
									<td><?= $data['email']; ?></td>
								</tr>
							</table>
							<?php endif; ?>
						</div>
						<!--======================== SIFRE YENILENMESI =================================-->
						<!-- <div class="tab-pane fade" id="password_change" role="tabpanel" aria-labelledby="nav-tab-2">
								<form class="form" action="##" method="post">
									<div class="form-group">
										<label>Cari şifrə</label>
										<input type="text" class="form-control" name="xxx"
											placeholder="Cari şifrənizi daxil edin" />
									</div>

									<div class="form-group">
										<label>Yeni şifrə</label>
										<input type="text" class="form-control" name="xxx"
											placeholder="Yeni şifrə 8 simvoldan az olmamalıdır" />
									</div>

									<div class="form-group">
										<label>Yeni şifrə təkrar</label>
										<input type="text" class="form-control" name="xxx"
											placeholder="Yeni şifrəni təkrar daxil edin" />
									</div>

									<div class="form-group">
										<button class="btn btn-default" type="submit">
											Yadda saxla
										</button>
									</div>
								</form>
							</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('layouts/foot') ?>
