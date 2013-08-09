<?php

namespace A2lix\I18nDoctrineBundle\EventListener;

use Doctrine\Common\EventSubscriber;

abstract class DoctrineListener implements EventSubscriber
{
    protected function isTranslatable(\ReflectionClass $reflClass, $isRecursive = false)
    {
        $isTranslatable = $reflClass->hasProperty('translations');

        while ($isRecursive && !$isTranslatable && $reflClass->getParentClass()) {
            $reflClass = $reflClass->getParentClass();
            $isTranslatable = $this->isTranslatable($reflClass, true);
        }

        return $isTranslatable;
    }

}