<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\EventListener;

use A2lix\I18nDoctrineBundle\EventListener\DoctrineListener as BaseDoctrineListener,
    Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\ORM\Mapping\ClassMetadata,
    Doctrine\ORM\Events;

class DoctrineListener extends BaseDoctrineListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (null === $classMetadata->reflClass) {
            return;
        }

        // Translatable object?
        if ($this->isTranslatable($classMetadata->reflClass) && !$classMetadata->hasAssociation('translations')) {
            $classMetadata->mapOneToMany(array(
                'fieldName' => 'translations',
                'mappedBy' => 'translatable',
                'indexBy' => 'locale',
                'cascade' => array('persist', 'merge', 'remove'),
                'targetEntity' => $classMetadata->name . 'Translation'
            ));
        }

        // Translation object?
        if ($classMetadata->reflClass->hasProperty('translatable') && !$classMetadata->hasAssociation('translatable')) {
            $classMetadata->mapManyToOne(array(
                'fieldName' => 'translatable',
                'inversedBy' => 'translations',
                'joinColumns' => array(array(
                    'name' => 'translatable_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE'
                )),
                'targetEntity' => substr($classMetadata->name, 0, -11)
            ));

            // Unique constraint
            $name = $classMetadata->getTableName() . '_unique_translation';
            if (!$this->hasUniqueTranslationConstraint($classMetadata, $name)) {
                $classMetadata->setPrimaryTable(array(
                    'uniqueConstraints' => array(array(
                        'name' => $name,
                        'columns' => array('translatable_id', 'locale')
                    )),
                ));
            }
        }
    }

    protected function hasUniqueTranslationConstraint(ClassMetadata $classMetadata, $name)
    {
        if (!isset($classMetadata->table['uniqueConstraints'])) {
            return;
        }

        $constraints = array_filter($classMetadata->table['uniqueConstraints'], function($constraint) use ($name) {
            return $name === $constraint['name'];
        });

        return count($constraints);
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

}