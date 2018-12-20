<?php
namespace App\EventListener;

use App\Exception\BadRestRequestHttpException;
use App\Http\ErrorJsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        
        if($e instanceof BadRestRequestHttpException) {
            $response = new ErrorJsonResponse($e->getMessage(), $e->getErrors(), $e->getStatusCode());
        }
        else if($e instanceof HttpException) {
            $response = new ErrorJsonResponse($e->getMessage(), [], $e->getStatusCode());
        }
        else if($e instanceof \Exception) {
            $response = new ErrorJsonResponse($e->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        if(isset($response)) {
            $event->setResponse($response);
        }
    }
}