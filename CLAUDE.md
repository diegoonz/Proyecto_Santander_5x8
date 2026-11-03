# CLAUDE.md

## Proyecto
Web de la **III Feria Virtual La Salle Business Match 2027**. Es un rediseño de la web del año anterior, según el PDF `docs/solicitud-rediseno-web.pdf`, para alumnos de Marketing de Santander (grupo 8). Es un trabajo de clase (DAM), así que el código debe ser claro y seguir lo visto en clase: Bootstrap, PHP, arrays y ficheros.

## Idioma y estilo
- Responder en español, de forma casual y concisa.
- Comentarios del código en español.
- Explicar los cambios de forma sencilla, como para alguien que está aprendiendo.

## Estructura de archivos
```
Proyecto_GitHub/            (raíz del repo diegoonz/Proyecto_Santander_5x8)
├── abrir-en-local.bat      (abre la web en el PC con doble clic)
├── base_de_datos/          (script SQL; la web todavía no lo usa)
├── docs/                   (PDF del cliente y guía del proyecto)
└── web/                    (lo único que se sube al servidor)
    ├── bootstrap-5.3.8-dist/
    ├── index.php           (página principal: lógica PHP arriba + HTML abajo)
    ├── config.php          (textos, fecha, programa, foros, reels y pie)
    ├── hola.css            (estilos propios)
    ├── centros.txt         (datos de los centros, uno por línea)
    └── inscripciones.txt   (se crea solo al enviar el formulario; no va a git)
```
Las rutas a Bootstrap son relativas y sin `../`: `bootstrap-5.3.8-dist/css/bootstrap.min.css` y `bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js`. Nada de `web/` puede apuntar fuera de esa carpeta, porque en el servidor no existe nada por encima.

## Cómo ejecutarlo
- En el PC: doble clic en `abrir-en-local.bat` (`http://localhost:8080`). Hace falta XAMPP en `C:\xampp`.
- En el servidor: subir con FileZilla lo de dentro de `web/` a la carpeta del grupo 8 (FTP `172.26.10.2`, solo desde la red del centro). Pasos en `README.md`.
- `PHP 8` es necesario por el uso de `match` y `str_starts_with`.
- La carpeta debe tener permisos de escritura para que se pueda crear `inscripciones.txt`.

## Requisitos del PDF (todos hechos)
1. Colores institucionales: azul, amarillo, gris y blanco como principal. Variables CSS en `:root` de `hola.css`.
2. Título "III FERIA VIRTUAL LA SALLE BUSINESS MATCH 2027" con una tipografía moderna (Montserrat).
3. Botones con esquinas redondeadas, azul con texto amarillo o blanco, y efecto hover (clases `btn-feria`).
4. Centros participantes con más espacio entre secciones (clase `.seccion`, `padding: 6rem 0`).
5. Programa en horizontal (`.programa`) y sección de foros de otros años.
6. Contador de cuenta atrás con días, horas, minutos y segundos.
7. Reels (vídeos cortos) y enlaces directos a cada centro.

Además hay una sección "Inscríbete" con un formulario que guarda en `inscripciones.txt`.

## Cómo está hecho el PHP
- **Arriba del archivo**, antes del HTML, se hace toda la lógica: constantes con `define`, arrays de datos, lectura de `centros.txt` y procesado del formulario. El HTML de abajo solo recorre variables con `foreach`.
- Constantes: `FERIA_TITULO`, `FERIA_INICIO`, `FICHERO_CENTROS`, `FICHERO_INSCRIPCIONES`.
- Arrays: `$menu`, `$programa`, `$foros`, `$reels` y `$centros`.
- Todo lo que se imprime pasa por la función `e()` (`htmlspecialchars`).
- Siempre usar `<?php`, nunca la etiqueta corta `<?`.
- Para ver un array usar `print_r`, no `print`.

### Formato de `centros.txt`
Una línea por centro, con campos separados por `|`:
```
nombre|ciudad|info|siglas|enlace|imagen
```
La imagen es opcional y las líneas que empiezan por `#` son comentarios.
Para añadir un centro, basta con añadir una línea. Se actualizan solas las tarjetas, los enlaces y el desplegable del formulario.

### Formato de `inscripciones.txt`
```
fecha hora|nombre|correo|centro
```

## Errores típicos ya corregidos (no repetirlos)
- `bootstrap-grid.min.css` solo trae la rejilla. Hay que usar `bootstrap.min.css` para navbar, botones y tarjetas.
- El botón del menú usa `data-bs-target="#MenuNav"` (no `tarjet`) y el `id` es `MenuNav` (sin `#`).
- El `div collapse` va **fuera** del `button` del navbar.
- En HTML5 no se cierran etiquetas como `<meta>`, `<input>` o `<link>`.
- En los bucles, la variable que se incrementa debe ser la que se comprueba en la condición.
- Las rutas de archivos van con punto: `archivo2.txt`, no `archivo2,txt`.

## Pendiente de cambiar
- Dirección final de la web y datos FTP del grupo 8 (los da el profesor).
- Fecha real de inicio de la feria en `FERIA_INICIO` de `config.php`.
- Imágenes y vídeos reales: ahora hay ilustraciones `ejemplo-*.svg` y faltan los vídeos de los reels.
- Datos reales de centros (`centros.txt`), programa, foros y reels.
- Enlaces reales de cada centro (el último campo de `centros.txt` está en `#`).
- Los enlaces del pie de página (`Acerca de`, `Contacto`, `Preguntas frecuentes` y `Términos`) están en `#`.
