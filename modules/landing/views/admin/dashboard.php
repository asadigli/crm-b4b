<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-5">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("Admin Dashboard"); ?></h4>
			</div>
			<div class="page-card-body">
				<div class="container-shadow mb-4">
					<div class="tab">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="home-tab" data-bs-toggle="tab"
									data-bs-target="#home" type="button" role="tab" aria-controls="home"
									aria-selected="true">Əlaqələr</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
									type="button" role="tab" aria-controls="profile"
									aria-selected="false">Təkliflər</button>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active p-3" id="home" role="tabpanel"
								aria-labelledby="home-tab">
                <table class="table table-striped" id="contacts_list">
                  <thead>
                    <tr>
                      <th class="w-50"><?= lang("Name"); ?> </th>
                      <th class="w-50"><?= lang("Email"); ?> </th>
                      <th class="w-50"><?= lang("Phone"); ?> </th>
                      <th class="w-50"><?= lang("Title"); ?> </th>
                      <th class="w-50"><?= lang("Message"); ?> </th>
                      <th class="w-50"><?= lang("Date"); ?> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($contacts as $key => $item): ?>
                      <tr>
                        <td><?= $item["name"] ?></td>
                        <td><?= $item["email"] ?></td>
                        <td><?= $item["phone"] ?></td>
                        <td><?= $item["subject"] ?></td>
                        <td><?= $item["body"] ?></td>
                        <td><?= $item["date"] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
							</div>
							<div class="tab-pane fade p-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <table class="table table-striped" id="requests_list">
                  <thead>
                    <tr>
                      <th class="w-50"><?= lang("Name"); ?> </th>
                      <th class="w-50"><?= lang("Company"); ?> </th>
                      <th class="w-50"><?= lang("Email"); ?> </th>
                      <th class="w-50"><?= lang("Phone"); ?> </th>
                      <th class="w-50"><?= lang("Address"); ?> </th>
                      <th class="w-50"><?= lang("City"); ?> </th>
                      <th class="w-50"><?= lang("Message"); ?> </th>
                      <th class="w-50"><?= lang("Date"); ?> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($requests as $key => $item): ?>
                      <tr>
                        <td><?= $item["name"] ?></td>
                        <td><?= $item["company_name"] ?></td>
                        <td><?= $item["email"] ?></td>
                        <td><?= $item["phone"] ?></td>
                        <td><?= $item["address"] ?></td>
                        <td><?= $item["city"] ?></td>
                        <td><?= $item["body"] ?></td>
                        <td><?= $item["date"] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script type="module" data-role="page-js" src="'.assets("js/pvt/product.edit.waINE58nrJawNbX0owVdN17o0F5pd6.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')

?>
