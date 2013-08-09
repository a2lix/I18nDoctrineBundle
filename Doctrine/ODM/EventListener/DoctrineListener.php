<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ODM\EventListener;

use A2lix\I18nDoctrineBundle\EventListener\DoctrineListener as BaseDoctrineListener,
    Doctrine\ODM\Event\LoadClassMetadataEventArgs,
    Doctrine\ODM\Events;

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
            $classMetadata->mapManyEmbedded(array(
                'fieldName' => 'translations',
                'targetDocument' => $classMetadata->name . 'Translation',
                'strategy' => 'pushAll',
            ));
        }
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

}