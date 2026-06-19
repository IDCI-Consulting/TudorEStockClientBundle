<?php

namespace IDCI\Bundle\TudorEStockClientBundle;

use IDCI\Bundle\TudorEStockClientBundle\DependencyInjection\Compiler\CachePoolInjectionCompilerPass;
use IDCI\Bundle\TudorEStockClientBundle\DependencyInjection\Compiler\EightPointsGuzzleClientInjectionCompilerPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class IDCITudorEStockClientBundle extends AbstractBundle
{
    protected string $extensionAlias = 'idci_tudor_estock_client';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('guzzle_http_client_service_alias')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('cache_pool_service_alias')->defaultValue(null)->cannotBeEmpty()->end()
                ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('client_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('scope')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('issuer')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $builder->setParameter(sprintf('%s.guzzle_http_client_service_alias', $this->extensionAlias), $config['guzzle_http_client_service_alias']);
        $builder->setParameter(sprintf('%s.cache_pool_service_alias', $this->extensionAlias), $config['cache_pool_service_alias']);
        $builder->setParameter(sprintf('%s.client_secret', $this->extensionAlias), $config['client_secret']);
        $builder->setParameter(sprintf('%s.client_id', $this->extensionAlias), $config['client_id']);
        $builder->setParameter(sprintf('%s.scope', $this->extensionAlias), $config['scope']);
        $builder->setParameter(sprintf('%s.issuer', $this->extensionAlias), $config['issuer']);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CachePoolInjectionCompilerPass());
        $container->addCompilerPass(new EightPointsGuzzleClientInjectionCompilerPass());
    }
}
