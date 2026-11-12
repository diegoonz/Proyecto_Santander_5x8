<?php
require __DIR__ . '/config.php';

function e($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function archivo($carpeta, $nombre) {
    if ($nombre === '' || basename($nombre) !== $nombre || !is_file(__DIR__ . "/$carpeta/$nombre")) {
        return '';
    }
    return $carpeta . '/' . rawurlencode($nombre);
}

$centros = [];
foreach ((is_readable(__DIR__ . '/centros.txt') ? file(__DIR__ . '/centros.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : []) ?: [] as $linea) {
    $linea = trim($linea);
    if ($linea === '' || $linea[0] === '#') continue;
    $campos = array_map('trim', explode('|', $linea));
    if (count($campos) !== 6 || $campos[0] === '') continue;
    [$nombre, $ciudad, $descripcion, $siglas, $url, $imagen] = $campos;
    if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
        $url = '';
    }
    $centros[] = compact('nombre', 'ciudad', 'descripcion', 'siglas', 'url', 'imagen');
}

$fecha = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $inicio, new DateTimeZone('Europe/Madrid'));
if ($fecha && $fecha->format('Y-m-d H:i') !== $inicio) $fecha = false;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?></title>
    <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="hola.css">
    <script src="feria.js" defer></script>
</head>
<body>
<a class="visually-hidden-focusable salto" href="#contenido">Saltar al contenido</a>
<header class="barra">
    <div class="container barra-interior">
        <a class="marca" href="#inicio">Business Match</a>
        <nav aria-label="Navegación principal">
            <a href="#centros">Centros</a>
            <a href="#programa">Programa</a>
            <a href="#foros">Foros</a>
            <a href="#reels">Reels</a>
        </nav>
    </div>
</header>
<main id="contenido">
    <section class="portada" id="inicio">
        <div class="container">
            <div class="cartel">
                <div>
                    <h1><?= e($titulo) ?></h1>
                    <a class="boton" href="#programa">Ver programa</a>
                </div>
                <span class="cartel-edicion" aria-hidden="true">III</span>
            </div>
            <aside class="cuenta" aria-labelledby="titulo-cuenta">
                <div>
                    <h2 id="titulo-cuenta">Cuenta atrás</h2>
                    <?php if ($fecha): ?>
                    <p><time datetime="<?= e($fecha->format(DATE_ATOM)) ?>"><?= e($fecha->format('d/m/Y · H:i')) ?></time> · hora peninsular</p>
                    <?php else: ?>
                    <p>Fecha y hora pendientes de confirmar.</p>
                    <?php endif; ?>
                </div>
                <dl class="reloj" <?php if ($fecha): ?>data-inicio="<?= $fecha->getTimestamp() ?>"<?php endif; ?>>
                    <?php foreach (['Días', 'Horas', 'Minutos', 'Segundos'] as $unidad): ?>
                    <div><dt><?= e($unidad) ?></dt><dd>—</dd></div>
                    <?php endforeach; ?>
                </dl>
                <p id="estado-cuenta" hidden>La fecha de inicio ya ha llegado.</p>
            </aside>
        </div>
    </section>
    <section class="seccion" id="centros">
        <div class="container">
            <h2>Centros participantes</h2>
            <?php if (!$centros): ?><p class="vacio">Listado de centros y enlaces pendiente de confirmación.</p><?php endif; ?>
            <div class="rejilla">
                <?php foreach ($centros as $centro): ?>
                <article class="ficha">
                    <?php if ($foto = archivo('img', $centro['imagen'])): ?>
                    <img src="<?= e($foto) ?>" alt="" loading="lazy">
                    <?php elseif ($centro['siglas']): ?>
                    <p class="siglas"><?= e($centro['siglas']) ?></p>
                    <?php endif; ?>
                    <h3><?= e($centro['nombre']) ?></h3>
                    <p class="rotulo"><?= e($centro['ciudad']) ?></p>
                    <p><?= e($centro['descripcion']) ?></p>
                    <?php if ($centro['url']): ?>
                    <a class="boton" href="<?= e($centro['url']) ?>">Visitar <?= e($centro['nombre']) ?></a>
                    <?php else: ?><p class="nota">Enlace pendiente.</p><?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section class="seccion gris" id="programa">
        <div class="container">
            <h2>Programa de la feria</h2>
            <?php if (!$programa): ?>
            <p class="vacio">Horarios y actividades pendientes de confirmación.</p>
            <?php else: ?>
            <div class="desplazable" tabindex="0" role="region" aria-label="Programa horizontal">
                <ol class="programa">
                    <?php foreach ($programa as $actividad): ?>
                    <li class="ficha"><strong><?= e($actividad['hora']) ?></strong><h3><?= e($actividad['titulo']) ?></h3></li>
                    <?php endforeach; ?>
                </ol>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="seccion" id="foros">
        <div class="container">
            <h2>Foros de otros años</h2>
            <?php if (!$foros): ?><p class="vacio">Contenido de ediciones anteriores pendiente de incorporar.</p><?php endif; ?>
            <div class="rejilla">
                <?php foreach ($foros as $foro): ?>
                <article class="ficha">
                    <?php if ($foto = archivo('img', $foro['imagen'] ?? '')): ?><img src="<?= e($foto) ?>" alt="" loading="lazy"><?php endif; ?>
                    <p class="rotulo"><?= e($foro['edicion']) ?></p>
                    <h3><?= e($foro['titulo']) ?></h3>
                    <p><?= e($foro['texto']) ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section class="seccion gris" id="reels">
        <div class="container">
            <h2>Reels de los centros</h2>
            <?php if (!$reels): ?><p class="vacio">Vídeos pendientes de incorporar.</p><?php endif; ?>
            <div class="rejilla">
                <?php foreach ($reels as $reel): ?>
                <article class="ficha">
                    <p class="rotulo"><?= e($reel['centro']) ?></p>
                    <h3><?= e($reel['titulo']) ?></h3>
                    <?php if ($video = archivo('videos', $reel['video'])): ?>
                    <video controls playsinline preload="metadata" aria-label="<?= e($reel['titulo']) ?>"
                        <?php if ($portada = archivo('img', $reel['portada'] ?? '')): ?>poster="<?= e($portada) ?>"<?php endif; ?>>
                        <source src="<?= e($video) ?>" type="video/mp4">
                        <?php if ($subtitulos = archivo('videos', $reel['subtitulos'] ?? '')): ?>
                        <track src="<?= e($subtitulos) ?>" kind="captions" srclang="es" label="Español">
                        <?php endif; ?>
                        <a href="<?= e($video) ?>">Abrir vídeo</a>
                    </video>
                    <?php else: ?><p class="nota">Vídeo pendiente.</p><?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
            <a class="boton" href="#centros">Ver enlaces a los centros</a>
        </div>
    </section>
</main>
<footer class="pie"><div class="container">La Salle Business Match · 2027 <a href="#inicio">Volver al inicio ↑</a></div></footer>
</body>
</html>
