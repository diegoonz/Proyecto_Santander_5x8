<?php
require "config.php";

// leer los centros del fichero
// cada linea: nombre|ciudad|info|siglas|enlace|imagen
$centros = array();
$ciudades = array();

if (file_exists(FICHERO_CENTROS)) {
    $lineas = file(FICHERO_CENTROS);
    sort($lineas);

    foreach ($lineas as $linea) {
        $campos = explode("|", trim($linea));
        if (count($campos) >= 5) {
            $imagen = "";
            if (count($campos) == 6) {
                $imagen = $campos[5];
            }

            $centro = array(
                "nombre" => $campos[0],
                "ciudad" => $campos[1],
                "info" => $campos[2],
                "siglas" => $campos[3],
                "url" => $campos[4],
                "imagen" => $imagen
            );
            $centros[] = $centro;

            if (!in_array($campos[1], $ciudades)) {
                $ciudades[] = $campos[1];
            }
        }
    }
}

// guardar la inscripcion
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
    } elseif ($centroElegido == "") {
        $mensaje = "Elige un centro de la lista.";
        $tipo = "danger";
    } else {
        $fichero = fopen(FICHERO_INSCRIPCIONES, "a");
        fwrite($fichero, date("Y-m-d H:i") . "|" . $nombre . "|" . $email . "|" . $centroElegido . "\n");
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
    $totalInscritos = count(file(FICHERO_INSCRIPCIONES));
}

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
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo TITULO; ?></title>
        <meta charset="UTF-8">
        <meta name="keywords" content="feria virtual, La Salle, Business Match, centros, empresas">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="hola.css" rel="stylesheet" type="text/css">
    </head>
    <body>

      <!-- menu -->
      <nav class="navbar navbar-expand-lg navbar-feria sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#inicio"><span class="marca-logo">LS</span> Business Match</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#MenuNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="MenuNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <?php foreach ($menu as $id => $texto) { ?>
                    <li class="nav-item"><a class="nav-link" href="#<?php echo $id; ?>"><?php echo $texto; ?></a></li>
                    <?php } ?>
                    <li class="nav-item ms-lg-2"><a class="btn btn-feria btn-feria-amarillo" href="#inscripcion">Inscríbete</a></li>
                </ul>
            </div>
        </div>
      </nav>

      <!-- titulo -->
      <header class="cabecera" id="inicio">
        <div class="container">
            <span class="cabecera-etiqueta">Edición 2027 · 100 % online</span>
            <h1><?php echo TITULO; ?></h1>
            <p><?php echo LEMA; ?></p>
            <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="#centros" class="btn btn-feria btn-feria-amarillo">Ver centros</a>
                <a href="#programa" class="btn btn-feria btn-feria-borde">Ver programa</a>
            </div>
        </div>
      </header>

      <!-- contador -->
      <section class="contador-zona" id="contador">
        <div class="container">
            <h2><?php echo $textoContador; ?></h2>
            <div class="contador">
                <div class="contador-caja"><span class="contador-numero" id="dias">00</span><span class="contador-texto">Días</span></div>
                <div class="contador-caja"><span class="contador-numero" id="horas">00</span><span class="contador-texto">Horas</span></div>
                <div class="contador-caja"><span class="contador-numero" id="minutos">00</span><span class="contador-texto">Minutos</span></div>
                <div class="contador-caja"><span class="contador-numero" id="segundos">00</span><span class="contador-texto">Segundos</span></div>
            </div>
            <a href="#inscripcion" class="btn btn-feria btn-feria-amarillo btn-grande">Inscríbete</a>
        </div>
      </section>

      <!-- centros -->
      <section class="seccion" id="centros">
        <div class="container">
            <h2 class="titulo-seccion">Centros participantes</h2>
            <p class="subtitulo-seccion">
                <?php echo count($centros); ?> centros de <?php echo count($ciudades); ?> ciudades
                (<?php echo implode(", ", $ciudades); ?>).
            </p>
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

      <!-- programa -->
      <section class="seccion seccion-gris" id="programa">
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

      <!-- foros -->
      <section class="seccion" id="foros">
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

      <!-- reels -->
      <section class="seccion seccion-gris" id="reels">
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

      <!-- inscripcion -->
      <section class="seccion" id="inscripcion">
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
                    <form method="post" action="index.php#inscripcion" class="formulario">
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

      <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
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