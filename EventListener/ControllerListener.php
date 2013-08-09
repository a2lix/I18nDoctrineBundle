<?php

namespace A2lix\I18nDoctrineBundle\EventListener;

use Doctrine\Common\Annotations\Reader,
    Doctrine\Common\Persistence\ObjectManager;

abstract class ControllerListener
{
    protected $annotationReader;
    protected $om;

    public function __construct(Reader $annotationReader, ObjectManager $om)
    {
        $this->annotationReader = $annotationReader;
        $this->om = $om;
    }

}