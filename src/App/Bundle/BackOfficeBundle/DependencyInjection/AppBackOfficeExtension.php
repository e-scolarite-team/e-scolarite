<?php

namespace App\Bundle\BackOfficeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

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
        $rootDir =  $container->getParameter('kernel.root_dir');
        $esConfigFile = $rootDir.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."escolarite_config.yml";

        $configuration = new EscolariteConfiguration();

        $parser = new Parser();
        try {
            $configs = $parser->parse(file_get_contents($esConfigFile));
        } catch (ParseException $e) {
            printf("Unable to parse the escolarite config YAML string: %s", $e->getMessage());
        }
        

        
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
