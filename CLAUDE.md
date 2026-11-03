# CLAUDE.md

## Proyecto
Web de la **III Feria Virtual La Salle Business Match 2027**. Es un rediseño de la web del año anterior, según el PDF `SOLICITUD_DE_REDISEÑO_WEB_.pdf`. Es un trabajo de clase (1º DAM), así que el código debe ser claro y seguir lo visto en clase: Bootstrap, PHP, arrays y ficheros.

## Idioma y estilo
- Responder en español, de forma casual y concisa.
- Comentarios del código en español.
- Explicar los cambios de forma sencilla, como para alguien que está aprendiendo.

## Estructura de archivos
```
carpeta-del-proyecto/
├── bootstrap-5.3.8-dist/   (Bootstrap en local, una carpeta por encima de la web)
└── web/
    ├── index.php           (página principal: lógica PHP arriba + HTML abajo)
    ├── hola.css            (estilos propios)
    ├── centros.txt         (datos de los centros, uno por línea)
    └── inscripciones.txt   (se crea solo al enviar el formulario)
```
Las rutas a Bootstrap son relativas: `../bootstrap-5.3.8-dist/css/bootstrap.min.css` y `../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js`.

## Cómo ejecutarlo
- Hace falta un servidor con PHP 8 o superior (XAMPP, por ejemplo) y abrir la página por `http://localhost/...`. Con doble clic el PHP no se ejecuta.
- `PHP 8` es necesario por el uso de `match`.
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
nombre|ciudad|info|siglas|enlace
```
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
- Fecha real de inicio de la feria en `FERIA_INICIO` de `index.php`.
- Imágenes y vídeos reales: ahora las tarjetas usan bloques azules con siglas y los reels son placeholders.
- Datos reales de centros (`centros.txt`), programa, foros y reels.
- Enlaces reales de cada centro (el último campo de `centros.txt` está en `#`).
- Los enlaces del pie de página (`Acerca de`, `Contacto`, `Preguntas frecuentes` y `Términos`) están en `#`.
