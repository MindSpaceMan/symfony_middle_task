<?php // modules/market/services/LocalImageUrlGenerator.php
namespace Market\services;

use Market\FileStorageRepository; // из задания

final class LocalImageUrlGenerator implements ImageUrlGeneratorInterface
{
    public function __construct(private FileStorageRepository $fs) {}
    public function url(string $path): ?string { return $this->fs->getUrl($path); }
}