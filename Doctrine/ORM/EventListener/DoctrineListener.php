<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\EventListener;

use A2lix\I18nDoctrineBundle\EventListener\DoctrineListener as BaseDoctrineListener,
    Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\ORM\Mapping\ClassMetadataInfo,
    Doctrine\ORM\Mapping\ClassMetadata,
    Doctrine\ORM\Events;

/**
 * Doctrine ORM Listener
 *
 * KnpDoctrineBehaviors (https://github.com/KnpLabs/DoctrineBehaviors/) inspiration
 *
 * @author David ALLIX
 */
class DoctrineListener extends BaseDoctrineListener
{
    private $translatableTrait;
    private $translationTrait;
    private $translatableFetchMode;
    private $translationFetchMode;
    private $isRecursive;

    /**
     *
     * @param string $translatableTrait
     * @param string $translationTrait
     * @param string $translatableFetchMode
     * @param string $translationFetchMode
     * @param boolean $isRecursive
     */
    public function __construct($translatableTrait, $translationTrait, $translatableFetchMode, $translationFetchMode, $isRecursive)
    {
        $this->translatableTrait = $translatableTrait;
        $this->translationTrait = $translationTrait;
        $this->translatableFetchMode = $this->convertFetchString($translatableFetchMode);
        $this->translationFetchMode = $this->convertFetchString($translationFetchMode);
        $this->isRecursive = $isRecursive;
    }

    /**
     *
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     * @return type
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (null === $classMetadata->reflClass) {
            return;
        }

        // Translatable object?
        if ($this->hasTrait($classMetadata->reflClass, $this->translatableTrait, $this->isRecursive)
                && !$classMetadata->hasAssociation('translations') && !$classMetadata->reflClass->isAbstract()) {

            $classMetadata->mapOneToMany(array(
                'fieldName' => 'translations',
                'mappedBy' => 'translatable',
                'fetch' => $this->translationFetchMode,
                'indexBy' => 'locale',
                'cascade' => array('persist', 'merge', 'remove'),
                'targetEntity' => $classMetadata->name . 'Translation'
            ));
        }

        // Translation object?
        if ($this->hasTrait($classMetadata->reflClass, $this->translationTrait, $this->isRecursive)
                && !$classMetadata->hasAssociation('translatable') && !$classMetadata->reflClass->isAbstract()) {

            $classMetadata->mapManyToOne(array(
                'fieldName' => 'translatable',
                'inversedBy' => 'translations',
                'fetch' => $this->translationFetchMode,
                'joinColumns' => array(array(
                    'name' => 'translatable_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE'
                )),
                'targetEntity' => substr($classMetadata->name, 0, -11)
            ));

            // Unique constraint
            $name = $classMetadata->getTableName() .'_unique_translation';
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

    /**
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     * @param type $name
     * @return boolean
     */
    protected function hasUniqueTranslationConstraint(ClassMetadata $classMetadata, $name)
    {
        if (!isset($classMetadata->table['uniqueConstraints'])) {
            return;
        }

        foreach ($classMetadata->table['uniqueConstraints'] as $constraintName => $constraint) {
            if ($name === $constraintName) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param type $fetchMode
     * @return int
     */
    private function convertFetchString($fetchMode)
    {
        if (is_int($fetchMode)) {
            return $fetchMode;
        }

        switch ($fetchMode) {
            case "EAGER":
                return ClassMetadataInfo::FETCH_EAGER;
            case "EXTRA_LAZY":
                return ClassMetadataInfo::FETCH_EXTRA_LAZY;
            default:
                return ClassMetadataInfo::FETCH_LAZY;
        }
    }

    /**
     *
     * @return type
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

}
