<?php
// app/repositories/CompositeTicketRepository.php
namespace app\repositories;

use app\dto\Ticket;

final class CompositeTicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        private DbTicketRepository $db,
        private ApiTicketRepository $api
    ) {}

    private function isExternalId(int|string $id): bool
    {
        return is_string($id) && str_starts_with($id, 'ext_'); // пример правила
    }

    public function load(int|string $id): ?Ticket
    {
        return $this->isExternalId($id) ? $this->api->load($id) : $this->db->load($id);
    }

    public function save(Ticket $ticket): Ticket
    {
        return $ticket->externalId ? $this->api->save($ticket) : $this->db->save($ticket);
    }

    public function update(Ticket $ticket): Ticket
    {
        return $ticket->externalId || $this->isExternalId($ticket->id)
            ? $this->api->update($ticket)
            : $this->db->update($ticket);
    }

    public function delete(int|string $id): void
    {
        $this->isExternalId($id) ? $this->api->delete($id) : $this->db->delete($id);
    }
}