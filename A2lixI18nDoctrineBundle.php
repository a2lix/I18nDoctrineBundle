<?php

namespace A2lix\I18nDoctrineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author David ALLIX
 */
class A2lixI18nDoctrineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}