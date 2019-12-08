# HTTP



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

Using streams you can access the response headers before doing anything with the stream:

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

### Response Download



### Response Proxy



### Handling Exceptions
