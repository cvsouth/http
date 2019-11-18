<?php

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
function http_get_stream($url, $data = [], $headers = [], &$response_headers = [], $return_stream = false)
{
    return http_get($url, $data, $headers, &$response_headers, true);
}
function http_get($url, $data = [], $headers = [], &$response_headers = [], $return_stream = false)
{
    $url = http_get_url($url, $data);

    $context = http_context('GET', null, $headers);

    $response = fopen($url, 'r', false, $context);

    $response_headers = http_response_headers($response);

    if($return_stream) return $response;

    else return stream_get_contents($response);
}
function http_post_stream($url, $data = [], $headers = [], &$response_headers = [])
{
    return http_post($url, $data, $headers, &$response_headers, true);
}
function http_post($url, $data = [], $headers = [], &$response_headers = [], $return_stream = false)
{
    $data = http_build_query($data);

    $headers = array_merge(
        [
            'Content-Type: application/x-www-form-urlencoded',

            'Content-Length: ' . strlen($data),
        ],
        $headers);

    $context = http_context('POST', $data, $headers);

    $response = fopen($url, 'r', false, $context);

    $response_headers = http_response_headers($response);

    if($return_stream) return $response;

    else return stream_get_contents($response);
}
function http_response_headers($response_stream)
{
    $stream_meta_data = stream_get_meta_data($response_stream);

    if($stream_meta_data['wrapper_type'] === 'http' && !empty($stream_meta_data['wrapper_data']))

        return $stream_meta_data['wrapper_data'];

    else return null;
}
function http_context($method, $data = [], $headers = [])
{
    $http = ['method'  => $method, 'header'  => implode("\r\n", $headers)];

    if(!empty($data)) $http['content'] = $data;

    return stream_context_create(['http' => $http]);
}
function forward_http_get($url, $data = [], $headers = [], &$response_headers = [])
{
    $stream = http_get_stream($url, $data, $headers, $response_headers);

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

        if(in_array($parts[0],
            [
                'Server',

                'Date',
            ]))
            continue;

        header($header);
    }
}
function http_download_headers($name, $mime, $size)
{
    header('Content-Description: File Transfer');

    header("Content-Type: " . $mime);

    header('Content-Disposition: attachment; filename="' . $name . '"');

    header('Content-Transfer-Encoding: binary');

    header('Connection: Keep-Alive');

    header('Expires: 0');

    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    header('Pragma: public');

    header("Content-Length: " . $size);
}
