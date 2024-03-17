<?php
  $this->page_title = $page['title'];
  $this->headCSS = '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />';
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<div class="container">
 <div class="row cs_main_products">
   <div class="col-md-3 cs_nav_row filter-section">
     <div class="overlay-filter">
       <span>
         <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
       </span>
     </div>
     <div class="filter-title">Filter</div>
     <div class="form-group">
       <!-- <label for=""><?php echo lang('Store_name'); ?></label> -->
       <input type="text" class="form-control"
               placeholder="<?php echo lang('Store_name'); ?>..."
               <?php if (isset($_GET['keyword'])){ echo 'value="'.$_GET['keyword'].'"';}?>
               id="store_filter_keyword">
     </div>

     <div class="form-group">
       <label for=""><?php echo lang('Brand'); ?></label>
       <select class="form-control multiple-select" name="store-brands" multiple data-live-search="true" title="<?php echo lang('Choose_brand'); ?>">
         <?php foreach ($brands as $key => $brand): ?>
           <option value="<?php echo $brand['id']; ?>"
             <?php if ($this->input->get('brands') && in_array($brand['id'],explode(",", $this->input->get('brands')))) { echo "selected"; } ?>><?php echo $brand['name']; ?></option>
         <?php endforeach; ?>
       </select>
     </div>

     <!--  -->
     <div class="form-group">
       <label for=""><?php echo lang('Region'); ?></label>
       <select class="form-control multiple-select" name="store-regions" multiple data-live-search="true"  title="<?php echo lang('Choose_region'); ?>">
         <?php foreach ($regions as $key => $region): ?>
           <option value="<?php echo $region['id']; ?>"
             <?php if (isset($_GET["regions"]) && in_array($region['id'],explode(",", $_GET["regions"]))) { echo "selected"; } ?>><?php echo $region['name']; ?></option>
         <?php endforeach; ?>
       </select>
     </div>
     <?php
     // if (isset($_GET['regions']) && in_array($region['id'],implode(',', $_GET['regions']))) { echo "selected"; }
     ?>


     <!-- <div class="filter-range">
       <label for=""><?php echo lang('Price_range'); ?></label><br>
       <input type="number" step="0.01" placeholder="Min..." id="filter_min" class="form-control">
       <input type="number" step="0.01" placeholder="Max..." id="filter_max" class="form-control">
     </div> -->
     <div class="form-group">
       <a href="javascript:void(0)"
           class="btn btn-default pull-right"
            id="store_filter_btn">Filter</a>
     </div>
   </div>
       <div class="col-md-9">
         <div class="row">
           <div class="col-md-12 cs_main_title">
             <h3>
               <?php echo $page['title']; ?>
             </h3>
             <p>We are a team of designers and developers that create high quality</p>
           </div>
           <div class="col-md-12">
             <div class="row" id="<?php echo $page['main_id']; ?>"></div>
           </div>
         </div>
         <div class="loading">
           <img src="<?php echo assets('img/gifs/loading.gif') ?>" alt="">
         </div>
         <nav class="stores-pagination">
           <ul class="pagination justify-content-center">
             <li class="page-item">
               <a class="page-link" href="javascript:void(0)" aria-label="Previous">
                 <span aria-hidden="true">&laquo;</span>
                 <span class="sr-only"><?php echo lang('Previous'); ?></span>
               </a>
             </li>
             <li class="page-item active">
               <a class="page-link" href="javascript:void(0)">1</a>
             </li>
             <li class="page-item">
               <a class="page-link" href="javascript:void(0)">2</a>
             </li>
             <li class="page-item">
               <a class="page-link" href="javascript:void(0)">3</a>
             </li>
             <li class="page-item">
               <a class="page-link" href="javascript:void(0)" aria-label="Next">
                 <span aria-hidden="true">&raquo;</span>
                 <span class="sr-only"><?php echo lang('Next'); ?></span>
               </a>
             </li>
           </ul>
         </nav>
       </div>
     </div>
   </div>

<?php
  $this->extraJS = '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>';
  $this->load->view('layouts/foot');
?>
