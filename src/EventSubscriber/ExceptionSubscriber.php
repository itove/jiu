<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\DBAL\Exception\DriverException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // ...
        // dd($event);
        $exception = $event->getThrowable();
        dump($exception);

        // Customize your response object to display the exception details
        if ($exception instanceof NotFoundHttpException) {
        }
        if ($exception instanceof AccessDeniedException) {
        }
        if ($exception instanceof DriverException){
            $message = sprintf(
                '代金券不足！错误代码: %s',
                // $exception->getMessage(),
                $exception->getCode()
            );
            // sends the modified response object to the event
            $response = new Response();
            $response->setContent($message);

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
