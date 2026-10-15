<?php
declare(strict_types=1);

namespace App;

class Imagen
{
    public function __construct(
        private readonly string $user,
        private readonly string $imagen,
        private readonly int $likes,
    ) {
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }
}
