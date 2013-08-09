<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\Util;

/**
 * Many locales trait.
 */
trait ManyLocales
{
    /**
     * @ORM\Column(type="simple_array")
     */
    protected $locales;

    public function getLocales()
    {
        return $this->locales;
    }

    public function setLocales($locales)
    {
        $this->locales = $locales;
        return $this;
    }
}
