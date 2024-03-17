<!-- Form be partner -->
<div class="popUpStyle" data-id="add-faq-popup" id="add-faq-popup">
	<div class="pop-up-wrapper pop-large">
		<div class="pop-header">
			<h4 class="text-center"><?php echo lang("Create") ?></h4>
			<div class="close-icon">
				<svg version="1.1" x="0px" y="0px" viewBox="0 0 512.001 512.001"
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
		<div class="pop-body flex-column px-4 pb-4 pt-0">
			<form action="/admin/pages/faq/add" method="POST">
				<div class="form-group">
					<select class="form-control" name="language">
						<option value="az"<?php echo !$this->input->get("data-lang") || $this->input->get("data-lang") === "az" ? " selected" : ""; ?>>AZ</option>
						<option value="en"<?php echo $this->input->get("data-lang") === "en" ? " selected" : ""; ?>>EN</option>
						<option value="ru"<?php echo $this->input->get("data-lang") === "ru" ? " selected" : ""; ?>>RU</option>
						<option value="tr"<?php echo $this->input->get("data-lang") === "tr" ? " selected" : ""; ?>>TR</option>
					</select>
				</div>
				<div class="form-group">
					<label for="faq_title"><?php echo lang("Title"); ?></label>
					<textarea name="faq_title"></textarea>
				</div>
				<div class="">
					<label for="faq_body"><?php echo lang("Status"); ?></label>
					<input type="checkbox" name="faq_status" checked>
				</div>
				<div class="form-group">
					<label for="faq_body">Faq text</label>
					<textarea name="faq_body" rows="10"></textarea>
				</div>
				<div class="form-group">
					<button type="button" class="def-btn popBtn" data-role="add-new-faq">
						<?php echo lang("Save"); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
	<div class="overlay-pop-up">
	</div>
</div>
<!-- Form be partner -->


<!-- Form be partner -->
<div class="popUpStyle" data-id="about-pop-edit" id="about-pop-edit">
	<div class="pop-up-wrapper pop-large">
		<div class="pop-header">
			<h4 class="text-center"><?php echo lang("Edit") ?></h4>
			<div class="close-icon">
				<svg version="1.1" x="0px" y="0px" viewBox="0 0 512.001 512.001"
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
		<div class="pop-body flex-column px-4 pb-4 pt-0">
			<form action="" method="POST" data-role="faq-edit-form">
				<input type="hidden" name="faq_id" value="">
				<div class="form-group">
					<label for="faq_title"><?php echo lang("Title"); ?></label>
					<textarea name="faq_title"></textarea>
				</div>
				<div class="form-group">
					<label for="faq_body">Faq text</label>
					<textarea name="faq_body" rows="10"></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="def-btn popBtn" data-role="edit-faq">
						<?php echo lang("Save"); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
	<div class="overlay-pop-up">
	</div>
</div>
<!-- Form be partner -->
