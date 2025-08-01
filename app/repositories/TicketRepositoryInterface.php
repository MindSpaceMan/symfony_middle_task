<?php
declare(strict_types=1);

namespace app\repositories;

use app\dto\Ticket;

interface TicketRepositoryInterface
{
    public function load(int|string $id): ?Ticket;
    public function save(Ticket $ticket): Ticket;     // create
    public function update(Ticket $ticket): Ticket;   // update
    public function delete(int|string $id): void;
}