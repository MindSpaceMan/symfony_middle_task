<?php // modules/market/services/S3ImageUrlGenerator.php
namespace Market\services;

use AwsS3\Client\AwsStorageInterface;

final class S3ImageUrlGenerator implements ImageUrlGeneratorInterface
{
    public function __construct(private AwsStorageInterface $s3) {}
    public function url(string $path): ?string
    {
        if (!$this->s3->isAuthorized()) return null;
        return (string)$this->s3->getUrl($path); // AwsUrlInterface::__toString()
    }
}