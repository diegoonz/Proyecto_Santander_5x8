<?php
// funciones que usan varias paginas

// lee centros.txt: una linea por centro
// nombre|ciudad|descripcion|siglas|URL|imagen (las lineas con # son comentarios)
function leerCentros() {
    $centros = array();
    if (!is_readable(FICHERO_CENTROS)) {
        return $centros;
    }
    foreach (file(FICHERO_CENTROS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        $linea = trim($linea);
        if ($linea == "" || $linea[0] == "#") {
            continue;
        }
        $campos = array_map("trim", explode("|", $linea));
        if (count($campos) != 6 || $campos[0] == "") {
            continue;
        }
        // solo enlaces http o https; si no, se deja como "#"
        $url = $campos[4];
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match("#^https?://#i", $url)) {
            $url = "#";
        }
        $centros[] = array(
            "nombre" => htmlspecialchars($campos[0]),
            "ciudad" => htmlspecialchars($campos[1]),
            "info" => htmlspecialchars($campos[2]),
            "siglas" => htmlspecialchars($campos[3]),
            "url" => htmlspecialchars($url),
            "imagen" => basename($campos[5])
        );
    }
    return $centros;
}

// ciudades sin repetir, en orden alfabetico
function leerCiudades($centros) {
    $ciudades = array();
    foreach ($centros as $centro) {
        if ($centro["ciudad"] != "" && !in_array($centro["ciudad"], $ciudades)) {
            $ciudades[] = $centro["ciudad"];
        }
    }
    sort($ciudades);
    return $ciudades;
}
