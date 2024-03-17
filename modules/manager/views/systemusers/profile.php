<section class="content">
	<div class="row">
		<div class="col-12 col-lg-7 col-xl-8">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
	  			<li><a class="active" href="#settings" data-bs-toggle="tab"><?= lang("General information") ?></a></li>
	  			<li><a href="#password" data-bs-toggle="tab"><?= lang("Change password") ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active " id="settings">
						<form class="form-horizontal form-element col-12">
						  <div class="form-group row">
								<label for="inputName" class="col-sm-2 form-label"><?= lang("Name") ?></label>
								<div class="col-sm-10">
								  <input type="email" data-id="<?= Auth::user() ?>" class="form-control" id="inputName" placeholder="" value="<?= Auth::name() ?>" readonly>
								</div>
						  </div>
						  <div class="form-group row">
								<label for="inputEmail" class="col-sm-2 form-label"><?= lang("Email") ?></label>
								<div class="col-sm-10">
								  <input type="email" data-id="<?= Auth::user() ?>" class="form-control" id="inputEmail" placeholder="" value="<?= Auth::email() ?>" readonly>
								</div>
						  </div>
						  <div class="form-group row">
								<label for="inputPhone" class="col-sm-2 form-label"><?= lang("Phone") ?></label>
								<div class="col-sm-10">
								  <input type="tel" data-id="<?= Auth::user() ?>" class="form-control" id="inputPhone" placeholder="" value="<?= Auth::phone() ?>" readonly>
								</div>
						  </div>
						</form>
				  </div>

				  <div class="tab-pane" id="password">
	            <div class="form-group col-6">
	              <label><?= lang("Current password") ?></label>
	              <div class="input-group">
	                <input autocomplete="off" type="password" name="old_password" class="form-control" placeholder="<?= lang("Current password") ?>">
									<div class="input-group-prepend">
										<span style="cursor:pointer;" class="input-group-text" data-role="view-password" data-text="old_password"><i  class="fas fa-eye"></i></span>
									</div>
	              </div>
	            </div>
	            <div class="form-group col-6">
	              <label><?= lang("Password") ?></label>
	              <div class="input-group">
	                <input autocomplete="off" type="password" name="password" class="form-control" placeholder="<?= lang("Password") ?>">
	                <div class="input-group-prepend">
	                  <span style="cursor:pointer;" class="input-group-text" data-role="view-password" data-text="password"><i class="fas fa-eye"></i></span>
	                  <!-- <span style="cursor:pointer;" class="input-group-text" data-role="generate-password"><i data-role="generate" class="fas fa-sync"></i></span> -->
	                </div>
	              </div>
	            </div>
							<div class="form-group col-6">
								<label><?= lang("Confirm password") ?></label>
								<div class="input-group">
									<input autocomplete="off" type="password" name="confirm_password" class="form-control" placeholder="<?= lang("Confirm password") ?>">
									<div class="input-group-prepend">
										<span style="cursor:pointer;" class="input-group-text" data-role="view-password" data-text="confirm_password"><i  class="fas fa-eye"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group col-6">
								<button class="btn btn-primary ms-2" data-id="<?= Auth::user() ?>" type="button" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?= lang("Save") ?>" data-role="save"><i class="fas fa-save"></i> </button>
							</div>
				  </div>
				</div>
			  </div>
			</div>

			  <div class="col-12 col-lg-5 col-xl-4">
				 <div class="box box-widget widget-user">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div class="widget-user-header bg-img bbsr-0 bber-0" style="background: url('<?= assets("image/bg-image.jpg",true) ?>') center center;" data-overlay="5">
					  <h3 class="widget-user-username text-white"><?= Auth::name() ?></h3>
            <?php if (Auth::group_name()): ?>
              <h6 class="widget-user-desc text-white"><?= Auth::group_name() ?></h6>
            <?php endif; ?>
					</div>
					<div class="widget-user-image">
					  <img class="rounded-circle" src="<?= Auth::avatar() ?: assets("image/avatar-user-png.png",true) ?>" alt="User Avatar">
					</div>
					<div class="box-footer">
					  <div class="row">
						<!-- /.col -->
					  </div>
					  <!-- /.row -->
					</div>
				  </div>
				  <div class="box">
					<div class="box-body box-profile">
					  <div class="row">
						<div class="col-12">
							<div>
								<p><?= lang("Email") ?> :<span class="text-gray ps-10"><?= Auth::email() ?></span> </p>
								<p><?= lang("Phone") ?> :<span class="text-gray ps-10"><?= Auth::phone() ?></span></p>
							</div>
						</div>


					  </div>
					</div>
					<!-- /.box-body -->
				  </div>


			  </div>

		  </div>
		  <!-- /.row -->

		</section>
		<!-- /.content -->

  <!-- /.content-wrapper -->
