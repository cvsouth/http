# HTTP

This package provides HTTP requests in pure PHP, without any additional dependencies such as ext-curl or over-engineered solutions such as Guzzle.

## Installation

```bash
composer require cvsouth/http
```

## Usage

### Basic Requests

#### GET

The package makes basic HTTP request ultra simple:

```php
$response = http_get($url);
```
#### POST

For POST data you can either pass raw string data or an associative array to be encoded as form data:

```php
$response = http_post($url, 'data');
// or
$response = http_post($url, ['key' => 'value']);
```

### Stream Response

#### GET

```php
$stream = http_get_stream($url);

// do something with the stream...
```

#### POST

```php
$stream = http_post_stream($url, ['key' => 'value']);

// do something with the stream...
```

### Specifying Additional Headers

You can specify additional request headers by passing them as an associative array as the next parameter:

#### GET

```php
$response = http_get($url, ['Pragma' => 'no-cache']);
```

```php
$stream = http_get_stream($url, ['Pragma' => 'no-cache']);
```
#### POST

```php
$response = http_post($url, ['key' => 'value'], ['Pragma' => 'no-cache']);
```

```php
$stream = http_post_stream($url, ['key' => 'value'], ['Pragma' => 'no-cache']);
```

### Reading Response Headers

You can access the response headers through a reference parameter as key value pairs in an associative array:

#### GET

```php
$response = http_get($url, null, $response_headers);

print_r($response_headers);
```

```php
$stream = http_get_stream($url, null, $response_headers);

print_r($response_headers);

// do something with the stream...
```

#### POST

```php
$response = http_post($url, ['key' => 'value'], null, $response_headers);

print_r($response_headers);
```

```php
$stream = http_post_stream($url, ['key' => 'value'], null, $response_headers);

print_r($response_headers);

// do something with the stream...
```

#### Accessing Specific Headers

You can also access specific response headers using `http_response_header`. The first parameter takes the header name (case insensitive). The second parameter is the array of headers or you can pass a stream:

```php
$response = http_get($url, null, $response_headers);

$content_type = http_response_header('Content-Type', $response_headers);
```

```php
$response = http_get_stream($url);

$content_type = http_response_header('Content-Type', $stream);

// do something with stream...
```

### Response Download

As well as using this package to read from HTTP endpoints, we also have some helper functions for returning our own response. `http_download_headers` can be used to set the headers on your response to force a download in the user's browser. The function takes three parameters; $name, $mime and $size:

```php
$file_path = '...';
$file = fopen($file_path, 'r');

$file_name = basename($file_path);
$file_mime = mime_content_type($file_path);
$file_size = filesize($file_path);

http_download_headers($file_name, $file_mime, $file_size);

fpassthru($file);
```

### Response Proxy

You can also use this package to easily pass on a response from elsewhere to your own response, allowing you to proxy external resources exactly. This function will use an underlying stream to forward on the request efficiently:

#### GET

```php
forward_http_get($url);
```

#### POST

```php
forward_http_post($url, ['key' => 'value']);
```

### Handling Exceptions

If any exceptions occur during HTTP request a HttpException will be thrown with of either sub-type RequestException or ResponseException. RequestException means there was something wrong with your request and you may get this if the url you are requesting does not exist. ResponseException denotes that a status other than 200 was received and may of type `InformationalResponseException`, `RedirectionResponseException`, `ClientErrorResponseException`, `ServerErrorResponseException`.

The RequestException object has the methods `getMethod()`, `getRequestData()` and `getRequestHeaders()`. The ResponseException object also has these methods and in addition it has `getResponseStatusCode()` and `getResponseHeaders()`. It is up to you to decide how specific you want your error catching to be:

```php
try
{
  $response = http_get($bad_url);
}
catch(HttpException $e)
{
  // if anything goes wrong...
}
```
```php
try
{
  $response = http_get($url);
}
catch(RequestException $e)
{
  // handle request exception...
}
catch(ResponseException $e)
{
  // handle response exception...
}
```

# Notes

To use this package `allow_url_fopen` in `php.ini` must be set to `on`, which it is by default.
