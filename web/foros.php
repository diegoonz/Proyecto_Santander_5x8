<?php
$tituloPagina = "Foros";
require "header.php";
?>

      <section class="seccion">
        <div class="container">
            <h2 class="titulo-seccion">Foros de otros años</h2>
            <p class="subtitulo-seccion">Un repaso a los mejores momentos de las ediciones anteriores.</p>
            <div class="row g-4">
                <?php foreach ($foros as $foro) { ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="foro-tarjeta">
                        <?php if ($foro["imagen"] != "" && file_exists(CARPETA_IMG . $foro["imagen"])) { ?>
                            <img class="foro-imagen" src="<?php echo CARPETA_IMG . $foro["imagen"]; ?>" alt="<?php echo $foro["titulo"]; ?>">
                        <?php } else { ?>
                            <div class="foro-imagen"><?php echo $foro["tipo"]; ?></div>
                        <?php } ?>
                        <div class="card-body">
                            <span class="foro-edicion"><?php echo $foro["tipo"]; ?> · <?php echo $foro["anio"]; ?></span>
                            <h3><?php echo $foro["titulo"]; ?></h3>
                            <p><?php echo $foro["texto"]; ?></p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
      </section>

<?php require "footer.php"; ?>
    </body>
</html>