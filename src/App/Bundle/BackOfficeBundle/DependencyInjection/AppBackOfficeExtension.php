<?php

namespace App\Bundle\BackOfficeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AppBackOfficeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // set default value
        $container->setParameter("app_back_office.date_format",$config['date_format']);
        $container->setParameter("app_back_office.datetime_format",$config['datetime_format']);
        $container->setParameter("app_back_office.current_semester",$config['current_semester']);
        $container->setParameter("app_back_office.current_academic_year",$config['current_academic_year']);
        $container->setParameter("app_back_office.activate_service",$config['activate_service']);
        $container->setParameter("app_back_office.auto_demands_answers.status",$config['auto_demands_answers']['status']);
        $container->setParameter("app_back_office.auto_demands_answers.amount",$config['auto_demands_answers']['amount']);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
