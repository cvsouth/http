<?php namespace Cvsouth\Http\Exceptions;

use Exception;

class ResponseException extends HttpException
{
    private $response_status_code;

    private $response_headers;

    function __construct($request_context, $response_status_code, $response_headers, Throwable $previous = null)
    {
        $this->response_status_code = $response_status_code;

        $this->response_headers = $response_headers;

        parent::__construct($request_context, 'HTTP Response Exception', $previous);
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
