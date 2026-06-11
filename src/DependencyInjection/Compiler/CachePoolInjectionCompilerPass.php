<?php

namespace IDCI\Bundle\TudorEStockClientBundle\DependencyInjection\Compiler;

use IDCI\Bundle\TudorEStockClientBundle\Client\TudorEStockApiClient;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CachePoolInjectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $cachePoolServiceAlias = $container->getParameter('idci_tudor_estock_client.cache_pool_service_alias');

        if (null !== $cachePoolServiceAlias) {
            $cachePoolDefinition = $container->findDefinition($cachePoolServiceAlias);
            $tudorClientDefinition = $container->findDefinition(TudorEStockApiClient::class);
            $tudorClientDefinition->addMethodCall('setCache', [$cachePoolDefinition]);
        }
    }
}
