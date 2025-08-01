<?php
declare(strict_types=1);

namespace app\dto;

use yii\base\Model;


final class Ticket
{
    public function __construct(
        public int|string $id,
        public string $title,
        public string $status,
        public ?\DateTimeImmutable $updatedAt = null,
        public ?string $externalId = null // если билет пришёл из внешнего API
    ) {}
}