<?php
declare(strict_types=1);

namespace tests\unit;

use Market\FileStorageRepository;
use Market\services\LocalImageUrlGenerator;
use PHPUnit\Framework\TestCase;

final class LocalImageUrlGeneratorTest extends TestCase
{
    public function testReturnsUrlFromRepository(): void
    {
        $repo = $this->createMock(FileStorageRepository::class);
        $repo->expects($this->once())->method('getUrl')
            ->with('img/p1.png')->willReturn('https://cdn/app/p1.png');

        $gen = new LocalImageUrlGenerator($repo);
        $this->assertSame('https://cdn/app/p1.png', $gen->url('img/p1.png'));
    }

    public function testReturnsNullWhenRepositoryReturnsNull(): void
    {
        $repo = $this->createMock(FileStorageRepository::class);
        $repo->method('getUrl')->willReturn(null);

        $gen = new LocalImageUrlGenerator($repo);
        $this->assertNull($gen->url('missing.png'));
    }
}