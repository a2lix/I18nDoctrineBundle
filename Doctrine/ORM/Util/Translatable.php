<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\Util;

/**
 * Translatable trait.
 *
 * Should be used inside entity, that needs to be translated.
 */
trait Translatable
{
    public function getTranslations()
    {
        return $this->translations = $this->translations ? : new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
        return $this;
    }

    public function addTranslation($translation)
    {
        $this->getTranslations()->set($translation->getLocale(), $translation);
        $translation->setTranslatable($this);
        return $this;
    }

    public function removeTranslation($translation)
    {
        $this->getTranslations()->removeElement($translation);
    }

    public static function getTranslationEntityClass()
    {
        return __CLASS__ . 'Translation';
    }

    public function getCurrentTranslation()
    {
        return $this->getTranslations()->first();
    }

    public function __call($method, $args)
    {
        return ($translation = $this->getCurrentTranslation()) ?
                call_user_func(array(
                    $translation,
                    'get' . ucfirst($method)
                )) : '';
    }

}
