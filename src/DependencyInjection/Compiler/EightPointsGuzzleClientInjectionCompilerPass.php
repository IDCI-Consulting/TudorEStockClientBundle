<?php

namespace IDCI\Bundle\TudorEStockClientBundle\DependencyInjection\Compiler;

use IDCI\Bundle\TudorEStockClientBundle\Client\TudorEStockApiClient;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EightPointsGuzzleClientInjectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $guzzleHttpClientServiceAlias = $container->getParameter('idci_tudor_estock_client.guzzle_http_client_service_alias');

        $httpClientDefinition = $container->findDefinition($guzzleHttpClientServiceAlias);
        $tudorClientDefinition = $container->findDefinition(TudorEStockApiClient::class);
        $tudorClientDefinition->addMethodCall('setHttpClient', [$httpClientDefinition]);
    }
}
