<!-- pie -->
      <footer class="pie">
        <div class="container">
            <div class="pie-enlaces">
                <?php foreach ($enlacesPie as $texto => $url) { ?>
                <a href="<?php echo $url; ?>"><?php echo $texto; ?></a>
                <?php } ?>
            </div>
            <p class="mb-1">&#9742; <?php echo $telefono; ?> · &#9993; <a href="mailto:<?php echo $correo; ?>"><?php echo $correo; ?></a></p>
            <p class="mb-0 pie-copy">&copy; <?php echo date("Y"); ?> <?php echo TITULO; ?></p>
        </div>
      </footer>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>