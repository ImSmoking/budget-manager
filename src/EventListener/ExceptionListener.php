<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\BaseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        if (!$exceptionEvent->isMainRequest()) {
            return;
        }

        $exception = $exceptionEvent->getThrowable();

        if ($exception instanceof BaseException) {
            $code = $exception->getCode() == 0 ? Response::HTTP_BAD_REQUEST : $exception->getCode();
            $content = [
                'data' => [
                    'message' => $exception->getMessage(),
                    'extra_data' => $exception->getExtraData()
                ]
            ];

            $exceptionEvent->setResponse(new JsonResponse($content, $code));
        }
    }
}