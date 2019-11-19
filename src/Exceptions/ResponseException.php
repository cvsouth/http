<?php namespace Cvsouth\Http\Exceptions;

use Exception;

class ResponseException extends HttpException
{
    private $response_status_code;

    private $response_headers;

    function __construct($method, $request_data, $request_headers, $response_status_code, $response_headers, $message = null, Throwable $previous = null)
    {
        $this->response_status_code = $response_status_code;

        $this->response_headers = $response_headers;

        if($message === null) $message = 'HTTP Response Exception';

        parent::__construct($method, $request_data, $request_headers, $message, $previous);
    }
    function getResponseStatusCode()
    {
        return $this->response_status_code;
    }
    function getResponseHeaders()
    {
        return $this->response_headers;
    }
}
