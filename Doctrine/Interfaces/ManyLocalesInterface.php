<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\Interfaces;

/**
 *
 * @author David ALLIX
 */
interface ManyLocalesInterface
{
    public function getLocales();

    public function setLocales($locales);
}