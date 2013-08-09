<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ORM\EventListener;

use A2lix\I18nDoctrineBundle\EventListener\ControllerListener as BaseControllerListener,
    Symfony\Component\HttpKernel\Event\FilterControllerEvent,
    Doctrine\Common\Util\ClassUtils;

class ControllerListener extends BaseControllerListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        list($object, $method) = $controller;

        $className = ClassUtils::getClass($object);
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($method);

        if ($this->annotationReader->getMethodAnnotation($reflectionMethod, 'A2lix\I18nDoctrineBundle\Annotation\I18nDoctrine')) {
            $this->om->getFilters()->disable('oneLocale');

        } else {
            $this->om->getFilters()->enable('oneLocale')->setParameter('locale', $event->getRequest()->getLocale());
        }
    }

}