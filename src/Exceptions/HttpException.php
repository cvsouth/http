<?php namespace Cvsouth\Http\Exceptions;

use Exception;

class HttpException extends Exception
{
    protected $method;

    protected $request_data;

    protected $request_headers;

    function __construct($method, $request_data, $request_headers, string $message = null, Throwable $previous = null)
    {
        if(empty($message)) $message = 'HTTP Exception';

        $this->method = $method;

        $this->request_data = $request_data;

        $this->request_headers = $request_headers;

        parent::__construct($message, 0, $previous);
    }
    function getMethod()
    {
        return $this->method;
    }
    function getRequestData()
    {
        return $this->request_data;
    }
    function getRequestHeaders()
    {
        return $this->request_headers;
    }
}
