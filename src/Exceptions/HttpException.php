<?php namespace Cvsouth\Http\Exceptions;

use Exception;

class HttpException extends Exception
{
    protected $request_context;

    function __construct($request_context, string $message = null, Throwable $previous = null)
    {
        if(empty($message)) $message = 'HTTP Exception';

        $this->request_context = $request_context;

        parent::__construct($message, 0, $previous);
    }
    function getRequestContext()
    {
        return $this->request_context;
    }
}
