<?php namespace Cvsouth\Http\Exceptions;

use Exception;

class RequestException extends HttpException
{
    function __construct($request_context, $message = null, Throwable $previous = null)
    {
        if(empty($message)) $message = 'HTTP Request Exception';

        parent::__construct($request_context, $message, $previous);
    }
}
