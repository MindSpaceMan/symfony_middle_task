<?php // modules/market/services/ImageUrlResolver.php
namespace Market\services;

final class ImageUrlResolver
{
    public function __construct(
        private ImageUrlGeneratorInterface $local,
        private ImageUrlGeneratorInterface $s3,
    ) {}

    public function bySource(string $source): ImageUrlGeneratorInterface
    {
        return $source === 's3' ? $this->s3 : $this->local;
    }
}