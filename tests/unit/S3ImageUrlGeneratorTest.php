<?php
declare(strict_types=1);

namespace tests\unit;

use AwsS3\AwsUrlInterface;
use AwsS3\Client\AwsStorageInterface;
use Market\services\S3ImageUrlGenerator;
use PHPUnit\Framework\TestCase;

final class S3ImageUrlGeneratorTest extends TestCase
{
    public function testReturnsNullWhenUnauthorized(): void
    {
        $s3 = $this->createMock(AwsStorageInterface::class);
        $s3->method('isAuthorized')->willReturn(false);

        $gen = new S3ImageUrlGenerator($s3);
        $this->assertNull($gen->url('p1.jpg'));
    }

    public function testReturnsStringUrlWhenAuthorized(): void
    {
        $s3 = $this->createMock(AwsStorageInterface::class);
        $s3->method('isAuthorized')->willReturn(true);

        $awsUrl = new class('https://s3.test/p1.jpg') implements AwsUrlInterface {
            public function __construct(private string $u) {}
            public function __toString(): string { return $this->u; }
        };

        $s3->expects($this->once())->method('getUrl')
            ->with('p1.jpg')->willReturn($awsUrl);

        $gen = new S3ImageUrlGenerator($s3);
        $this->assertSame('https://s3.test/p1.jpg', $gen->url('p1.jpg'));
    }
}