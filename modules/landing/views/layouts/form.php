<!-- Form be partner -->
<div class="popUpStyle" data-id="be-partner" id="be-partner">
	<div class="pop-up-wrapper pop-up-middle-wrapper">
		<div class="pop-header">
			<h4 class="text-center"><?= lang("Want to be a partner"); ?></h4>
			<div class="close-icon">
				<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001"
					style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
					<g>
						<g>
							<path d="M294.111,256.001L504.109,46.003c10.523-10.524,10.523-27.586,0-38.109c-10.524-10.524-27.587-10.524-38.11,0L256,217.892
            				L46.002,7.894c-10.524-10.524-27.586-10.524-38.109,0s-10.524,27.586,0,38.109l209.998,209.998L7.893,465.999
            				c-10.524,10.524-10.524,27.586,0,38.109c10.524,10.524,27.586,10.523,38.109,0L256,294.11l209.997,209.998
            				c10.524,10.524,27.587,10.523,38.11,0c10.523-10.524,10.523-27.586,0-38.109L294.111,256.001z" />
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
			</div>
		</div>
		<div class="pop-body">
			<form action="/send-order" class="m-0">
				<div class="row m-0 pb-4 border-bottom">
					<div class="mini-title-form col-12">
						<h5><?= lang("Responsible person") ?></h5>
					</div>
					<div class="col-6">
						<div class="form-group">
							<input type="text" name="person_name" placeholder="<?= lang("Name") ?>"
								required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<input type="text" name="person_surname"
								placeholder="<?= lang("Surname") ?>" required>
						</div>
					</div>
				</div>
				<div class="row pt-4 m-0">
					<div class="mini-title-form col-12">
						<h5><?= lang("Company") ?></h5>
					</div>
					<div class="col-6">
						<div class="form-group">
							<input type="text" name="comp_name" placeholder="<?= lang("Name") ?>"
								required>
						</div>

						<div class="form-group">
							<input type="tel" name="comp_phone" placeholder="<?= lang("Phone") ?>"
								required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<select id="formCityList" name="comp_city"
								data-text="<?= lang("City"); ?>" required></select>
						</div>

						<div class="form-group">
							<input type="email" name="comp_email" placeholder="<?= lang("Email") ?>"
								data-error-text="<?= lang("Enter valid email input"); ?>" required>
						</div>
					</div>
					<div class="col-12">
						<div class="form-group">
							<input type="text" name="comp_address"
								placeholder="<?= lang("Address") ?>" required>
						</div>

						<div class="form-group">
							<textarea id="formComment" name="comment"
								placeholder="<?= lang("Comment") ?>" required></textarea>
						</div>
					</div>
					<div class="col-12">
						<?php if (false): ?>
						<label class="chck m-0">
							<span class="link"><a href="#" class="a-hover-underline pb-1">Şərtlər və Qaydaları</a>
							oxudum, qəbul edirəm.</span>
							<input type="checkbox" checked="checked">
							<span class="checkmark"></span>
						</label>
						<?php endif; ?>
						<div class="btn-contact d-flex flex-column align-items-end w-100">
							<div class="form-group d-flex justify-content-end text-right">
								<div class="g-recaptcha mb-3" data-sitekey="<?= $this->config->item("captcha_site_key") ?>"></div>
							</div>
							<button class="btn mb-3" id="formSend"
								data-error-text="<?= lang("Cannot_be_empty"); ?>">
								<?= lang("Send"); ?>
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="overlay-pop-up">
	</div>
</div>
<!-- Form be partner -->
