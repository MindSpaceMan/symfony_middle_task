<?php
// app/repositories/ApiTicketRepository.php
namespace app\repositories;

use app\components\TicketApiClient;
use app\dto\Ticket;

final class ApiTicketRepository implements TicketRepositoryInterface
{
    public function __construct(private TicketApiClient $api) {}

    public function load(int|string $id): ?Ticket
    {
        $d = $this->api->get((string)$id);
        return new Ticket(
            id: $d['id'],
            title: $d['title'],
            status: $d['status'],
            updatedAt: isset($d['updated_at']) ? new \DateTimeImmutable($d['updated_at']) : null,
            externalId: $d['id']
        );
    }

    public function save(Ticket $ticket): Ticket
    {
        $d = $this->api->create(['title' => $ticket->title, 'status' => $ticket->status]);
        return $this->load($d['id']);
    }

    public function update(Ticket $ticket): Ticket
    {
        $id = (string)($ticket->externalId ?? $ticket->id);
        $d  = $this->api->patch($id, ['title' => $ticket->title, 'status' => $ticket->status]);
        return $this->load($d['id'] ?? $id);
    }

    public function delete(int|string $id): void
    {
        $this->api->delete((string)$id);
    }
}