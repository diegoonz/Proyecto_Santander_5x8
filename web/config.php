<?php
/* =====================================================================
   CONFIG.PHP  ->  AQUÍ SE CAMBIA TODO EL CONTENIDO DE LA WEB
   ---------------------------------------------------------------------
   Este archivo solo tiene DATOS (constantes y arrays). No hay HTML.
   index.php lo carga con require y recorre estos arrays con foreach.

   >>> CÓMO ENCONTRAR LO QUE HAY QUE CAMBIAR <<<
   - Busca "CAMBIAR" (Ctrl+F) en este archivo y en centros.txt: son datos
     de ejemplo que hay que sustituir por los reales.
   - En la web, esos datos salen SUBRAYADOS EN AMARILLO mientras
     MOSTRAR_PENDIENTES esté en true.
   - Las imágenes cuyo nombre empieza por "ejemplo-" son ilustraciones
     provisionales: cámbialas por fotos reales.
   ===================================================================== */

date_default_timezone_set("Europe/Madrid");

/* ---------- 0. MODO BORRADOR ----------
   true  = se subrayan en la web los datos provisionales (para trabajar)
   false = se ven normales (ponlo en false cuando todo sea real, antes de entregar) */
define("MOSTRAR_PENDIENTES", true);

/* ---------- 1. CONSTANTES (define) ---------- */
define("FERIA_TITULO", "III FERIA VIRTUAL LA SALLE BUSINESS MATCH 2027");
define("FERIA_LEMA", "Conecta con los centros participantes, descubre sus proyectos y participa en los foros y encuentros de la feria.");
define("FERIA_INICIO", "2027-03-18 10:00:00");   // CAMBIAR: fecha y hora reales de inicio (año-mes-día hora:min:seg)
define("FICHERO_CENTROS", "centros.txt");
define("FICHERO_INSCRIPCIONES", "inscripciones.txt");
define("CARPETA_IMG", "img/");                   // aquí van las fotos de centros, foros y reels
define("CARPETA_VIDEOS", "videos/");             // aquí van los vídeos .mp4 de los reels

/* ---------- 2. MENÚ: id de la sección => texto del botón ---------- */
$menu = [
    "inicio"   => "Inicio",
    "centros"  => "Centros",
    "programa" => "Programa",
    "foros"    => "Foros",
    "reels"    => "Reels"
];

/* ---------- 3. PROGRAMA (se pinta en horizontal) ----------
   CAMBIAR: todo el programa es de ejemplo. Pon las horas y actividades reales.
   Para añadir una actividad, copia una línea y cambia la hora y el título. */
$programaDia = "Día 1. Todas las horas en horario peninsular.";
$programa = [
    ["hora" => "10:00", "titulo" => "Bienvenida y conferencia inaugural"],
    ["hora" => "11:15", "titulo" => "Mesa redonda: innovación"],
    ["hora" => "13:00", "titulo" => "Demostración de proyectos"],
    ["hora" => "14:30", "titulo" => "Sesión de networking"],
    ["hora" => "16:00", "titulo" => "Taller de habilidades"],
    ["hora" => "17:30", "titulo" => "Clausura y premios"]
];

/* ---------- 4. FOROS DE OTROS AÑOS ----------
   CAMBIAR: títulos, textos y fotos son de ejemplo. Poned los foros reales de la I y II Feria.
   "imagen": nombre de un archivo de img/. Si lo dejas en "", sale un bloque azul. */
$foros = [
    ["tipo" => "Conferencia",  "anio" => 2025, "titulo" => "Cómo captar al público",  "texto" => "Una charla para abrir la feria con empresas y centros.",          "imagen" => "ejemplo-foro-1.svg"],
    ["tipo" => "Mesa redonda", "anio" => 2025, "titulo" => "Hablan los expertos",     "texto" => "Profesionales del sector responden a las preguntas del alumnado.", "imagen" => "ejemplo-foro-2.svg"],
    ["tipo" => "Presentación", "anio" => 2026, "titulo" => "Proyectos innovadores",   "texto" => "Los centros muestran sus ideas más innovadoras.",                "imagen" => "ejemplo-foro-3.svg"],
    ["tipo" => "Networking",   "anio" => 2026, "titulo" => "Centros y empresas",      "texto" => "Encuentros en grupos pequeños para crear contactos.",            "imagen" => "ejemplo-foro-4.svg"],
    ["tipo" => "Taller",       "anio" => 2026, "titulo" => "Aprender haciendo",       "texto" => "Ejemplos reales de empresa para practicar.",                     "imagen" => "ejemplo-foro-5.svg"],
    ["tipo" => "Charla",       "anio" => 2026, "titulo" => "Charla distendida",       "texto" => "Una conversación relajada para cerrar la jornada.",              "imagen" => "ejemplo-foro-6.svg"]
];

/* ---------- 5. REELS (vídeos cortos) ----------
   CAMBIAR: frases y portadas de ejemplo; faltan los vídeos reales.
   "video": nombre del .mp4 dentro de videos/ (si está vacío, sale un reel de ejemplo).
   "imagen": portada dentro de img/.
   "centro": tiene que coincidir con un nombre de centros.txt. */
$reels = [
    ["titulo" => "Innovación",         "centro" => "CIFP Número Uno",           "frase" => "Diseñando el futuro con la tecnología.", "video" => "", "imagen" => "ejemplo-reel-1.svg"],
    ["titulo" => "Impacto global",     "centro" => "Colegio La Salle Santander", "frase" => "Proyectos que cruzan fronteras.",        "video" => "", "imagen" => "ejemplo-reel-2.svg"],
    ["titulo" => "Arte y creatividad", "centro" => "IES Santa Clara",            "frase" => "Ideas que se convierten en arte.",       "video" => "", "imagen" => "ejemplo-reel-3.svg"]
];

/* ---------- 6. PIE DE PÁGINA: texto del enlace => dirección ----------
   CAMBIAR: las direcciones están en "#" (no llevan a ningún sitio). */
$enlacesPie = [
    "Acerca de"            => "#",
    "Contacto"             => "#",
    "Preguntas frecuentes" => "#",
    "Términos"             => "#"
];
// CAMBIAR: teléfono y correo inventados. Poned los de la organización de la feria.
$contacto = [
    "telefono" => "942 000 000",
    "email"    => "feria@ejemplo.es"
];
