<?php
declare(strict_types=1);

use App\ApiUtils;
use GuzzleHttp\Exception\GuzzleException;

require __DIR__ . '/vendor/autoload.php';

$imagenes = [];
$error = null;
$apiKey = getenv('PIXABAY_API_KEY');

if ($apiKey === false || trim($apiKey) === '') {
    $error = 'Configura PIXABAY_API_KEY en Apache para cargar las imágenes.';
} else {
    try {
        $imagenes = ApiUtils::getImagenes();
    } catch (GuzzleException | JsonException | RuntimeException $exception) {
        $error = 'No se pudieron cargar las imágenes. Comprueba tu conexión y que la API key de Pixabay sea válida.';
    }
}

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f4f0e6">
    <title>Atlas — imágenes de Pixabay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#f4f0e6',
                        ink: '#182521',
                        moss: '#536d4e',
                        citron: '#d5f36a',
                        clay: '#c87654'
                    },
                    fontFamily: {
                        display: ['Georgia', 'serif'],
                        sans: ['Segoe UI', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <style>
        body {
            background-image: radial-gradient(#18252112 0.7px, transparent 0.7px);
            background-size: 14px 14px;
        }
        .image-card:nth-child(5n + 2) .photo { aspect-ratio: 4 / 5; }
        .image-card:nth-child(5n + 4) .photo { aspect-ratio: 5 / 6; }
        .photo img { transition: transform 500ms cubic-bezier(.2,.7,.2,1); }
        .image-card:hover .photo img { transform: scale(1.045); }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-paper text-ink antialiased">
    <div class="mx-auto max-w-[1500px] px-5 py-6 sm:px-8 lg:px-12">
        <header class="flex items-center justify-between border-b border-ink/20 pb-5">
            <a href="index.php" class="flex items-center gap-3" aria-label="Atlas, inicio">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-ink text-citron font-display text-xl">A</span>
                <span class="text-sm font-bold uppercase tracking-[.22em]">Atlas <span class="font-normal text-ink/50">/ archivo visual</span></span>
            </a>
            <span class="hidden text-xs uppercase tracking-[.2em] text-ink/55 sm:block">Proyecto de consumo de API · Pixabay</span>
        </header>

        <main>
            <section class="grid gap-8 py-12 sm:py-16 lg:grid-cols-[1fr_auto] lg:items-end">
                <div>
                    <p class="mb-5 flex items-center gap-3 text-xs font-semibold uppercase tracking-[.2em] text-moss">
                        <span class="h-px w-8 bg-moss"></span> Colección 001 · naturaleza
                    </p>
                    <h1 class="max-w-4xl font-display text-5xl leading-[.98] tracking-[-.045em] sm:text-7xl lg:text-8xl">
                        Un paseo por<br><span class="italic text-moss">lo extraordinario.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-7 text-ink/65 sm:text-lg">
                        Una selección de fotografías de la comunidad Pixabay. Cada imagen guarda el nombre de su autor y el cariño que ha recibido.
                    </p>
                </div>
                <div class="flex items-end gap-3 lg:pb-2">
                    <span class="font-display text-6xl leading-none"><?= count($imagenes); ?></span>
                    <span class="pb-1 text-xs uppercase leading-5 tracking-[.16em] text-ink/55">fotografías<br>en esta página</span>
                </div>
            </section>

            <div class="mb-5 flex items-center justify-between border-y border-ink/20 py-4">
                <h2 class="font-display text-2xl italic">Miradas de la naturaleza</h2>
                <span class="rounded-full bg-citron px-3 py-1 text-[11px] font-bold uppercase tracking-[.14em]">Pixabay · 20 resultados</span>
            </div>

            <?php if ($error !== null): ?>
                <section role="alert" class="my-8 border-l-4 border-clay bg-white/70 px-5 py-4">
                    <h2 class="font-semibold">No se pudo completar la colección</h2>
                    <p class="mt-1 text-sm leading-6 text-ink/70"><?= $escape($error); ?></p>
                    <p class="mt-2 text-sm leading-6 text-ink/70">
                        En XAMPP, añade <code class="rounded bg-ink/5 px-1.5 py-0.5">SetEnv PIXABAY_API_KEY "tu_clave"</code>
                        a la configuración de Apache y reinícialo.
                    </p>
                </section>
            <?php elseif ($imagenes === []): ?>
                <p class="my-8 border-l-4 border-moss bg-white/70 px-5 py-4" role="status">
                    Pixabay no devolvió imágenes para esta búsqueda.
                </p>
            <?php else: ?>
                <section aria-label="Imágenes de naturaleza" class="grid grid-cols-1 gap-x-5 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <?php foreach ($imagenes as $indice => $imagen): ?>
                        <article class="image-card group">
                            <div class="photo relative aspect-[4/3] overflow-hidden rounded-sm bg-ink/10">
                                <img
                                    src="<?= $escape($imagen->getImagen()); ?>"
                                    alt="Fotografía de naturaleza compartida por <?= $escape($imagen->getUser()); ?>"
                                    loading="<?= $indice < 4 ? 'eager' : 'lazy'; ?>"
                                    class="h-full w-full object-cover"
                                >
                                <span class="absolute left-3 top-3 bg-paper px-2.5 py-1 text-[10px] font-bold uppercase tracking-[.14em] text-ink">
                                    <?= str_pad((string) ($indice + 1), 2, '0', STR_PAD_LEFT); ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-3 border-b border-ink/20 py-3">
                                <p class="truncate text-sm font-medium">Por <?= $escape($imagen->getUser()); ?></p>
                                <span class="flex shrink-0 items-center gap-1.5 rounded-full bg-white/70 px-2.5 py-1 text-xs font-semibold" aria-label="<?= $imagen->getLikes(); ?> me gusta">
                                    <span class="text-clay" aria-hidden="true">♥</span>
                                    <?= number_format($imagen->getLikes(), 0, ',', '.'); ?>
                                </span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </main>

        <footer class="mt-16 flex flex-col gap-2 border-t border-ink/20 pt-5 text-xs text-ink/55 sm:flex-row sm:items-center sm:justify-between">
            <p>Atlas · práctica de PHP, Guzzle y Tailwind CSS</p>
            <p>Imágenes y datos proporcionados por Pixabay</p>
        </footer>
    </div>
</body>
</html>
