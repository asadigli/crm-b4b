<script src="<?= assets("js/libs/jquery.min.js", true, true) ?>"></script>
<?php if ($this->extraJS): ?>
  <?php foreach ($this->extraJS as $key => $item): ?>
    <script src="<?= assets($item) ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
