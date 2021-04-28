<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EcreditProcessManagerExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config) {
            $container->setParameter('ecredit_process_manager.service.name', (string)$config['service']['name']);
            $container->setParameter('ecredit_process_manager.service.instance_name', (string)$config['service']['instance_name']);
            $container->setParameter('ecredit_process_manager.commands', $config['commands']);
        } else {
            $container->setParameter('ecredit_process_manager.service.name', "service");
            $container->setParameter('ecredit_process_manager.service.instance_name', "local");
            $container->setParameter('ecredit_process_manager.commands', []);
        }
    }

}
