<?php

namespace Rias\StatamicRedirect\Exceptions;

use Statamic\Exceptions\Concerns\RendersHttpExceptions;
use Symfony\Component\HttpKernel\Exception\GoneHttpException as SymfonyException;

class GoneHttpException extends SymfonyException
{
    use RendersHttpExceptions;

    public function getApiMessage()
    {
        return 'Gone.';
    }
}
