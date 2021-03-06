<?php

namespace Dothiv\LandingpageBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DothivLandingpageExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('repositories.yml');
        $loader->load('services.yml');
        $loader->load('controllers.yml');
        if ($container->getParameter("kernel.environment") != 'test') {
            $loader->load('listeners.yml');
        }
    }
}
