<?php
namespace  WhiteOctober\PagerfantaBundle\EventListener;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ConvertNotValidCurrentPageToNotFoundListener implements EventSubscriberInterface
{
    /**
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event)
    {
        if (method_exists($event, 'getThrowable')) {
            $throwable = $event->getThrowable();
        }

        if ($throwable instanceof NotValidCurrentPageException) {
            $notFoundHttpException = new NotFoundHttpException('Page Not Found', $throwable);
            if (method_exists($event, 'setThrowable')) {
                $event->setThrowable($notFoundHttpException);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array('onException', 512)
        );
    }
}
