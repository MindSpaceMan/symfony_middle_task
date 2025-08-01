<?php

// config/container.php  (подключается из web.php/console.php)
use yii\httpclient\Client;
use app\repositories\{
    TicketRepositoryInterface, CompositeTicketRepository,
    DbTicketRepository, ApiTicketRepository
};
use app\components\TicketApiClient;

return [
    Client::class => static fn() => new Client(['baseUrl' => getenv('TICKET_API_URL')]),
    TicketApiClient::class => static fn(\yii\di\Container $c) => new TicketApiClient($c->get(Client::class)),

    DbTicketRepository::class => DbTicketRepository::class,
    ApiTicketRepository::class => ApiTicketRepository::class,
    TicketRepositoryInterface::class => CompositeTicketRepository::class,
];

///
///
//// пример использования (например, в сервисе/контроллере)
//// $repo = Yii::$container->get(\app\repositories\TicketRepositoryInterface::class);
//// $ticket = $repo->load('ext_123'); // из API
//// $ticket = $repo->load(42);        // из БД