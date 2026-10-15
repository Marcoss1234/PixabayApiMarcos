# Pixabay API

Galería en PHP que consulta 20 fotografías de naturaleza en Pixabay mediante Guzzle y muestra el autor y los «me gusta» en un grid responsive de Tailwind CSS.

## Requisitos

- PHP 8 o posterior
- Composer
- Una API key de Pixabay

## Instalación y ejecución con Docker Desktop

1. En la carpeta `C:\Users\usuario\Downloads\Web\Web`, crea un archivo llamado `.env` (sin extensión) junto a `docker-compose.yml`.

2. Dentro de `.env`, pega tu clave:

   ```dotenv
   PIXABAY_API_KEY=tu_api_key
   ```

   Puedes copiar `.env.example` y sustituir el valor de ejemplo. No compartas ni incluyas `.env` en el ZIP.

3. En Docker Desktop, reinicia el proyecto `web` (Stop y después Start) para que el contenedor reciba la variable.

4. Abre `http://localhost/apipelis/`.

También puedes abrir `http://localhost/apipelis/public/uno.php`; esa ruta antigua carga la misma página principal.

La hoja de estilos de Tailwind se carga desde su CDN, por lo que la página necesita conexión a Internet para aplicar el diseño.

Si necesitas instalar o regenerar las dependencias de Composer, hazlo desde la carpeta del proyecto:

```powershell
composer install
```

## Entrega

Incluye `index.php`, `src/`, `composer.json`, `composer.lock` y este README en el archivo comprimido. Excluye la carpeta `vendor/`; quien reciba el proyecto puede reconstruirla con `composer install`.
