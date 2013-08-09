<?php

namespace A2lix\I18nDoctrineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader;

/**
 * @author David ALLIX
 */
class A2lixI18nDoctrineExtension extends Extension
{
    /**
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('a2lix_i18n_doctrine.locales', $config['locales']);
        $container->setParameter('a2lix_i18n_doctrine.manager_registry', $config['manager_registry']);
//        $container->setParameter('a2lix_i18n_doctrine.enable_filters', $config['enable_filters']);

        // ORM
        if ('doctrine' === $config['manager_registry']) {
            $container->setAlias('a2lix_i18n_doctrine.object_manager', 'doctrine.orm.entity_manager');
            $container->setParameter('a2lix_i18n_doctrine.listener.controller.class', 'A2lix\I18nDoctrineBundle\Doctrine\ORM\EventListener\ControllerListener');
            $container->setParameter('a2lix_i18n_doctrine.listener.doctrine.class', 'A2lix\I18nDoctrineBundle\Doctrine\ORM\EventListener\DoctrineListener');

        // ODM MongoDB
        } elseif ('doctrine_mongodb' === $config['manager_registry']) {
            $container->setAlias('a2lix_i18n_doctrine.object_manager', 'doctrine.odm.document_manager');
            $container->setParameter('a2lix_i18n_doctrine.listener.controller.class', 'A2lix\I18nDoctrineBundle\Doctrine\ODM\EventListener\ControllerListener');
            $container->setParameter('a2lix_i18n_doctrine.listener.doctrine.class', 'A2lix\I18nDoctrineBundle\Doctrine\ODM\EventListener\DoctrineListener');
        }
    }

}
