<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\Util;

use ReflectionClass;

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
        return (new ReflectionClass())->getShortName($this) . 'Translation';
    }

    public function getCurrentTranslation()
    {
        return $this->getTranslations()->first();
    }

    public function __call($method, $args)
    {
        $method = ('get' === substr($method, 0, 3)) ? $method : 'get'. ucfirst($method);

        if (!$translation = $this->getCurrentTranslation()) {
            return;
        }

        if (method_exists($translation, $method)) {
            return call_user_func(array($translation, $method));
        }

        return;
    }

}
