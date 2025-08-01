<?php // modules/market/config/di.php
use Market\services\{LocalImageUrlGenerator,S3ImageUrlGenerator,ImageUrlResolver,ImageUrlGeneratorInterface};
use Market\FileStorageRepository;
use AwsS3\Client\AwsStorageInterface;

return [
    FileStorageRepository::class => fn() => new FileStorageRepository(),
    LocalImageUrlGenerator::class => fn($c) => new LocalImageUrlGenerator($c->get(FileStorageRepository::class)),
    S3ImageUrlGenerator::class    => fn($c) => new S3ImageUrlGenerator($c->get(AwsStorageInterface::class)),
    ImageUrlResolver::class       => fn($c) => new ImageUrlResolver(
        $c->get(LocalImageUrlGenerator::class),
        $c->get(S3ImageUrlGenerator::class)
    ),
];