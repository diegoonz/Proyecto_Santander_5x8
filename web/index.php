<?php
$tituloPagina = "Inicio";
require "header.php";

// fecha de la feria
$inicio = strtotime(FECHA_INICIO);
$faltan = $inicio - time();
if ($faltan <= 0) {
    $textoContador = "La feria ya ha comenzado";
} elseif ($faltan < 7 * 86400) {
    $textoContador = "¡Queda menos de una semana!";
} else {
    $textoContador = "Faltan para el inicio de la feria";
}
?>

      <!-- titulo -->
      <header class="cabecera">
        <div class="container">
            <span class="cabecera-etiqueta">Edición 2027 · 100 % online</span>
            <h1><?php echo TITULO; ?></h1>
            <p><?php echo LEMA; ?></p>
            <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="centros.php" class="btn btn-feria btn-feria-amarillo">Ver centros</a>
                <a href="programa.php" class="btn btn-feria btn-feria-borde">Ver programa</a>
            </div>
        </div>
      </header>

      <!-- contador -->
      <section class="contador-zona">
        <div class="container">
            <h2><?php echo $textoContador; ?></h2>
            <div class="contador">
                <div class="contador-caja"><span class="contador-numero" id="dias">00</span><span class="contador-texto">Días</span></div>
                <div class="contador-caja"><span class="contador-numero" id="horas">00</span><span class="contador-texto">Horas</span></div>
                <div class="contador-caja"><span class="contador-numero" id="minutos">00</span><span class="contador-texto">Minutos</span></div>
                <div class="contador-caja"><span class="contador-numero" id="segundos">00</span><span class="contador-texto">Segundos</span></div>
            </div>
            <a href="inscripcion.php" class="btn btn-feria btn-feria-amarillo btn-grande">Inscríbete</a>
        </div>
      </section>

<?php require "footer.php"; ?>

      <script>
        var inicio = <?php echo $inicio * 1000; ?>;

        function cifras(n) {
            if (n < 10) {
                return "0" + n;
            }
            return n;
        }

        function contador() {
            var falta = inicio - new Date().getTime();
            if (falta < 0) {
                falta = 0;
            }
            var seg = Math.floor(falta / 1000);
            var min = Math.floor(seg / 60);
            var horas = Math.floor(min / 60);
            var dias = Math.floor(horas / 24);

            document.getElementById("dias").innerHTML = cifras(dias);
            document.getElementById("horas").innerHTML = cifras(horas % 24);
            document.getElementById("minutos").innerHTML = cifras(min % 60);
            document.getElementById("segundos").innerHTML = cifras(seg % 60);
        }

        contador();
        setInterval(contador, 1000);
      </script>
    </body>
</html>