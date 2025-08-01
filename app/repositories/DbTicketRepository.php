<?php
// app/repositories/DbTicketRepository.php
namespace app\repositories;

use app\dto\Ticket;
use app\models\TicketAR;

final class DbTicketRepository implements TicketRepositoryInterface
{
    public function load(int|string $id): ?Ticket
    {
        $ar = TicketAR::find()->where(['id' => $id])->one();
        return $ar ? TicketAR::toDto($ar) : null;
    }

    public function save(Ticket $ticket): Ticket
    {
        $ar = new TicketAR();
        $ar->fromDto($ticket);
        if (!$ar->save()) {
            throw new \RuntimeException('DB save failed');
        }
        return TicketAR::toDto($ar);
    }

    public function update(Ticket $ticket): Ticket
    {
        $ar = TicketAR::findOne($ticket->id);
        if (!$ar) {
            throw new \RuntimeException('Ticket not found');
        }
        $ar->fromDto($ticket);
        if (!$ar->save(false)) {
            throw new \RuntimeException('DB update failed');
        }
        return TicketAR::toDto($ar);
    }

    public function delete(int|string $id): void
    {
        if (!TicketAR::deleteAll(['id' => $id])) {
            throw new \RuntimeException('Ticket not found');
        }
    }
}