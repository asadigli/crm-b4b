<?php if(isset($nav["setup"]) && $nav["setup"] && isset($nav["roles"])): ?>
     <?php $case = false;
     $all_links = [];
     if (isset($nav["childs"]) && $nav["childs"]) {
       $all_links = array_map(function($i) {
           return $i["path"];
       },$nav["childs"]);
     }

     if (in_array(uri_string(),$all_links)) {
       $case = true;
     }

      ?>
     <li class="nav-item<?= (uri_string() === $nav["path"]) || (!uri_string() && in_array($nav["path"],["","home"]) )|| $case ? " current" : "" ?>">
       <a class="nav-link" href="<?= (isset($nav["path"]) && ($nav["path"] !== "javascript:void(0)")) ? path_local($nav["path"]) : "javascript:void(0)" ?>">
         <?= isset($nav["icon"]) ? "<i class='{$nav["icon"]}'></i>" : "" ?>
         <span class="menu-title"><?= isset($nav["name"]) ? lang($nav["name"]) : "" ?></span>
       </a>
       <?php if (isset($nav["childs"])): ?>
         <ul role="group" aria-hidden="true" aria-expanded="false" class="sm-nowrap">
           <?php foreach($nav["childs"] as $subnav): ?>
             <?php if (Auth::checkRole($subnav["roles"])): ?>
               <li>
                 <a href="<?= (isset($subnav["path"]) && ($subnav["path"] !== "javascript:void(0)")) ? path_local($subnav["path"]) : "javascript:void(0)" ?>">
                   <?= isset($subnav["icon"]) ? "<i class='{$subnav["icon"]}'></i>" : "" ?>
                   <?= isset($subnav["name"]) ? lang($subnav["name"]) : "" ?>
                 </a>
               </li>
             <?php endif; ?>
           <?php endforeach; ?>
         </ul>
       <?php endif; ?>
     </li>
<?php endif; ?>
