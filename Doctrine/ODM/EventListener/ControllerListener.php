<?php

namespace A2lix\I18nDoctrineBundle\Doctrine\ODM\EventListener;

use A2lix\I18nDoctrineBundle\EventListener\ControllerListener as BaseControllerListener,
    Symfony\Component\HttpKernel\Event\FilterControllerEvent,
    Doctrine\Common\Util\ClassUtils;

/**
 * Controller Listener
 *
 * @author David ALLIX
 */
class ControllerListener extends BaseControllerListener
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (\is_array($controller)) {
            list($object, $method) = $controller;
        } else {
            $object = $controller;
            $method = '__invoke';
        }

        $className = ClassUtils::getClass($object);
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($method);

        if ($this->annotationReader->getMethodAnnotation($reflectionMethod, 'A2lix\I18nDoctrineBundle\Annotation\I18nDoctrine')) {
            $this->om->getFilterCollection()->disable('oneLocale');

        } else {
            $this->om->getFilterCollection()->enable('oneLocale')->setParameter('locale', $event->getRequest()->getLocale());
        }
    }

}
