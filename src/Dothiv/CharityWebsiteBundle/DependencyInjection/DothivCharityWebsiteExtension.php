<?php

namespace Dothiv\CharityWebsiteBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DothivCharityWebsiteExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        $container->setParameter('dothiv_charity_website.features', $config['features']);
        $container->setParameter('dothiv_charity_website.bundles', array_keys($container->getParameter('kernel.bundles')));

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('controllers.yml');
        $loader->load('twig_extensions.yml');
        $loader->load('listeners.yml');
        $loader->load('reminders.yml');
        foreach ($config['features'] as $name => $feature) {
            if (!$feature['enabled']) {
                continue;
            }
            if (!$feature['config']) {
                continue;
            }
            $loader->load($name . '.feature.yml');
        }
    }
}
