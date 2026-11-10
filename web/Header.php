<?php
// parte de arriba que se repite en todas las paginas
require_once "config.php";
require_once "funciones.php";

// nombre de la pagina en la que estamos (para marcar el menu)
$paginaActual = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo $tituloPagina; ?> - <?php echo TITULO; ?></title>
        <meta charset="UTF-8">
        <meta name="keywords" content="feria virtual, La Salle, Business Match, centros, empresas">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="hola.css" rel="stylesheet" type="text/css">
    </head>
    <body>

      <!-- menu -->
      <nav class="navbar navbar-expand-lg navbar-feria sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><span class="marca-logo">LS</span> Business Match</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#MenuNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="MenuNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <?php foreach ($menu as $archivo => $texto) { ?>
                    <?php
                        $clase = "nav-link";
                        if ($archivo == $paginaActual) {
                            $clase = "nav-link active";
                        }
                    ?>
                    <li class="nav-item"><a class="<?php echo $clase; ?>" href="<?php echo $archivo; ?>"><?php echo $texto; ?></a></li>
                    <?php } ?>
                    <li class="nav-item ms-lg-2"><a class="btn btn-feria btn-feria-amarillo" href="inscripcion.php">Inscríbete</a></li>
                </ul>
            </div>
        </div>
      </nav>