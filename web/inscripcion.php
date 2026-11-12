<?php
$tituloPagina = "Inscripción";
require_once "config.php";
require_once "funciones.php";

$centros = leerCentros();

// guardar la inscripcion
// cada linea: fecha|nombre|correo|centro
$mensaje = "";
$tipo = "";
$nombre = "";
$email = "";
$centroElegido = "";

if (isset($_POST["enviar"])) {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $centroElegido = $_POST["centro"];

    if ($nombre == "" || $email == "") {
        $mensaje = "Rellena el nombre y el correo electrónico.";
        $tipo = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
        $tipo = "danger";
    } elseif (!in_array($centroElegido, array_column($centros, "nombre"))) {
        $mensaje = "Elige un centro de la lista.";
        $tipo = "danger";
    } else {
        // el fichero es .php y empieza con exit: si alguien lo abre en el navegador no ve los datos
        $nuevo = !file_exists(FICHERO_INSCRIPCIONES);
        $fichero = fopen(FICHERO_INSCRIPCIONES, "a");
        if ($nuevo) {
            fwrite($fichero, "<?php exit; ?>\n");
        }
        // sin | ni saltos de linea, que son los separadores del fichero
        $limpio = str_replace(array("|", "\r", "\n"), " ", array($nombre, $email, $centroElegido));
        fwrite($fichero, date("Y-m-d H:i") . "|" . implode("|", $limpio) . "\n");
        fclose($fichero);

        $mensaje = "¡Inscripción guardada! Nos vemos en la feria, " . htmlspecialchars($nombre) . ".";
        $tipo = "success";
        $nombre = "";
        $email = "";
        $centroElegido = "";
    }
}

// contar inscritos
$totalInscritos = 0;
if (file_exists(FICHERO_INSCRIPCIONES)) {
    $totalInscritos = count(file(FICHERO_INSCRIPCIONES)) - 1; // la primera linea es el exit
}

require "header.php";
?>

      <section class="seccion">
        <div class="container">
            <h2 class="titulo-seccion">Inscríbete</h2>
            <p class="subtitulo-seccion">
                <?php if ($totalInscritos == 0) { ?>
                    Sé la primera persona en inscribirte.
                <?php } elseif ($totalInscritos == 1) { ?>
                    Ya hay 1 persona inscrita. ¡Reserva tu plaza!
                <?php } else { ?>
                    Ya hay <?php echo $totalInscritos; ?> personas inscritas. ¡Reserva tu plaza!
                <?php } ?>
            </p>
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <?php if ($mensaje != "") { ?>
                        <div class="alert alert-<?php echo $tipo; ?>"><?php echo $mensaje; ?></div>
                    <?php } ?>
                    <form method="post" action="inscripcion.php" class="formulario">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="centro" class="form-label">Centro</label>
                            <select class="form-select" id="centro" name="centro" required>
                                <option value="">Elige tu centro</option>
                                <?php foreach ($centros as $centro) { ?>
                                <option value="<?php echo $centro["nombre"]; ?>"><?php echo $centro["nombre"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" name="enviar" class="btn btn-feria w-100">Enviar inscripción</button>
                    </form>
                </div>
            </div>
        </div>
      </section>

<?php require "footer.php"; ?>
    </body>
</html>