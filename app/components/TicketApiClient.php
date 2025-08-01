<?php
// app/components/TicketApiClient.php
namespace app\components;

use yii\httpclient\Client;

final class TicketApiClient
{
    public function __construct(
        private Client $client = new Client(['baseUrl' => null]) // задайте baseUrl через DI
    ) {}

    public function get(string $id): array
    {
        $resp = $this->client->get("tickets/$id")->send();
        if (!$resp->isOk) {
            throw new \RuntimeException('API get failed');
        }
        return $resp->data;
    }

    public function create(array $payload): array
    {
        $resp = $this->client->post('tickets', $payload)->send();
        if (!$resp->isOk) {
            throw new \RuntimeException('API create failed');
        }
        return $resp->data;
    }

    public function patch(string $id, array $payload): array
    {
        $resp = $this->client->createRequest()
            ->setMethod('PATCH')->setUrl("tickets/$id")->setData($payload)->send();
        if (!$resp->isOk) {
            throw new \RuntimeException('API patch failed');
        }
        return $resp->data;
    }

    public function delete(string $id): void
    {
        $resp = $this->client->delete("tickets/$id")->send();
        if (!$resp->isOk) {
            throw new \RuntimeException('API delete failed');
        }
    }
}