<?php
  $this->page_title = lang('Users');
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>
<style media="screen">
	.spinner {
		position: absolute;
		left: 50%;
		top: 150px;
		z-index: 10;
		height: 60px;
		width: 60px;
		margin: 0px auto;
		-webkit-animation: rotation .6s infinite linear;
		-moz-animation: rotation .6s infinite linear;
		-o-animation: rotation .6s infinite linear;
		animation: rotation .6s infinite linear;
		border-left: 6px solid rgba(0, 174, 239, .15);
		border-right: 6px solid rgba(0, 174, 239, .15);
		border-bottom: 6px solid rgba(0, 174, 239, .15);
		border-top: 6px solid rgb(33 87 137);
		border-radius: 100%;
	}

	@-webkit-keyframes rotation {
		from {
			-webkit-transform: rotate(0deg);
		}

		to {
			-webkit-transform: rotate(359deg);
		}
	}

	@-moz-keyframes rotation {
		from {
			-moz-transform: rotate(0deg);
		}

		to {
			-moz-transform: rotate(359deg);
		}
	}

	@-o-keyframes rotation {
		from {
			-o-transform: rotate(0deg);
		}

		to {
			-o-transform: rotate(359deg);
		}
	}

	@keyframes rotation {
		from {
			transform: rotate(0deg);
		}

		to {
			transform: rotate(359deg);
		}
	}

</style>
<div class="container">
	<div class="right-side-page-card my-5">
		<div class="page-card-header">
			<h4 class="mb-3"><?php echo lang("General configuration"); ?></h4>
		</div>
		<div class="page-card-body container-shadow">
			<div id="accordion" class="panel-group">
				<div class="spinner d-none"></div>
				<ul class="list-group">
					<?php foreach (array_keys($data) as $key => $item): ?>
					<li class="list-group-item d-flex">
						<label class="chck m-0">
							<input type="checkbox" data-key="<?php echo $item; ?>" class="configClass"
								<?php if($data[$item]["status"]) { echo "checked value='1'";}else{echo "value='0'";} ?>>
							<span class="checkmark"></span>
						</label>
						<a class="configListFT" href="javascript:void(0)"
							data-href="#bodyList<?php echo $key + 1; ?>"><?php echo $data[$item]["name"]; ?></a>
						<?php if (isset($data[$item]["children"])): ?>
						<div class="panel-body d-none" id="bodyList<?php echo $key + 1; ?>" style="margin-top: 15px;">
							<ul class="list-group">
								<?php foreach (array_keys($data[$item]["children"]) as $sub_key => $sub): ?>
								<li class="list-group-item">
									<label class="chck">
										<input type="checkbox" data-key="<?php echo $sub; ?>" class="configClass"
											<?php if($data[$item]["children"][$sub]["status"]) { echo "checked ";}else{echo "value='0'";}  ?>>
										<?php echo $data[$item]["children"][$sub]["name"]; ?>
										<span class="checkmark"></span>
									</label>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>

			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script type="module" src="'.assets('js/admin.js',$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
