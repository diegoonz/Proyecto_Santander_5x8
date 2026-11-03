<?php
/* =====================================================================
   INDEX.PHP
   Arriba: la LÓGICA (cargar datos, leer ficheros, procesar el formulario).
   Abajo:  el HTML, que solo recorre las variables con foreach.
   El CONTENIDO (textos, horas, enlaces) se cambia en config.php.
   ===================================================================== */
require "config.php";

// Función para escribir texto de forma segura en el HTML
function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, "UTF-8");
}

// Igual que e(), pero si MOSTRAR_PENDIENTES está en true lo subraya en amarillo.
// Se usa para los datos de ejemplo que hay que cambiar por los reales.
function cambiar($texto) {
    if (MOSTRAR_PENDIENTES) {
        return '<mark class="pendiente" title="Dato de ejemplo: cámbialo por el real">' . e($texto) . '</mark>';
    }
    return e($texto);
}

// Devuelve true si el archivo existe (sirve para imágenes y vídeos opcionales)
function hayArchivo($carpeta, $archivo) {
    return $archivo !== "" && file_exists($carpeta . $archivo);
}

// Si la imagen es de ejemplo (su nombre empieza por "ejemplo-"), devuelve una etiqueta de aviso
function avisoImagen($archivo) {
    if (MOSTRAR_PENDIENTES && str_starts_with($archivo, "ejemplo-")) {
        return '<span class="aviso-imagen">Imagen de ejemplo</span>';
    }
    return "";
}

// Un enlace "#" todavía no lleva a ningún sitio: hay que poner el real
function enlacePendiente($url) {
    return $url === "#" || $url === "";
}

/* =====================================================
   FICHEROS (1): leer los centros desde centros.txt
   Cada línea:  nombre|ciudad|info|siglas|enlace|imagen
   (el último campo, imagen, es opcional)
   ===================================================== */
$centros = [];
if (file_exists(FICHERO_CENTROS) && is_readable(FICHERO_CENTROS)) {
    $lineas = file(FICHERO_CENTROS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    sort($lineas); // orden alfabético
    foreach ($lineas as $linea) {
        // Las líneas que empiezan por # son comentarios: las saltamos
        if (str_starts_with(trim($linea), "#")) {
            continue;
        }
        $campos = explode("|", $linea);
        if (count($campos) >= 5) {
            $centros[] = [
                "nombre" => trim($campos[0]),
                "ciudad" => trim($campos[1]),
                "info"   => trim($campos[2]),
                "siglas" => trim($campos[3]),
                "url"    => trim($campos[4]),
                "imagen" => trim($campos[5] ?? "")   // si no hay 6º campo, queda vacío
            ];
        }
    }
}
$nombresCentros = array_column($centros, "nombre");
$ciudades = array_unique(array_column($centros, "ciudad"));

/* =====================================================
   FICHEROS (2): guardar las inscripciones en inscripciones.txt
   Cada línea:  fecha hora|nombre|correo|centro
   ===================================================== */
$mensaje = "";
$tipoMensaje = "";
$nombre = "";
$email = "";
$centroElegido = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $centroElegido = $_POST["centro"] ?? "";

    if (empty($nombre) || empty($email)) {
        $mensaje = "Rellena el nombre y el correo electrónico.";
        $tipoMensaje = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
        $tipoMensaje = "danger";
    } elseif (!in_array($centroElegido, $nombresCentros)) {
        $mensaje = "Elige un centro de la lista.";
        $tipoMensaje = "danger";
    } else {
        // Quitamos el separador | y los saltos de línea para no romper el fichero
        $nombreLimpio = str_replace(["|", "\n", "\r"], " ", $nombre);
        $linea = date("Y-m-d H:i") . "|" . $nombreLimpio . "|" . $email . "|" . $centroElegido . PHP_EOL;

        // FILE_APPEND añade al final sin borrar lo anterior; LOCK_EX evita escrituras a la vez
        if (file_put_contents(FICHERO_INSCRIPCIONES, $linea, FILE_APPEND | LOCK_EX) !== false) {
            $mensaje = "¡Inscripción guardada! Nos vemos en la feria, " . $nombreLimpio . ".";
            $tipoMensaje = "success";
            $nombre = $email = $centroElegido = ""; // vaciamos el formulario
        } else {
            $mensaje = "No se pudo guardar la inscripción. Revisa los permisos de la carpeta.";
            $tipoMensaje = "danger";
        }
    }
}

// Contamos las inscripciones leyendo el fichero (si existe)
$totalInscritos = 0;
if (file_exists(FICHERO_INSCRIPCIONES)) {
    $totalInscritos = count(file(FICHERO_INSCRIPCIONES, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}

/* =====================================================
   Estado de la feria (match, necesita PHP 8)
   ===================================================== */
$timestampInicio = strtotime(FERIA_INICIO);
$faltan = $timestampInicio - time();
$textoContador = match (true) {
    $faltan <= 0         => "La feria ya ha comenzado",
    $faltan < 7 * 86400  => "¡Queda menos de una semana!",
    default              => "Faltan para el inicio de la feria"
};
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo e(FERIA_TITULO); ?></title>
        <meta charset="UTF-8">
        <meta name="keywords" content="feria virtual, La Salle, Business Match, centros, empresas">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Tipografía moderna (Montserrat). Si no hay internet, se usa Segoe UI/Arial -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
        <!-- Bootstrap COMPLETO (bootstrap-grid.min.css solo trae la rejilla) -->
        <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="hola.css" rel="stylesheet" type="text/css">
    </head>
    <body>

      <?php if (MOSTRAR_PENDIENTES): ?>
      <!-- Aviso de borrador: desaparece al poner MOSTRAR_PENDIENTES en false (config.php) -->
      <div class="aviso-borrador">
        <b>Borrador.</b> Lo <mark class="pendiente">subrayado en amarillo</mark> son datos de ejemplo.
        Cámbialos en <code>config.php</code> y <code>centros.txt</code> (busca «CAMBIAR»).
      </div>
      <?php endif; ?>

      <!-- ================= MENÚ (generado con el array $menu) ================= -->
      <nav class="navbar navbar-expand-lg navbar-feria sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#inicio"><span class="marca-logo">LS</span> Business Match</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#MenuNav" aria-controls="MenuNav" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="MenuNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <?php foreach ($menu as $id => $texto): ?>
                    <li class="nav-item"><a class="nav-link" href="#<?php echo e($id); ?>"><?php echo e($texto); ?></a></li>
                    <?php endforeach; ?>
                    <li class="nav-item ms-lg-2"><a class="btn btn-feria btn-feria-amarillo" href="#inscripcion">Inscríbete</a></li>
                </ul>
            </div>
        </div>
      </nav>

      <!-- ================= 2. TÍTULO ================= -->
      <header class="cabecera" id="inicio">
        <div class="container">
            <span class="cabecera-etiqueta">Edición 2027 · 100 % online</span>
            <h1><?php echo e(FERIA_TITULO); ?></h1>
            <p><?php echo e(FERIA_LEMA); ?></p>
            <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="#centros" class="btn btn-feria btn-feria-amarillo">Ver centros</a>
                <a href="#programa" class="btn btn-feria btn-feria-borde">Ver programa</a>
            </div>
        </div>
      </header>

      <!-- ================= 6. CONTADOR ================= -->
      <section class="contador-zona" id="contador">
        <div class="container">
            <h2><?php echo e($textoContador); ?></h2>
            <?php if (MOSTRAR_PENDIENTES): ?>
                <p class="contador-fecha">Fecha de ejemplo: <?php echo cambiar(date("d/m/Y H:i", $timestampInicio)); ?></p>
            <?php endif; ?>
            <div class="contador">
                <div class="contador-caja"><span class="contador-numero" id="dias">00</span><span class="contador-texto">Días</span></div>
                <div class="contador-caja"><span class="contador-numero" id="horas">00</span><span class="contador-texto">Horas</span></div>
                <div class="contador-caja"><span class="contador-numero" id="minutos">00</span><span class="contador-texto">Minutos</span></div>
                <div class="contador-caja"><span class="contador-numero" id="segundos">00</span><span class="contador-texto">Segundos</span></div>
            </div>
            <a href="#inscripcion" class="btn btn-feria btn-feria-amarillo btn-grande">Inscríbete</a>
        </div>
      </section>

      <!-- ================= 4. CENTROS PARTICIPANTES (leídos de centros.txt) ================= -->
      <section class="seccion" id="centros">
        <div class="container">
            <h2 class="titulo-seccion">Centros participantes</h2>
            <p class="subtitulo-seccion">
                <?php echo count($centros); ?> centros de <?php echo count($ciudades); ?> ciudades
                (<?php echo e(implode(", ", $ciudades)); ?>).
            </p>
            <?php if (empty($centros)): ?>
                <p class="text-center">No se pudo leer <?php echo e(FICHERO_CENTROS); ?>. Comprueba que está en la misma carpeta que index.php.</p>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($centros as $centro): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="tarjeta-centro">
                        <?php if (hayArchivo(CARPETA_IMG, $centro["imagen"])): ?>
                            <img class="centro-imagen" src="<?php echo e(CARPETA_IMG . $centro["imagen"]); ?>" alt="<?php echo e($centro["nombre"]); ?>">
                            <?php echo avisoImagen($centro["imagen"]); ?>
                        <?php else: ?>
                            <div class="centro-imagen"><?php echo e($centro["siglas"]); ?></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3><?php echo e($centro["nombre"]); ?></h3>
                            <p class="centro-ciudad">&#128205; <?php echo e($centro["ciudad"]); ?></p>
                            <p class="centro-info"><?php echo cambiar($centro["info"]); ?></p>
                            <?php if (enlacePendiente($centro["url"])): ?>
                                <a href="#" class="btn btn-feria btn-sm">Ver perfil</a> <?php echo cambiar("falta el enlace"); ?>
                            <?php else: ?>
                                <a href="<?php echo e($centro["url"]); ?>" class="btn btn-feria btn-sm" target="_blank" rel="noopener">Ver perfil</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
      </section>

      <!-- ================= 5. PROGRAMA HORIZONTAL (array $programa) ================= -->
      <section class="seccion seccion-gris" id="programa">
        <div class="container">
            <h2 class="titulo-seccion">Programa de la feria</h2>
            <p class="subtitulo-seccion"><?php echo e($programaDia); ?></p>
            <div class="programa-scroll">
                <div class="programa">
                    <?php foreach ($programa as $posicion => $paso): ?>
                    <div class="programa-paso">
                        <div class="programa-punto"><?php echo $posicion + 1; ?></div>
                        <span class="programa-hora"><?php echo cambiar($paso["hora"]); ?></span>
                        <span class="programa-titulo"><?php echo cambiar($paso["titulo"]); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
      </section>

      <!-- ================= 5. FOROS DE OTROS AÑOS (array $foros) ================= -->
      <section class="seccion" id="foros">
        <div class="container">
            <h2 class="titulo-seccion">Foros de otros años</h2>
            <p class="subtitulo-seccion">Un repaso a los mejores momentos de las ediciones anteriores.</p>
            <div class="row g-4">
                <?php foreach ($foros as $foro): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="foro-tarjeta">
                        <?php if (hayArchivo(CARPETA_IMG, $foro["imagen"])): ?>
                            <img class="foro-imagen" src="<?php echo e(CARPETA_IMG . $foro["imagen"]); ?>" alt="<?php echo e($foro["titulo"]); ?>">
                            <?php echo avisoImagen($foro["imagen"]); ?>
                        <?php else: ?>
                            <div class="foro-imagen"><?php echo e($foro["tipo"]); ?></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="foro-edicion"><?php echo e($foro["tipo"]); ?> · <?php echo (int)$foro["anio"]; ?></span>
                            <h3><?php echo cambiar($foro["titulo"]); ?></h3>
                            <p><?php echo cambiar($foro["texto"]); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
      </section>

      <!-- ================= 7. REELS Y ENLACES A LOS CENTROS ================= -->
      <section class="seccion seccion-gris" id="reels">
        <div class="container">
            <h2 class="titulo-seccion">Reels de los centros</h2>
            <p class="subtitulo-seccion">Vídeos cortos para conocer a los centros en menos de un minuto.</p>
            <div class="row g-4 justify-content-center">
                <?php foreach ($reels as $reel): ?>
                <div class="col-10 col-sm-6 col-lg-3">
                    <div class="reel">
                        <?php if (hayArchivo(CARPETA_VIDEOS, $reel["video"])): ?>
                            <!-- Hay vídeo: se muestra el reproductor -->
                            <video class="reel-video" src="<?php echo e(CARPETA_VIDEOS . $reel["video"]); ?>" controls preload="metadata"
                                <?php if (hayArchivo(CARPETA_IMG, $reel["imagen"])): ?>poster="<?php echo e(CARPETA_IMG . $reel["imagen"]); ?>"<?php endif; ?>></video>
                        <?php else: ?>
                            <!-- No hay vídeo todavía: reel de ejemplo -->
                            <?php if (hayArchivo(CARPETA_IMG, $reel["imagen"])): ?>
                                <img class="reel-video" src="<?php echo e(CARPETA_IMG . $reel["imagen"]); ?>" alt="">
                            <?php endif; ?>
                            <span class="reel-etiqueta"><?php echo e($reel["titulo"]); ?></span>
                            <?php if (MOSTRAR_PENDIENTES): ?><span class="aviso-imagen">Falta el vídeo</span><?php endif; ?>
                            <button type="button" class="reel-play" aria-label="Reproducir reel: <?php echo e($reel["titulo"]); ?>">&#9654;</button>
                            <div class="reel-texto">
                                <h3><?php echo e($reel["titulo"]); ?></h3>
                                <p class="reel-centro"><?php echo e($reel["centro"]); ?></p>
                                <p class="reel-frase">“<?php echo cambiar($reel["frase"]); ?>”</p>
                                <div class="reel-barra"><span></span></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <h3 class="text-center mt-5 mb-4 fs-5">Accede directamente a cada centro</h3>
            <div class="row g-3">
                <?php foreach ($centros as $centro): ?>
                <div class="col-6 col-lg-3">
                    <!-- Si el enlace está en "#", se marca con la clase enlace-pendiente -->
                    <a class="enlace-centro <?php echo enlacePendiente($centro["url"]) && MOSTRAR_PENDIENTES ? "enlace-pendiente" : ""; ?>" href="<?php echo e($centro["url"]); ?>" <?php echo enlacePendiente($centro["url"]) ? "" : 'target="_blank" rel="noopener"'; ?>>
                        <span><?php echo e($centro["nombre"]); ?></span><span aria-hidden="true">&#128279;</span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
      </section>

      <!-- ================= INSCRIPCIÓN (se guarda en inscripciones.txt) ================= -->
      <section class="seccion" id="inscripcion">
        <div class="container">
            <h2 class="titulo-seccion">Inscríbete</h2>
            <p class="subtitulo-seccion">
                <?php if ($totalInscritos > 0): ?>
                    Ya hay <?php echo $totalInscritos; ?> <?php echo $totalInscritos === 1 ? "persona inscrita" : "personas inscritas"; ?>. ¡Reserva tu plaza!
                <?php else: ?>
                    Sé la primera persona en inscribirte.
                <?php endif; ?>
            </p>
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?php echo e($tipoMensaje); ?>" role="alert"><?php echo e($mensaje); ?></div>
                    <?php endif; ?>
                    <form method="post" action="index.php#inscripcion" class="formulario">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo e($nombre); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo e($email); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="centro" class="form-label">Centro</label>
                            <select class="form-select" id="centro" name="centro" required>
                                <option value="">Elige tu centro</option>
                                <?php foreach ($nombresCentros as $nombreCentro): ?>
                                <option value="<?php echo e($nombreCentro); ?>" <?php echo $nombreCentro === $centroElegido ? "selected" : ""; ?>><?php echo e($nombreCentro); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-feria w-100">Enviar inscripción</button>
                    </form>
                </div>
            </div>
        </div>
      </section>

      <!-- ================= PIE (arrays $enlacesPie y $contacto) ================= -->
      <footer class="pie">
        <div class="container">
            <div class="pie-enlaces">
                <?php foreach ($enlacesPie as $texto => $url): ?>
                <a href="<?php echo e($url); ?>"><?php echo enlacePendiente($url) ? cambiar($texto) : e($texto); ?></a>
                <?php endforeach; ?>
            </div>
            <p class="mb-1">&#9742; <?php echo cambiar($contacto["telefono"]); ?> · &#9993; <a href="mailto:<?php echo e($contacto["email"]); ?>"><?php echo cambiar($contacto["email"]); ?></a></p>
            <p class="mb-0 pie-copy">&copy; <?php echo date("Y"); ?> <?php echo e(FERIA_TITULO); ?></p>
        </div>
      </footer>

      <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
      <script>
        // La fecha de inicio viene de PHP (constante FERIA_INICIO), en milisegundos
        const inicioFeria = <?php echo $timestampInicio * 1000; ?>;

        function dosCifras(n) {
            return String(n).padStart(2, "0");
        }

        function actualizarContador() {
            const falta = Math.max(0, inicioFeria - Date.now());
            document.getElementById("dias").textContent = dosCifras(Math.floor(falta / 86400000));
            document.getElementById("horas").textContent = dosCifras(Math.floor(falta / 3600000) % 24);
            document.getElementById("minutos").textContent = dosCifras(Math.floor(falta / 60000) % 60);
            document.getElementById("segundos").textContent = dosCifras(Math.floor(falta / 1000) % 60);
        }

        actualizarContador();
        setInterval(actualizarContador, 1000);
      </script>
    </body>
</html>
