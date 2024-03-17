<div class="container-select-language">
	<div class="items-top-footer drop-down-key">
		<?= readSVG('icons/flags/'.$this->local) ?>
		<p><?php echo $this->local; ?></p>
		<?= readSVG("icons/chevron") ?>
	</div>
	<div class="drop-down-language" data-role="language-switcher">
		<ul>
			<?php
			$langs = $this->config->item("languages");
			foreach($langs as $lang) {
				if($lang){ ?>
				<li data-id="<?= $lang ?>">
					<a <?= $this->local === $lang ? ' class="selected"' : ''; ?> href="<?= langSwitcher(uri_string(),$lang); ?>">
					<?= readSVG("icons/flags/$lang") ?>
					<?= strtoupper($lang) ?>
					</a>
				</li>
			<?php }
			} ?>
		</ul>
	</div>
</div>
