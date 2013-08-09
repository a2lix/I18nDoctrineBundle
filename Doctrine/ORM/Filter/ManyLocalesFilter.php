<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class ManyLocalesFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the right interface
        if (!$targetEntity->reflClass->implementsInterface('\A2lix\I18nDoctrineBundle\Doctrine\Interfaces\ManyLocalesInterface')) {
            return "";
        }

        return $targetTableAlias .'.locales LIKE %'. $this->getParameter('locale') .'%';
    }

}