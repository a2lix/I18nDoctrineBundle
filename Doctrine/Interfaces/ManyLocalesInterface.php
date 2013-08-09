<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\Interfaces;

interface ManyLocalesInterface
{
    public function getLocales();

    public function setLocales($locales);
}