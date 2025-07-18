<?php
declare(strict_types=1);

namespace App\Attribute;

use Attribute;
#[Attribute(Attribute::TARGET_CLASS)]
class AsPaymentProcessor
{
    public function __construct(public string $alias) {}
}