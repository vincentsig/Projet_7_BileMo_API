<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Exception\ResourceValidationException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof \Exception) {
            $data = [
                'code' => 500,
                'message' => 'Internal Server Error'
            ];
        }

        if ($exception instanceof InvalidArgumentException) {
            $data = [
                'code' => 400,
                'message' => $exception->getMessage(),
            ];
        }

        if ($exception instanceof BadRequestHttpException) {
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => 'Bad request'
            ];
        }

        if ($exception instanceof ResourceValidationException) {
            $fields = json_decode($exception->getMessage());
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => 'The JSON sent contains invalid data',
                'invalid data: ' => $fields,
            ];
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => 'Acces Denied'
            ];
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => 'Method not allowed'
            ];
        }

        if ($exception instanceof NotFoundHttpException) {
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => 'Resource not found'
            ];
        }

        $response = new JsonResponse($data);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
