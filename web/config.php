<?php
// Datos de la feria
date_default_timezone_set("Europe/Madrid");

define("TITULO", "III FERIA VIRTUAL LA SALLE BUSINESS MATCH 2027");
define("LEMA", "Conecta con los centros participantes, descubre sus proyectos y participa en los foros y encuentros de la feria.");
define("FECHA_INICIO", "2027-03-18 10:00:00");
define("FICHERO_CENTROS", "centros.txt");
define("FICHERO_INSCRIPCIONES", "inscripciones.txt");
define("CARPETA_IMG", "img/");
define("CARPETA_VIDEOS", "videos/");

// Menu
$menu = array(
    "inicio" => "Inicio",
    "centros" => "Centros",
    "programa" => "Programa",
    "foros" => "Foros",
    "reels" => "Reels"
);

// Programa del dia 1
$programaDia = "Día 1. Todas las horas en horario peninsular.";
$programa = array(
    array("hora" => "10:00", "titulo" => "Bienvenida y conferencia inaugural"),
    array("hora" => "11:15", "titulo" => "Mesa redonda: innovación"),
    array("hora" => "13:00", "titulo" => "Demostración de proyectos"),
    array("hora" => "14:30", "titulo" => "Sesión de networking"),
    array("hora" => "16:00", "titulo" => "Taller de habilidades"),
    array("hora" => "17:30", "titulo" => "Clausura y premios")
);

// Foros de otros años
$foros = array(
    array("tipo" => "Conferencia", "anio" => 2025, "titulo" => "Cómo captar al público", "texto" => "Una charla para abrir la feria con empresas y centros.", "imagen" => "ejemplo-foro-1.svg"),
    array("tipo" => "Mesa redonda", "anio" => 2025, "titulo" => "Hablan los expertos", "texto" => "Profesionales del sector responden a las preguntas del alumnado.", "imagen" => "ejemplo-foro-2.svg"),
    array("tipo" => "Presentación", "anio" => 2026, "titulo" => "Proyectos innovadores", "texto" => "Los centros muestran sus ideas más innovadoras.", "imagen" => "ejemplo-foro-3.svg"),
    array("tipo" => "Networking", "anio" => 2026, "titulo" => "Centros y empresas", "texto" => "Encuentros en grupos pequeños para crear contactos.", "imagen" => "ejemplo-foro-4.svg"),
    array("tipo" => "Taller", "anio" => 2026, "titulo" => "Aprender haciendo", "texto" => "Ejemplos reales de empresa para practicar.", "imagen" => "ejemplo-foro-5.svg"),
    array("tipo" => "Charla", "anio" => 2026, "titulo" => "Charla distendida", "texto" => "Una conversación relajada para cerrar la jornada.", "imagen" => "ejemplo-foro-6.svg")
);

// Reels (si "video" esta vacio sale la imagen)
$reels = array(
    array("titulo" => "Innovación", "centro" => "CIFP Número Uno", "frase" => "Diseñando el futuro con la tecnología.", "video" => "", "imagen" => "ejemplo-reel-1.svg"),
    array("titulo" => "Impacto global", "centro" => "Colegio La Salle Santander", "frase" => "Proyectos que cruzan fronteras.", "video" => "", "imagen" => "ejemplo-reel-2.svg"),
    array("titulo" => "Arte y creatividad", "centro" => "IES Santa Clara", "frase" => "Ideas que se convierten en arte.", "video" => "", "imagen" => "ejemplo-reel-3.svg")
);

// Enlaces del pie
$enlacesPie = array(
    "Acerca de" => "#",
    "Contacto" => "#",
    "Preguntas frecuentes" => "#",
    "Términos" => "#"
);

$telefono = "942 000 000";
$correo = "feria@ejemplo.es";