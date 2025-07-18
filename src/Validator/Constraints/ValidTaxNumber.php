<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class ValidTaxNumber extends Constraint
{
    public string $message = 'Invalid tax number "{{ value }}".';

    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }
    public function validatedBy(): string
    {
        return ValidTaxNumber::class.'Validator';
    }
}
