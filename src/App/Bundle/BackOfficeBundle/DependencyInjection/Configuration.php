<?php

namespace App\Bundle\BackOfficeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_back_office');
        $rootNode->children()
                        ->scalarNode("date_format")
                            ->defaultValue("d-m-Y")
                        ->end()
                        ->scalarNode("datetime_format")
                            ->defaultValue("d-m-Y\TH:i")
                        ->end()
                        ->integerNode("current_semester")
                            ->defaultValue("10")
                            ->min(1)
                        ->end()
                        ->integerNode("current_academic_year")
                            ->min(2013)
                        ->end()
                        ->scalarNode("activate_service")
                            ->isRequired()
                            ->validate()
                                ->ifNotInArray(array("yes","no"))
                                    ->thenInvalid('activate_service value "%s" not correct in config.yml')
                                ->end()
                        ->end()
                        ->arrayNode("auto_demands_answers")
                            ->children()
                                ->scalarNode("status")
                                    ->isRequired()
                                    ->validate()
                                        ->ifNotInArray(array("activate","deativate"))
                                            ->thenInvalid('auto_demands_answers.status value "%s" not correct in config.yml')
                                        ->end()
                                ->end()
                                ->integerNode("amount")
                                    ->min(1)
                                ->end()
                        ->end()
                    ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
