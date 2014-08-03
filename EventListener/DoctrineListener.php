<?php

namespace A2lix\I18nDoctrineBundle\EventListener;

use Doctrine\Common\EventSubscriber;

/**
 * Doctrine Listener
 *
 * @author David ALLIX
 */
abstract class DoctrineListener implements EventSubscriber
{
    /**
     *
     * @param \ReflectionClass $reflClass
     * @param type $traitName
     * @param type $isRecursive
     * @return boolean
     */
    protected function hasTrait(\ReflectionClass $reflClass, $traitName, $isRecursive = false)
    {
        if (in_array($traitName, $reflClass->getTraitNames())) {
            return true;
        }

        $parentClass = $reflClass->getParentClass();

        if ((false === $isRecursive) || (false === $parentClass) || (null === $parentClass)) {
            return false;
        }

        return $this->hasTrait($parentClass, $traitName, $isRecursive);
    }

}