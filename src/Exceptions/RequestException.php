<?php namespace Cvsouth\Http\Exceptions;

use Exception;

class RequestException extends HttpException
{
    function __construct($method, $request_data, $request_headers, $message = null, Throwable $previous = null)
    {
        if(empty($message)) $message = 'HTTP Request Exception';

        parent::__construct($method, $request_data, $request_headers, $message, $previous);
    }
}
