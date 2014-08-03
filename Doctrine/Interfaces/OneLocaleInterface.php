<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\Interfaces;

/**
 *
 * @author David ALLIX
 */
interface OneLocaleInterface
{
    public function getLocale();

    public function setLocale($locale);
}