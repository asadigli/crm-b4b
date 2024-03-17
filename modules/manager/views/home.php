<div class="content-header">
  <div class="row align-items-center">
    <div class="col-md-9">
      <h3 class="page-title"><?= lang("Home page") ?></h3>
    </div>
    <div class="col-md-3 d-flex justify-content-end">
      <input type="month" name="month_date" style="min-width: 134px;" min="2018-01" max="<?= date("Y-m") ?>" class="form-control" value="<?= date("Y-m") ?>">
      <button class="btn btn-success ms-3" data-role="filter-action-btn">
        <em class="fa fa-search"></em>
      </button>
      <button class="btn btn-primary ms-3" data-role="refresh-cache-last-hour" data-type="customer-accounts">
        <em class="fa fa-sync"></em>
      </button>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-xl-3 col-md-6 col-12">
    <div class="box blue">
      <div class="box-body">
        <h4 class="fw-500 mt-0"><?= lang("Daily sales") ?></h4>
        <div class="">
          <div class="d-flex align-items-center justify-content-between">
            <h3><?= lang("EURO") . ":" ?></h3>
            <h3 class="fw-600 my-0 d-flex " data-role="daily_sales">
              <?= isset($data["amount"]["daily_eur_sales"]) ? number_format($data["amount"]["daily_eur_sales"],2,",",".") : 0 ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="box-footer py-3">
        <a href="<?= path_local("invoices/sales") . "?start_date=" . date("Y-m-d") ?>" target="_blank"
            rel="noreferrer nofollow"  class="d-flex justify-content-between align-items-center">
          <span><?= lang("See details") ?></span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 col-12">
    <div class="box orange">
      <div class="box-body">
        <h4 class="fw-500 mt-0"><?= lang("Monthly sales") ?></h4>
        <div class="">
          <div class="d-flex align-items-center justify-content-between">
            <h3><?= lang("EURO") . ":" ?></h3>
            <h3 class="fw-600 my-0 d-flex " data-role="monthly_sales">
              <?= isset($data["amount"]["monthly_eur_sales"]) ? number_format($data["amount"]["monthly_eur_sales"],2,",",".") : 0 ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="box-footer py-3">
        <a href="<?= path_local("invoices/daily-sales") . "?start_date=" . date("Y-m-01") ?>" target="_blank"
            rel="noreferrer nofollow"  class="d-flex justify-content-between align-items-center">
          <span><?= lang("See details") ?></span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 col-12">
    <div class="box purple">
      <div class="box-body">
        <h4 class="fw-500 mt-0"><?= lang("Customer debt") ?></h4>
        <div class="">
          <div class="d-flex align-items-center justify-content-between">
            <h3><?= lang("EURO") . ":" ?></h3>
            <h3 class="fw-600 my-0 d-flex " data-role="customer_debt">
              <?= isset($data["amount"]["customer_eur_debt"]) ? number_format($data["amount"]["customer_eur_debt"],2,",",".") : 0 ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="box-footer py-3">
        <a href="<?= path_local("customers?customer_type=211&status=active") ?>" target="_blank"
            rel="noreferrer nofollow"  class="d-flex justify-content-between align-items-center">
          <span><?= lang("See details") ?></span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 col-12">
    <div class="box green">
      <div class="box-body">
        <h4 class="fw-500 mt-0"><?= lang("Monthly purchases") ?></h4>
        <div class="">
          <div class="d-flex align-items-center justify-content-between">
            <h3><?= lang("EURO") . ":" ?></h3>
            <h3 class="fw-600 my-0 d-flex " data-role="monthly_purchase">
              <?= isset($data["amount"]["monthly_eur_purchase"]) ? number_format($data["amount"]["monthly_eur_purchase"],2,",",".") : 0 ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="box-footer py-3">
        <a <a href="<?= path_local("invoices/purchases") . "?start_date=" . date("Y-m-01") ?>" target="_blank"
            rel="noreferrer nofollow"  class="d-flex justify-content-between align-items-center">
          <span><?= lang("See details") ?></span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <?php if (Auth::isDeveloper() && false): ?>
    <div class="col-xl-3 col-12">
      <div class="box">
        <div class="box-header">
          <h4 class="box-title">Working Format</h4>
        </div>
        <div class="box-body">
          <div id="chart41"></div>
          <div class="d-flex align-items-center justify-content-between">
            <h5 class="fw-500 my-0"><span class="me-10 badge badge-xl badge-dot badge-primary"></span> Remote</h5>
            <h5 class="fw-500 my-0"><span class="me-10 badge badge-xl badge-dot badge-primary-light"></span> On Site</h5>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (Auth::isDeveloper() && false): ?>
    <div class="col-xl-6 col-12">
      <div class="box">
        <div class="box-header">
          <h4 class="box-title">Project employment</h4>
        </div>
        <div class="box-body">
          <div id="analytics-bar-chart"></div>
          <div class="mt-10 d-flex align-items-center justify-content-between">
            <h5 class="fw-500 my-0"><span class="me-10 badge badge-xl badge-dot badge-info"></span> Project</h5>
            <h5 class="fw-500 my-0"><span class="me-10 badge badge-xl badge-dot badge-info-light"></span> Bench</h5>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="row m-0">
    <div class="col-xl-12 col-12 p-0">
      <div class="box">
        <div class="box-header">
          <div class="row align-items-center">
            <div class="col-md-11 col-9">
              <h4 class="box-title"><?= lang("Sales and purchase and payment reports") ?></h4>
            </div>
            <?php if (false): ?>
              <div class="col-md-2 col-3 p-0 d-flex justify-content-end">
                <select class="form-select custom-select" name="warehouse">
                  <option value=""><?= lang("All") ?></option>
                  <?php $warehouse_list = $this->config->item("warehouse_list");
                      foreach (array_values($warehouse_list) as $key => $warehouse): ?>
                        <option value="<?= $key ?>"<?= (int)$this->input->get("warehouse") === (int)$key || $key === 0 ? " selected" : "" ?>><?= $warehouse ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>

            <div class="col-md-1 col-3 p-0 d-flex justify-content-end">
              <?php if (false): ?>
                <select class="form-select custom-select" name="annual_year_filter">
                  <?php for ($i = date("Y"); $i >= 2018; $i--) { ?>
                    <option value="<?= $i ?>"<?= (int)$this->input->get("year") === (int)$i ? " selected" : "" ?>><?= $i ?></option>
                  <?php } ?>
                </select>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="box-body">
          <canvas id="annualReports" style="height:404px" data-currency="<?= CURRENCY_EUR ?>"></canvas>
        </div>
				<div class="box-body">
					<div class="table-responsive">
					  <table class="table table-bordered mb-0">
						  <tbody>
  							<tr>
                  <th scope="col"><?= lang("Total sales") ?></th>
                  <th scope="col"><?= lang("Total purchases") ?></th>
                  <th scope="col"><?= lang("Total payments") ?></th>
                  <th scope="col"><?= lang("Total sale returns") ?></th>
                  <th scope="col"><?= lang("Average sales") ?></th>
  							  <th scope="col"><?= lang("Average purchases") ?></th>
  							  <th scope="col"><?= lang("Average payments") ?></th>
  							</tr>
						  </tbody>
						  <tbody>
  							<tr>
  							  <td data-role="total-sales">0</td>
                  <td data-role="total-purchases">0</td>
                  <td data-role="total-payments">0</td>
                  <td data-role="total-sale-returns">0</td>
                  <td data-role="average-sales">0</td>
                  <td data-role="average-purchases">0</td>
                  <td data-role="average-payments">0</td>
  							</tr>
						  </tbody>
						</table>
					</div>
				</div>
			  </div>
			</div>
  </div>

  <div class="col-xl-3 col-md-6 col-12">
    <div class="box mb-4">
      <div class="box-body">
        <h4 class="fw-500 mt-0"><?= lang("B4B current online entry count") ?></h4>
        <div class="d-flex align-items-center justify-content-between">
          <h2 class="fw-600 my-0" data-role="current_online"><?= isset($data["onlines"]["current"]) ? $data["onlines"]["current"] : 0 ?></h2>
          <?php if (false): ?>
            <h4 class="fw-500 my-0 text-success"><i class="me-10 mdi mdi-arrow-top-right"></i> +15%</h4>
          <?php endif; ?>
        </div>
      </div>
      <div class="box-footer py-3">
        <a href="<?= path_local("entries?sort_by=by_is_online") ?>" target="_blank"
            rel="noreferrer nofollow"  class="d-flex justify-content-between align-items-center">
          <span><?= lang("See details") ?></span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
    <div class="box" data-role="b4b-reports">
      <div class="box-header">
        <h4 class="box-title"><?= lang("B4B reports") ?></h4>
      </div>
      <div class="box-body p-0">
        <div class="media-list media-list-hover media-list-divided">
          <a class="media media-single rounded-0" href="<?= path_local("orders?start_date=".date("Y-m-d")."&end_date=".date("Y-m-d")) ?>"
                target="_blank" rel="noreferrer nofollow">
            <span class="title"><?= lang("Daily orders count") ?> </span>
            <span class="title" data-role="daily-orders"><?= isset($data["b4b"]["order_count"]["daily"]) ? $data["b4b"]["order_count"]["daily"] : 0 ?> </span>
          </a>

          <a class="media media-single rounded-0" href="<?= path_local("orders?start_date=".date("Y-m-01")."&end_date=".date("Y-m-d")) ?>"
                target="_blank" rel="noreferrer nofollow">
            <span class="title"><?= lang("Monthly orders count") ?></span>
            <span class="title" data-role="monthly-orders"><?= isset($data["b4b"]["order_count"]["monthly"]) ? $data["b4b"]["order_count"]["monthly"] : 0 ?></span>
          </a>

          <a class="media media-single rounded-0" href="<?= path_local("orders?start_date=".date("Y-01-01")."&end_date=".date("Y-m-d")) ?>"
                target="_blank" rel="noreferrer nofollow">
            <span class="title"><?= lang("Annual orders count") ?></span>
            <span class="title" data-role="annual-orders"><?= isset($data["b4b"]["order_count"]["annual"]) ? $data["b4b"]["order_count"]["annual"] : 0 ?> </span>
          </a>

        </div>
      </div>
    </div>
  </div>


  <div class="col-xl-9 col-12">
    <?php if (false): ?>
      <div class="box">
        <div class="box-header">
          <h4 class="box-title"><?= lang("Monthly B4B activity") ?></h4>
        </div>
        <div class="box-body">
          <canvas id="onlineChart" style="height:284px"></canvas>
        </div>
      </div>
    <?php endif; ?>
  </div>


  <?php if (Auth::isDeveloper()): ?>
    <?php if (false): ?>
      <div class="col-xl-6 col-12">
        <div class="box">
          <div class="box-header">
            <h4 class="box-title">Total Applications</h4>
          </div>
          <div class="box-body content-flot-chart">
            <div class="demo-container">
              <div id="placeholder" class="demo-placeholder"></div>
              <p id="hoverdata"></p>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

  <?php endif; ?>

  <?php if (false): ?>
    <div class="col-xl-3 col-12">
      <div class="box">
        <div class="box-header">
          <h4 class="box-title">Total Applications</h4>
        </div>
        <div class="box-body">
          <div class="d-flex py-10 w-p100 rounded100 overflow-hidden">
            <div class="bg-danger h-10" style="width: 8%;"></div>
            <div class="bg-warning h-10" style="width: 12%;"></div>
            <div class="bg-success h-10" style="width: 22%;"></div>
            <div class="bg-info h-10" style="width: 58%;"></div>
          </div>
        </div>
        <div class="box-body p-0">
          <div class="media-list media-list-hover media-list-divided">
            <a class="media media-single rounded-0" href="#">
              <span class="badge badge-xl badge-dot badge-info"></span>
              <span class="title">Applications </span>
              <span class="badge badge-pill badge-info-light">58%</span>
            </a>

            <a class="media media-single rounded-0" href="#">
              <span class="badge badge-xl badge-dot badge-success"></span>
              <span class="title">Shortlisted</span>
              <span class="badge badge-pill badge-success-light">22%</span>
            </a>

            <a class="media media-single rounded-0" href="#">
              <span class="badge badge-xl badge-dot badge-warning"></span>
              <span class="title">On-Hold</span>
              <span class="badge badge-pill badge-warning-light">12%</span>
            </a>

            <a class="media media-single rounded-0" href="#">
              <span class="badge badge-xl badge-dot badge-danger"></span>
              <span class="title">Rejected</span>
              <span class="badge badge-pill badge-danger-light">08%</span>
            </a>
          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>

  <?php if (false): ?>
    <div class="col-xl-6 col-12">
      <div class="box">
        <div class="box-header">
          <h4 class="box-title">Staff turnover</h4>
        </div>
        <div class="box-body">
          <div id="staff_turnover"></div>
        </div>
      </div>
    </div>

  <?php endif; ?>

  <?php if (false): ?>
    <div class="col-xl-6 col-12">
      <div class="box">
        <div class="box-header">
          <h4 class="box-title">Recruitment progress</h4>
        </div>
        <div class="box-body px-0">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <tbody>
                <tr>
                  <th scope="col">Full Name</th>
                  <th scope="col">Department</th>
                  <th scope="col">Type</th>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>
                    <h5 class="fw-500 my-0 min-w-150"><img src="https://crm-admin-dashboard-template.multipurposethemes.com/images/avatar/avatar-1.png" class="avatar me-10 bg-primary-light" alt=""> Dom Sibley</h5>
                  </td>
                  <td>
                    <h5 class="fw-500 my-0">Devops</h5>
                  </td>
                  <td>
                    <h5 class="fw-500 my-0"><span class="me-10 badge badge-dot badge-primary"></span> Tech interview</h5>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h5 class="fw-500 my-0 min-w-150"><img src="https://crm-admin-dashboard-template.multipurposethemes.com/images/avatar/avatar-1.png" class="avatar me-10 bg-danger-light" alt=""> Joe Root</h5>
                  </td>
                  <td>
                    <h5 class="fw-500 my-0">UX/UI Designer</h5>
                  </td>
                  <td>
                    <h5 class="fw-500 my-0"><span class="me-10 badge badge-dot badge-danger"></span> Resume review</h5>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h5 class="fw-500 my-0 min-w-150"><img src="https://crm-admin-dashboard-template.multipurposethemes.com/images/avatar/avatar-1.png" class="avatar me-10 bg-warning-light" alt=""> Zak Crawley</h5>
                  </td>
                  <td>
                    <h5 class="fw-500 my-0">.Net developer</h5>
                  </td>
                  <td>
                    <h5 class="fw-500 my-0"><span class="me-10 badge badge-dot badge-warning"></span> Final interview</h5>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>




</div>
