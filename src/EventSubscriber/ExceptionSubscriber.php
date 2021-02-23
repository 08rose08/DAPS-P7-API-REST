<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if($exception instanceof HttpException){
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];
            $event->setResponse(new JsonResponse($data));
        }else{
            $data = [
                //'status' => $exception->getStatusCode(),
                'status' => 500,
                'message' => $exception->getMessage()
            ];
            $event->setResponse(new JsonResponse($data));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
