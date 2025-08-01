<?php

use Application\OrderService;
use Domain\Entity\Order;
use Domain\Entity\OrderItem;
use Domain\ValueObject\Money;
use Infrastructure\Persistence\InMemoryOrderRepository;

$repo   = new InMemoryOrderRepository();
$svc    = new OrderService($repo);
$order  = new Order(id: 'ORD-1');
$repo->save($order);

$svc->addItem('ORD-1', ['sku'=>'SKU1','name'=>'Кружка','qty'=>2,'price_minor'=>9900,'currency'=>'RUB']);
$svc->addItem('ORD-1', ['sku'=>'SKU2','name'=>'Футболка','qty'=>1,'price_minor'=>19900,'currency'=>'RUB']);

echo $svc->printOrder('ORD-1'), PHP_EOL;     // человекочитаемый принт
var_dump($svc->showOrder('ORD-1'));          // для API/UI