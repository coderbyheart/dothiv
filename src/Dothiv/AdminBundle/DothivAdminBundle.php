<?php

namespace Dothiv\AdminBundle;

use Dothiv\AdminBundle\DependencyInjection\CompilerPass\DothivAdminBundleStatsReportCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DothivAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DothivAdminBundleStatsReportCompilerPass());
    }
}
