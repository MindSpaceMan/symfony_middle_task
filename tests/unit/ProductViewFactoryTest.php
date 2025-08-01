<?php
declare(strict_types=1);

namespace tests\unit;

use Market\dto\ProductView;
use Market\models\ProductAR;
use Market\models\ProductImageAR;
use Market\services\ImageUrlGeneratorInterface;
use Market\services\ImageUrlResolver;
use Market\services\ProductViewFactory;
use PHPUnit\Framework\TestCase;

final class ProductViewFactoryTest extends TestCase
{
    public function testImagesAndLegacyImageUrlAndFavoriteFlag(): void
    {
        // Моки генераторов
        $local = new class implements ImageUrlGeneratorInterface {
            public function url(string $path): ?string { return 'https://cdn/' . $path; }
        };
        $s3 = new class implements ImageUrlGeneratorInterface {
            public function url(string $path): ?string { return 'https://s3/' . $path; }
        };
        $resolver = new ImageUrlResolver($local, $s3);
        $factory  = new ProductViewFactory($resolver);

        // Фиктивная модель продукта с двумя картинками
        $p = new class extends ProductAR {
            public $id = 10;
            public $name = 'Phone';
            public $description = 'Desc';
            public $category = 'gadgets';
            public array $images = [];
        };
        $img1 = new class('local','img/p1.png') extends ProductImageAR {
            public function __construct(public $source, public $path) {}
        };
        $img2 = new class('s3','p1-2.jpg') extends ProductImageAR {
            public function __construct(public $source, public $path) {}
        };
        $p->images = [$img1, $img2];

        $favMap = [10 => true];
        $view = $factory->create($p, $favMap);

        $this->assertInstanceOf(ProductView::class, $view);
        $this->assertSame(10, $view->id);
        $this->assertSame(['https://cdn/img/p1.png','https://s3/p1-2.jpg'], $view->images);
        $this->assertSame('https://cdn/img/p1.png', $view->image_url);  // back-compat
        $this->assertTrue($view->is_favorite);
    }

    public function testGuestHasNoFavoriteFlag(): void
    {
        $resolver = new ImageUrlResolver(
            new class implements ImageUrlGeneratorInterface { public function url(string $p): ?string { return 'x'; } },
            new class implements ImageUrlGeneratorInterface { public function url(string $p): ?string { return 'y'; } },
        );
        $factory  = new ProductViewFactory($resolver);

        $p = new class extends ProductAR {
            public $id = 1; public $name='N'; public $description='D'; public $category='C'; public array $images=[];
        };

        $view = $factory->create($p, null); // гость
        $this->assertNull($view->is_favorite);
    }
}