<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use App\Attribute\AsPaymentProcessor;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // Регистрируем атрибут AsPaymentProcessor для автоконфигурации
        $container->registerAttributeForAutoconfiguration(
            AsPaymentProcessor::class,
            function (ChildDefinition $definition, AsPaymentProcessor $attribute) {
                // Добавляем тег 'app.payment_processor' и передаём значение (alias) из атрибута
                $definition->addTag('app.payment_processor', [
                    'alias' => $attribute->alias // или ->value, в зависимости от объявления атрибута
                ]);
            }
        );
    }
}
