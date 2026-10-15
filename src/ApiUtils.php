<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;
use JsonException;
use RuntimeException;

class ApiUtils
{
    public static function getImagenes(): array
    {
        $apiKey = getenv('PIXABAY_API_KEY');
        if ($apiKey === false || trim($apiKey) === '') {
            throw new RuntimeException('Falta configurar la variable PIXABAY_API_KEY.');
        }

        $client = new Client([
            'base_uri' => 'https://pixabay.com/api/',
            'timeout' => 15,
            'connect_timeout' => 5,
        ]);

        $response = $client->get('', [
            'query' => [
                'key' => $apiKey,
                'q' => 'naturaleza',
                'image_type' => 'photo',
                'per_page' => 20,
                'safesearch' => 'true',
            ],
            'headers' => ['Accept' => 'application/json'],
        ]);

        try {
            $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Pixabay devolvió una respuesta JSON no válida.', 0, $exception);
        }

        if (!is_array($data) || !isset($data['hits']) || !is_array($data['hits'])) {
            throw new RuntimeException('La respuesta de Pixabay no contiene una lista de imágenes válida.');
        }

        $imagenes = [];
        foreach ($data['hits'] as $hit) {
            if (
                !is_array($hit)
                || !isset($hit['user'], $hit['webformatURL'], $hit['likes'])
                || !is_string($hit['user'])
                || !is_string($hit['webformatURL'])
                || !is_numeric($hit['likes'])
            ) {
                throw new RuntimeException('Pixabay devolvió una imagen con datos incompletos.');
            }

            $imagenes[] = new Imagen(
                user: $hit['user'],
                imagen: $hit['webformatURL'],
                likes: (int) $hit['likes'],
            );
        }

        return $imagenes;
    }
}
