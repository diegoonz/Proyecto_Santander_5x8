<?php
$tituloPagina = "Centros";
require "header.php";

$centros = leerCentros();
$ciudades = leerCiudades($centros);
?>

      <section class="seccion">
        <div class="container">
            <h2 class="titulo-seccion">Centros participantes</h2>
            <?php if (count($centros) == 0) { ?>
                <p class="subtitulo-seccion">Listado de centros pendiente de confirmación.</p>
            <?php } else { ?>
            <p class="subtitulo-seccion">
                <?php echo count($centros); ?> centros de <?php echo count($ciudades); ?> ciudades
                (<?php echo implode(", ", $ciudades); ?>).
            </p>
            <?php } ?>

            <div class="row g-4">
                <?php foreach ($centros as $centro) { ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="tarjeta-centro">
                        <?php if ($centro["imagen"] != "" && file_exists(CARPETA_IMG . $centro["imagen"])) { ?>
                            <img class="centro-imagen" src="<?php echo CARPETA_IMG . $centro["imagen"]; ?>" alt="<?php echo $centro["nombre"]; ?>">
                        <?php } else { ?>
                            <div class="centro-imagen"><?php echo $centro["siglas"]; ?></div>
                        <?php } ?>
                        <div class="card-body">
                            <h3><?php echo $centro["nombre"]; ?></h3>
                            <p class="centro-ciudad">&#128205; <?php echo $centro["ciudad"]; ?></p>
                            <p class="centro-info"><?php echo $centro["info"]; ?></p>
                            <?php if ($centro["url"] == "#") { ?>
                                <a href="#" class="btn btn-feria btn-sm">Ver perfil</a>
                            <?php } else { ?>
                                <a href="<?php echo $centro["url"]; ?>" class="btn btn-feria btn-sm" target="_blank">Ver perfil</a>
                            <?php } ?>
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