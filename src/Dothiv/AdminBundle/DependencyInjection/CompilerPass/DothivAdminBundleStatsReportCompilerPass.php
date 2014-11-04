<?php

namespace Dothiv\AdminBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DothivAdminBundleStatsReportCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('dothiv.admin.service.stats')) {
            return;
        }

        $statsService = $container->getDefinition(
            'dothiv.admin.service.stats'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'dothiv_admin.stats.reporter'
        );
        foreach ($taggedServices as $id => $attributes) {
            $statsService->addMethodCall(
                'addReporter',
                array($attributes[0]['id'], new Reference($id))
            );
        }
    }
}
