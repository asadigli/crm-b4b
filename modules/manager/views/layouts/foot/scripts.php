<script src="<?= assets("js/libs/jquery.min.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/vendors.js", true, true) ?>"></script>
<!-- <script src="<?= assets("js/apexcharts.js") ?>"></script> -->
<script src="<?= assets("js/custom.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/sweetalert2.min.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/select2.min.js", true, true) ?>"></script>
<!-- <script src="<?= assets("js/template.js") ?>"></script> -->

<script src="<?= assets("js/loader.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/jquery.inputmask.js", true, true); ?>"></script>
<script src="<?= assets("js/master.js") ?>"></script>

<?php if ($this->extraJS): ?>
  <?php foreach ($this->extraJS as $key => $item): ?>
    <script src="<?= assets($item) ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>

<?= $this->lang_dom ?>
