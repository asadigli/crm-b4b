<script src="<?php echo assets('vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo assets('vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="//cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
<script src="<?php echo assets('js/libs/sweetalert2.all.min.js'); ?>"></script>
<script src="<?php echo assets('js/libs/toastr.min.js'); ?>"></script>
<script src="<?php echo assets('js/libs/bootstrap-tagsinput.js'); ?>"></script>
<script src="<?php echo assets('js/libs/bootstrap-tagsinput.min.js'); ?>"></script>
<script type="module" src="<?php echo assets('js/admin.js',$this->config->item("is_production")); ?>"></script>
<script src="<?php echo assets('js/file_uploader.js'); ?>"></script>

<?php echo $this->extraJSBefore; ?>
<?php echo $this->extraJS; ?>
<script src="<?php echo assets('js/jquery/jquery.fancybox.js'); ?>"></script>
<script type="text/javascript" src="<?php echo assets('js/libs/select2.min.js'); ?>">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
</body>

</html>
