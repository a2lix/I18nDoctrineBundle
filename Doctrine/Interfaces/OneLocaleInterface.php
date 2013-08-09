<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\Interfaces;

interface OneLocaleInterface
{
    public function getLocale();

    public function setLocale($locale);
}