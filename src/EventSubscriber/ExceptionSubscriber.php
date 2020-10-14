<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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

        if ($exception instanceof \Exception) {
            $data = [
                'code' => 500,
                'message' => 'Internal Server Error'
            ];
        }

        if ($exception instanceof UniqueConstraintViolationException) {
            $data = [
                'code' => '400',
                'message' => 'Your email exists already',
            ];
        }

        if ($exception instanceof BadRequestHttpException) {
            $data = [
                'code' => '400',
                'message' => 'Bad request'
            ];
        }

        if ($exception instanceof AccessDeniedHttpException)
            $data = [
                'code' => '403',
                'message' => 'Acces Denied'
            ];

        if ($exception instanceof MethodNotAllowedHttpException) {
            $data = [
                'code' => '405',
                'message' => 'Method not allowed'
            ];
        }

        if ($exception instanceof NotFoundHttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
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
