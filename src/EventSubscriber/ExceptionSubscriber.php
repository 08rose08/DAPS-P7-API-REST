<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        //dd($exception);
        
        if($exception instanceof NotFoundHttpException){
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => 'Resource not found'
            ];
            $event->setResponse(new JsonResponse($data));
        }elseif($exception instanceof BadRequestHttpException){
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];
            $event->setResponse(new JsonResponse($data));
        
        }elseif($exception instanceof MethodNotAllowedHttpException){
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => getMessage()
            ];
            $event->setResponse(new JsonResponse($data));

        }else{
            $data = [
                'status' => $exception->getStatusCode(),
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
