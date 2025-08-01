<?php // modules/market/services/ImageUrlGeneratorInterface.php
namespace Market\services;

interface ImageUrlGeneratorInterface
{
    /** Возвращает абсолютный URL для пути в своём сторадже */
    public function url(string $path): ?string;
}