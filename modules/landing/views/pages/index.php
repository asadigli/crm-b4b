<?php
  $this->page_title = $page['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>
 <div class="container">
   <div class="row cs_no_pad">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-12 cs_while_content">
           <div class="row">
             <div class="col-md-12 cs_whc_title">
               <div class="row">
                 <div class="col-md-8">
                   <h1><?php echo $page['title']; ?></h1>
                 </div>
               </div>
             </div>
             <div class="col-md-12 cs_whc_content">
               <p><?php echo $data['body']; ?></p>
               <div class="accordion" id="pageList">
                  <?php foreach ($data['tabs'] as $key => $tb): ?>
                    <div class="card">
                      <div class="card-header page-list" id="part<?php echo $tb->id; ?>">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#bodypart<?php echo $tb->id; ?>" aria-expanded="true" aria-controls="bodypart<?php echo $tb->id; ?>">
                            <?php echo $tb->title; ?>
                          </button>
                      </div>
                      <div id="bodypart<?php echo $tb->id; ?>" class="collapse" aria-labelledby="part<?php echo $tb->id; ?>" data-parent="#pageList">
                        <div class="card-body">
                          <?php echo $tb->body; ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
               </div>
             </div>
           </div>

         </div>
       </div>

     </div>
   </div>
 </div>

<?php $this->load->view('layouts/foot') ?>
