# III Feria Virtual La Salle Business Match 2027

Web de la feria para los alumnos de Marketing de Santander. Trabajo del grupo 8.

Hecha con PHP, Bootstrap y ficheros de texto.

## Cómo está organizado

```
Proyecto_GitHub/
├── web/                  LO ÚNICO QUE SE SUBE AL SERVIDOR
│   ├── index.php         lógica arriba + HTML abajo
│   ├── config.php        textos, fecha, programa, foros, reels y pie
│   ├── hola.css          estilos (colores en :root)
│   ├── centros.txt       un centro por línea
│   ├── img/  videos/     fotos y vídeos
│   └── bootstrap-5.3.8-dist/
├── base_de_datos/        script SQL (la web todavía no lo usa)
├── docs/                 PDF del cliente y guía del proyecto
└── abrir-en-local.bat    abre la web en tu PC con doble clic
```

## Probarla en tu PC

Doble clic en `abrir-en-local.bat`. Se abre en `http://localhost:8080`. Hace falta XAMPP en `C:\xampp`.

## Publicarla con FileZilla

Esto solo funciona desde la red del centro.

1. En FileZilla: **Servidor** `172.26.10.2`, **Puerto** `21`, y el usuario y la contraseña del grupo 8 que da el profesor.
2. A la derecha (servidor), entra en la carpeta del grupo 8.
3. A la izquierda (tu PC), entra en `Proyecto_GitHub\web`.
4. Selecciona **lo de dentro** de `web` y arrástralo a la derecha. La carpeta `web` no se sube, para que la dirección quede corta.
5. Clic derecho en la carpeta del grupo 8 > **Permisos de archivo**. Tiene que tener permiso de escritura o el formulario no podrá crear `inscripciones.txt`.
6. Abre la dirección del grupo. **Pendiente de confirmar con el profesor**; por cómo es la suya (`/Profe`), debería ser algo parecido a `http://172.26.10.1:8088/Grupo8`.

Cuando cambiéis algo, se vuelve a subir solo el archivo cambiado. Ojo: si has probado el formulario en tu PC, tendrás un `inscripciones.txt` en `web`. Ese no lo subas, porque machacaría el del servidor.

## Antes de la entrega

- Cambiar los datos de ejemplo: buscar `CAMBIAR` en `config.php` y `centros.txt`.
- Poner `MOSTRAR_PENDIENTES` en `false` en `config.php` para quitar los subrayados.
