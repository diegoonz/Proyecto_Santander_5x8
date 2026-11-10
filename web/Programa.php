<?php
$tituloPagina = "Programa";
require "header.php";
?>

      <section class="seccion">
        <div class="container">
            <h2 class="titulo-seccion">Programa de la feria</h2>
            <p class="subtitulo-seccion"><?php echo $programaDia; ?></p>
            <div class="programa-scroll">
                <div class="programa">
                    <?php for ($i = 0; $i < count($programa); $i++) { ?>
                    <div class="programa-paso">
                        <div class="programa-punto"><?php echo $i + 1; ?></div>
                        <span class="programa-hora"><?php echo $programa[$i]["hora"]; ?></span>
                        <span class="programa-titulo"><?php echo $programa[$i]["titulo"]; ?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
      </section>

<?php require "footer.php"; ?>
    </body>
</html>