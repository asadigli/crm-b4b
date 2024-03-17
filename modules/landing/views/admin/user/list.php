<?php
  $this->page_title = lang('Users');
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>
<div class="container">
	<div class="page-card-inside my-4">
		<div class="row d-flex justify-content-between">
			<div class="col-sm-12">
				<div class="right-side-page-card">
					<div class="page-card-header">
						<h4 class="mb-3"><?php echo lang("Users"); ?></h4>
					</div>
					<div class="page-card-body container-shadow">
						<div class="btn-group  mb-4 d-flex justify-content-between w-100">
              <div class="left-side userRole">
							    <button type="button" data-user-role="admin"
								  class="<?php if (!isset($_GET['role']) || $this->input->get("role") === 'admin'){ echo " active";} ?>"><?php echo lang('Admins'); ?></button>
							    <button type="button" data-user-role="main_admin"
							 	  class="<?php if ($this->input->get("role") === 'main_admin'){ echo " active";} ?>">Baş
								  admin</button>
							    <button type="button" data-user-role="developer"
								  class="<?php if ($this->input->get("role") === 'developer'){ echo " active";} ?>">Developer</button>
  						    <button type="button" data-user-role="user"
  							  class="<?php if ($this->input->get("role") === 'user'){ echo " active";} ?>"><?php echo lang('Users'); ?></button>
              </div>
              <?php if ($this->main_admin) { ?>
                <div class="right-side">
                  <button type="button" data-toggle="modal" data-target="#addUserModal">+ <?php echo lang("Add new user"); ?></button>
                </div>
              <?php } ?>
						</div>

						<div class="table-wrapper">
							<table class="table table-striped border">
								<thead>
									<tr>
										<th scope="col"><?php echo lang('Name'); ?> / <br>
											<?php echo lang('Birthdate'); ?></th>
										<th scope="col"><?php echo lang('Email'); ?> /
											<br> <?php echo lang('Phone'); ?></th>
										<th scope="col"><?php echo lang('Gender'); ?></th>
										<th scope="col" <?php if ($this->main_admin){ echo "data-allow='editable'";} ?>> <?php echo lang('Role'); ?></th>
										<?php if ($this->main_admin): ?>
                    <th scope="col"><?php echo lang("Blocked user"); ?></th>
										<th scope="col" id="saveTitle"></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody id="usersList">

              	</tbody>
							</table>
						</div>



            <?php if ($this->main_admin) { ?>
            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form class="modal-content" action="/admin/user/add">
                  <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel"><?php echo lang("Add new user"); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
          						<input type="text" name="name" id="reg_name" class="form-control" minlength="6"
                              data-error="<?php echo lang("Valid name"); ?>"
          							       autocomplete="off" placeholder="<?php echo lang('Name'); ?>..." required>
                      <small class="text-danger d-none"></small>
          					</div>
          					<div class="form-group">
          						<input type="text" name="surname" id="reg_surname" class="form-control" minlength="6"
                              data-error="<?php echo lang("Valid surname"); ?>"
          							       autocomplete="off" placeholder="<?php echo lang("Surname"); ?>..." required>
                      <small class="text-danger d-none"></small>
          					</div>
          					<div class="form-group">
          						<input type="email" name="email" id="reg_email" class="form-control" minlength="6"
                              data-error="<?php echo lang("Enter valid email input"); ?>"
          							       placeholder="<?php echo lang("Email"); ?>..." required>
                      <small class="text-danger d-none"></small>
          					</div>
                    <div class="form-group">
                      <select class="form-control" name="reg_gender" id="reg_gender">
                        <option value="male">male</option>
                        <option value="female">female</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <select class="form-control" name="role" id="reg_role">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                        <option value="main_admin">main admin</option>
                        <option value="developer">developer</option>
                      </select>
                    </div>
                    <div class="form-group">
          						<input type="text" name="password" id="reg_phone" class="form-control" minlength="6" placeholder="(50) 000-00-00"
          							       autocomplete="off" placeholder="<?php echo lang('Phone'); ?>..." required>
                      <small class="text-danger d-none"></small>
          					</div>
          					<div class="form-group">
          						<input type="password" name="password" id="reg_password" class="form-control" minlength="6"
          							       autocomplete="off" placeholder="<?php echo lang('Password'); ?>..."
                                data-error="<?php echo lang("Enter password"); ?>" required>
                      <small class="text-danger d-none"></small>
          					</div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-role="add-new-user" disabled><?php echo lang("Add"); ?></button>
                  </div>
                </form>
              </div>
            </div>
            <?php } ?>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS = '<script src="'.assets("js/jquery/jquery.inputmask.js").'"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/user.list.89mOyVoJ3yyZmcdzPqpuIKqDu4rhFf.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot');
?>
