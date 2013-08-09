<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\Util;

/**
 * One locale trait.
 *
 * Should be used inside entity, that needs to be localized
 */
trait OneLocale
{
    /**
     * @ORM\Column(length=10)
     */
    protected $locale;

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

}
