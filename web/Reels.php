<?php
$tituloPagina = "Reels";
require "header.php";

$centros = leerCentros();
?>

      <section class="seccion">
        <div class="container">
            <h2 class="titulo-seccion">Reels de los centros</h2>
            <p class="subtitulo-seccion">Vídeos cortos para conocer a los centros en menos de un minuto.</p>
            <div class="row g-4 justify-content-center">
                <?php foreach ($reels as $reel) { ?>
                <div class="col-10 col-sm-6 col-lg-3">
                    <div class="reel">
                        <?php if ($reel["video"] != "" && file_exists(CARPETA_VIDEOS . $reel["video"])) { ?>
                            <video class="reel-video" src="<?php echo CARPETA_VIDEOS . $reel["video"]; ?>" controls poster="<?php echo CARPETA_IMG . $reel["imagen"]; ?>"></video>
                        <?php } else { ?>
                            <?php if ($reel["imagen"] != "" && file_exists(CARPETA_IMG . $reel["imagen"])) { ?>
                                <img class="reel-video" src="<?php echo CARPETA_IMG . $reel["imagen"]; ?>" alt="">
                            <?php } ?>
                            <span class="reel-etiqueta"><?php echo $reel["titulo"]; ?></span>
                            <button type="button" class="reel-play">&#9654;</button>
                            <div class="reel-texto">
                                <h3><?php echo $reel["titulo"]; ?></h3>
                                <p class="reel-centro"><?php echo $reel["centro"]; ?></p>
                                <p class="reel-frase">“<?php echo $reel["frase"]; ?>”</p>
                                <div class="reel-barra"><span></span></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>

            <h3 class="text-center mt-5 mb-4 fs-5">Accede directamente a cada centro</h3>
            <div class="row g-3">
                <?php foreach ($centros as $centro) { ?>
                <div class="col-6 col-lg-3">
                    <a class="enlace-centro" href="<?php echo $centro["url"]; ?>">
                        <span><?php echo $centro["nombre"]; ?></span><span>&#128279;</span>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
      </section>

<?php require "footer.php"; ?>
    </body>
</html>