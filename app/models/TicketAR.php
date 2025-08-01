<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;
use app\dto\Ticket;

final class TicketAR extends ActiveRecord
{
    public static function tableName(): string { return '{{%ticket}}'; }

    public function rules(): array
    {
        return [
            [['title','status'], 'required'],
            [['title','status'], 'string', 'max' => 255],
        ];
    }

    public static function toDto(self $ar): Ticket
    {
        return new Ticket(
            id: (int)$ar->id,
            title: (string)$ar->title,
            status: (string)$ar->status,
            updatedAt: $ar->updated_at ? new \DateTimeImmutable($ar->updated_at) : null
        );
    }

    public function fromDto(Ticket $t): void
    {
        $this->title  = $t->title;
        $this->status = $t->status;
    }
}