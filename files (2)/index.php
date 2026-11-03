<?php
/* =====================================================
   CONFIGURACIÓN: constantes (define)
   ===================================================== */
date_default_timezone_set("Europe/Madrid");
define("FERIA_TITULO", "III FERIA VIRTUAL LA SALLE BUSINESS MATCH 2027");
define("FERIA_INICIO", "2027-03-18 10:00:00"); # CÁMBIALA por la fecha real (año-mes-día hora)
define("FICHERO_CENTROS", "centros.txt");
define("FICHERO_INSCRIPCIONES", "inscripciones.txt");

// Función para escribir texto de forma segura en el HTML
function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, "UTF-8");
}

/* =====================================================
   ARRAYS con los datos de la web
   ===================================================== */
// Array asociativo: clave (id de la sección) => texto del menú
$menu = [
    "inicio"   => "Inicio",
    "centros"  => "Centros",
    "programa" => "Programa",
    "foros"    => "Foros",
    "reels"    => "Reels"
];

// Array de arrays: programa del día 1
$programa = [
    ["hora" => "10:00", "titulo" => "Bienvenida y conferencia inaugural"],
    ["hora" => "11:15", "titulo" => "Mesa redonda: innovación"],
    ["hora" => "13:00", "titulo" => "Demostración de proyectos"],
    ["hora" => "14:30", "titulo" => "Sesión de networking"],
    ["hora" => "16:00", "titulo" => "Taller de habilidades"],
    ["hora" => "17:30", "titulo" => "Clausura y premios"]
];

// Array de arrays: foros de otros años
$foros = [
    ["edicion" => "I Feria",  "anio" => 2025, "titulo" => "Conferencia: captar al público", "texto" => "Una charla para abrir la feria con empresas y centros."],
    ["edicion" => "I Feria",  "anio" => 2025, "titulo" => "Mesa redonda: hablan los expertos", "texto" => "Profesionales del sector responden a las preguntas del alumnado."],
    ["edicion" => "II Feria", "anio" => 2026, "titulo" => "Presentación de proyectos", "texto" => "Los centros muestran sus ideas más innovadoras."],
    ["edicion" => "II Feria", "anio" => 2026, "titulo" => "Networking entre centros y empresas", "texto" => "Encuentros en grupos pequeños para crear contactos."],
    ["edicion" => "II Feria", "anio" => 2026, "titulo" => "Taller práctico", "texto" => "Aprender haciendo, con ejemplos reales de empresa."],
    ["edicion" => "II Feria", "anio" => 2026, "titulo" => "Charla distendida", "texto" => "Una conversación relajada para cerrar la jornada."]
];

// Array de arrays: reels (vídeos cortos)
$reels = [
    ["titulo" => "Innovación",         "centro" => "IES Labordeta"],
    ["titulo" => "Impacto global",     "centro" => "Col·legi Montserrat"],
    ["titulo" => "Arte y creatividad", "centro" => "IES Miguel de Cervantes"]
];

/* =====================================================
   FICHEROS (1): leer los centros desde centros.txt
   Cada línea:  nombre|ciudad|info|siglas|enlace
   ===================================================== */
$centros = [];
if (file_exists(FICHERO_CENTROS) && is_readable(FICHERO_CENTROS)) {
    $lineas = file(FICHERO_CENTROS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    sort($lineas); // orden alfabético
    foreach ($lineas as $linea) {
        $campos = explode("|", $linea);
        if (count($campos) >= 5) {
            $centros[] = [
                "nombre" => $campos[0],
                "ciudad" => $campos[1],
                "info"   => $campos[2],
                "siglas" => $campos[3],
                "url"    => $campos[4]
            ];
        }
    }
}
$nombresCentros = array_column($centros, "nombre");
$ciudades = array_unique(array_column($centros, "ciudad"));

/* =====================================================
   FICHEROS (2): guardar las inscripciones en inscripciones.txt
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
   Estado de la feria (match)
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
<!--Esta marca indica que es una página de HTML5-->
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
        <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="hola.css" rel="stylesheet" type="text/css">
    </head>
    <body>

      <!-- ================= MENÚ (generado con el array $menu) ================= -->
      <nav class="navbar navbar-expand-lg navbar-feria sticky-top">
        <div class="container-fluid px-lg-5">
            <a class="navbar-brand" href="#inicio">La Salle Business Match</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#MenuNav" aria-controls="MenuNav" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="MenuNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <?php foreach ($menu as $id => $texto): ?>
                    <li class="nav-item"><a class="nav-link" href="#<?php echo e($id); ?>"><?php echo e($texto); ?></a></li>
                    <?php endforeach; ?>
                    <li class="nav-item ms-lg-2"><a class="btn btn-feria btn-feria-amarillo" href="#inscripcion">Inscríbete</a></li>
                </ul>
            </div>
        </div>
      </nav>

      <!-- ================= TÍTULO ================= -->
      <header class="cabecera" id="inicio">
        <div class="container">
            <h1>III FERIA VIRTUAL LA SALLE<br>BUSINESS MATCH 2027</h1>
            <p>Conecta con los centros participantes, descubre sus proyectos y participa en los foros y encuentros de la feria.</p>
            <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="#centros" class="btn btn-feria btn-feria-amarillo">Ver centros</a>
                <a href="#programa" class="btn btn-feria">Ver programa</a>
            </div>
        </div>
      </header>

      <!-- ================= CONTADOR ================= -->
      <section class="contador-zona" id="contador">
        <div class="container">
            <h2><?php echo e($textoContador); ?></h2>
            <div class="contador">
                <div class="contador-caja"><span class="contador-numero" id="dias">00</span><span class="contador-texto">Días</span></div>
                <div class="contador-caja"><span class="contador-numero" id="horas">00</span><span class="contador-texto">Horas</span></div>
                <div class="contador-caja"><span class="contador-numero" id="minutos">00</span><span class="contador-texto">Minutos</span></div>
                <div class="contador-caja"><span class="contador-numero" id="segundos">00</span><span class="contador-texto">Segundos</span></div>
            </div>
            <a href="#inscripcion" class="btn btn-feria btn-feria-amarillo">Inscríbete</a>
        </div>
      </section>

      <!-- ================= CENTROS PARTICIPANTES (leídos de centros.txt) ================= -->
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
                        <div class="centro-imagen"><?php echo e($centro["siglas"]); ?></div>
                        <div class="card-body">
                            <h3><?php echo e($centro["nombre"]); ?></h3>
                            <p class="centro-ciudad"><?php echo e($centro["ciudad"]); ?></p>
                            <p class="centro-info"><?php echo e($centro["info"]); ?></p>
                            <a href="<?php echo e($centro["url"]); ?>" class="btn btn-feria btn-sm">Ver perfil</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
      </section>

      <!-- ================= PROGRAMA HORIZONTAL (array $programa) ================= -->
      <section class="seccion seccion-gris" id="programa">
        <div class="container">
            <h2 class="titulo-seccion">Programa de la feria</h2>
            <p class="subtitulo-seccion">Día 1. Todas las horas en horario peninsular.</p>
            <div class="programa-scroll">
                <div class="programa">
                    <?php foreach ($programa as $posicion => $paso): ?>
                    <div class="programa-paso">
                        <div class="programa-punto"><?php echo $posicion + 1; ?></div>
                        <span class="programa-hora"><?php echo e($paso["hora"]); ?></span>
                        <span class="programa-titulo"><?php echo e($paso["titulo"]); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
      </section>

      <!-- ================= FOROS DE OTROS AÑOS (array $foros) ================= -->
      <section class="seccion" id="foros">
        <div class="container">
            <h2 class="titulo-seccion">Foros de otros años</h2>
            <p class="subtitulo-seccion">Un repaso a los mejores momentos de las ediciones anteriores.</p>
            <div class="row g-4">
                <?php foreach ($foros as $foro): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="foro-tarjeta">
                        <div class="foro-imagen"><?php echo e($foro["edicion"]); ?></div>
                        <div class="card-body">
                            <span class="foro-edicion"><?php echo (int)$foro["anio"]; ?></span>
                            <h3><?php echo e($foro["titulo"]); ?></h3>
                            <p><?php echo e($foro["texto"]); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
      </section>

      <!-- ================= REELS Y ENLACES A LOS CENTROS ================= -->
      <section class="seccion seccion-gris" id="reels">
        <div class="container">
            <h2 class="titulo-seccion">Reels de los centros</h2>
            <p class="subtitulo-seccion">Vídeos cortos para conocer a los centros en menos de un minuto.</p>
            <div class="row g-4 justify-content-center">
                <?php foreach ($reels as $reel): ?>
                <div class="col-10 col-sm-6 col-lg-3">
                    <div class="reel">
                        <button type="button" class="reel-play" aria-label="Reproducir reel: <?php echo e($reel["titulo"]); ?>">&#9654;</button>
                        <h3><?php echo e($reel["titulo"]); ?></h3>
                        <p><?php echo e($reel["centro"]); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <h3 class="text-center mt-5 mb-4 fs-5">Accede directamente a cada centro</h3>
            <div class="row g-3">
                <?php foreach ($centros as $centro): ?>
                <div class="col-6 col-lg-3"><a class="enlace-centro" href="<?php echo e($centro["url"]); ?>"><?php echo e($centro["nombre"]); ?></a></div>
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
                        <button type="submit" class="btn btn-feria">Enviar inscripción</button>
                    </form>
                </div>
            </div>
        </div>
      </section>

      <!-- ================= PIE ================= -->
      <footer class="pie">
        <div class="container">
            <p class="mb-2">&copy; <?php echo date("Y"); ?> <?php echo e(FERIA_TITULO); ?></p>
            <a href="#">Acerca de</a><a href="#">Contacto</a><a href="#">Preguntas frecuentes</a><a href="#">Términos</a>
        </div>
      </footer>

      <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
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
