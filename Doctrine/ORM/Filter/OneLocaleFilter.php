<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class OneLocaleFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the right interface
        if (!$targetEntity->reflClass->implementsInterface('\A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface')) {
            return "";
        }

        return $targetTableAlias .'.locale = '. $this->getParameter('locale');
    }

}