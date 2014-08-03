<?php

namespace A2lix\I18nDoctrineBundle\EventListener;

use Doctrine\Common\Annotations\Reader,
    Doctrine\Common\Persistence\ObjectManager;

/**
 * Controller Listener
 *
 * @author David ALLIX
 */
abstract class ControllerListener
{
    protected $annotationReader;
    protected $om;

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     */
    public function __construct(Reader $annotationReader, ObjectManager $om)
    {
        $this->annotationReader = $annotationReader;
        $this->om = $om;
    }

}