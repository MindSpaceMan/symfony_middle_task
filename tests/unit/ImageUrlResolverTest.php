<?php
declare(strict_types=1);

namespace tests\unit;

use Market\services\ImageUrlGeneratorInterface;
use Market\services\ImageUrlResolver;
use PHPUnit\Framework\TestCase;

final class ImageUrlResolverTest extends TestCase
{
    public function testSelectsS3ForS3Source(): void
    {
        $local = new class implements ImageUrlGeneratorInterface {
            public function url(string $path): ?string { return 'local://' . $path; }
        };
        $s3 = new class implements ImageUrlGeneratorInterface {
            public function url(string $path): ?string { return 's3://' . $path; }
        };

        $resolver = new ImageUrlResolver($local, $s3);
        $this->assertSame('s3://x', $resolver->bySource('s3')->url('x'));
    }

    public function testFallsBackToLocal(): void
    {
        $local = new class implements ImageUrlGeneratorInterface {
            public function url(string $path): ?string { return 'local://' . $path; }
        };
        $s3 = new class implements ImageUrlGeneratorInterface {
            public function url(string $path): ?string { return 's3://' . $path; }
        };

        $resolver = new ImageUrlResolver($local, $s3);
        $this->assertSame('local://y', $resolver->bySource('local')->url('y'));
        $this->assertSame('local://z', $resolver->bySource('unknown')->url('z')); // дефолт
    }
}