<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HandlerPass implements CompilerPassInterface
{
    const MANAGER_SERVICE = 'app.handler_manager';

    const HANDLER_TAG = 'app.handler';

    /**
     * {@inheritdoc}
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(self::MANAGER_SERVICE)) {
            return;
        }

        $definition = $container->findDefinition(self::MANAGER_SERVICE);

        $taggedServices = $container->findTaggedServiceIds(self::HANDLER_TAG);

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addHandler', [
                    new Reference($id),
                    $attributes['alias'],
                ]);
            }
        }
    }
}
