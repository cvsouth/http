<?php

use Cvsouth\Http\Enums\HttpStatusCode;

use Cvsouth\Http\Exceptions\RequestException;

use Cvsouth\Http\Exceptions\InformationalResponseException;

use Cvsouth\Http\Exceptions\RedirectionResponseException;

use Cvsouth\Http\Exceptions\ClientErrorResponseException;

use Cvsouth\Http\Exceptions\ServerErrorResponseException;
use Illuminate\Support\Str;

function http_get($url, $headers = [], &$response_headers = [], $return_stream = false)
{
    return http_response($url, 'GET', null, $headers, $return_stream, $response_headers);
}
function http_post($url, $data = [], $headers = [], &$response_headers = [], $return_stream = false)
{
    if(empty($data)) $data = [];

    if(is_array($data))
    {
        $data = http_build_query($data);

        $headers = array_merge(
        [
            'Content-Type: application/x-www-form-urlencoded',
        ],
        $headers);
    }
    $headers = array_merge(
    [
        'Content-Length: ' . strlen($data),
    ],
    $headers);

    return http_response($url, 'POST', $data, $headers, $return_stream, $response_headers);
}
function http_response($url, $method, $data, $headers, $return_stream, &$response_headers)
{
    if(empty($headers)) $headers = [];

    $context = http_context($method, $data, $headers);

    try
    {
        $response = fopen($url, 'r', false, $context);

        if($response === false)
        {
            $error = error_get_last();

            throw new RequestException($method, $data, $headers, $error['message']);
        }
    }
    catch(Exception $e)
    {
        $message = str_replace('HTTP/1.0', 'HTTP/1.1', $e->getMessage());

        if(($position = strpos($message, 'HTTP/1.1 ')) !== false)
        {
            list($status_code) = explode(' ', substr($message, $position + 9), 2);

            $status_code = (int) trim($status_code);

            $response_headers = [];

            $message = substr($e->getMessage(), $position) . '[' . $url . ']';

            if(HttpStatusCode::isInformational($status_code))

                throw new InformationalResponseException($method, $data, $headers, $status_code, $response_headers, $message);

            elseif(HttpStatusCode::isRedirection($status_code))

                throw new RedirectionResponseException($method, $data, $headers, $status_code, $response_headers, $message);

            elseif(HttpStatusCode::isClientError($status_code))

                throw new ClientErrorResponseException($method, $data, $headers, $status_code, $response_headers, $message);

            elseif(HttpStatusCode::isServerError($status_code))

                throw new ServerErrorResponseException($method, $data, $headers, $status_code, $response_headers, $message);
        }
        throw new RequestException($method, $data, $headers, $message);
    }
    $response_headers = http_response_headers($response);

    if($return_stream) return $response;

    else return stream_get_contents($response);
}
function http_get_stream($url, $headers = [], &$response_headers = [])
{
    return http_get($url, $headers, $response_headers, true);
}
function http_post_stream($url, $data = [], $headers = [], &$response_headers = [])
{
    return http_post($url, $data, $headers, $response_headers, true);
}
function http_get_url($url, $data = [])
{
    $url_parts = explode('?', $url, 2);

    if(!empty($url_parts[1])) parse_str($url_parts[1], $url_query);

    else $url_query = [];

    $query = array_merge($url_query, $data);

    if(count($query) >= 1)

        return $url_parts[0] . '?' . http_build_query($query);

    else return $url_parts[0];
}
function http_response_status($response_headers, &$status = null)
{
    foreach ($response_headers as $key => $r)

        if(stripos($r, 'HTTP/1.1') === 0 || stripos($r, 'HTTP/1.0') === 0)
        {
            list(,$code, $status) = explode(' ', $r, 3);

            return $code;
        }
    return null;
}
function http_response_headers($response_stream)
{
    $stream_meta_data = stream_get_meta_data($response_stream);

    if($stream_meta_data['wrapper_type'] === 'http' && !empty($stream_meta_data['wrapper_data']))

        return $stream_meta_data['wrapper_data'];

    else return null;
}
function http_response_header($name, $headers)
{
    if(is_resource($headers)) $headers = http_response_headers($headers);

    if(empty($headers)) return false;

    foreach($headers as $header)
    {
        $strpos = strpos(strtolower($header), strtolower($name) . ':');

        if($strpos === 0) return trim(substr($header, mb_strlen($name) + 1));
    }
    return null;
}
function http_context($method, $data = [], $headers = [])
{
    $http = ['method' => $method, 'header' => implode("\r\n", $headers)];

    if(!empty($data)) $http['content'] = $data;

    return stream_context_create(['http' => $http]);
}
function forward_http_get($url, $headers = [], &$response_headers = [])
{
    $stream = http_get_stream($url, $headers, $response_headers);

    forward_http_headers($response_headers);

    fpassthru($stream); exit();
}
function forward_http_post($url, $data = [], $headers = [], &$response_headers = [])
{
    $stream = http_post_stream($url, $data, $headers, $response_headers);

    forward_http_headers($response_headers);

    fpassthru($stream); exit();
}
function forward_http_headers($headers)
{
    foreach($headers as $i => $header)
    {
        $parts = explode(': ', $header, 2);

        if(count($parts) <= 1) continue;

        if(in_array($parts[0], ['Server', 'Date'])) continue;

        header($header);
    }
}
function http_download_headers($name, $mime = null, $size = null)
{
    header('Content-Description: File Transfer');

    if(!empty($mime)) header("Content-Type: " . $mime);

    header('Content-Disposition: attachment; filename="' . $name . '"');

    header('Content-Transfer-Encoding: binary');

    header('Connection: Keep-Alive');

    header('Expires: 0');

    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    header('Pragma: public');

    if(!empty($size)) header("Content-Length: " . $size);
}
function response_cookie($response_headers, $cookie = [], $return_as_string = true)
{
    if(is_string($cookie))
    {
        $cookie = explode("; ", $cookie);

        foreach($cookie as $i => $cookie_)
        {
            unset($cookie[$i]);
            
            $parts = explode('=', $cookie_, 2);

            $cookie[$parts[0]] = $parts[1];
        }
    }
    foreach($response_headers as $response_header)
    
        if(Str::startsWith(mb_strtolower($response_header), 'set-cookie: '))
        {
            $cookie_ = substr($response_header, 12, mb_strpos($response_header, '; ') - 12);

            $parts = explode('=', $cookie_, 2);

            $cookie[$parts[0]] = $parts[1];
        }
    if($return_as_string)
    {
        foreach($cookie as $name => $value)
        {
            unset($cookie[$name]);

            $cookie[] = $name . '=' . $value;
        }
        $cookie = implode("; ", $cookie);
    }
    return $cookie;
}
