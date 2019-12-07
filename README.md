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
// or
$stream = http_get_stream($url, ['Pragma' => 'no-cache']);
```
#### POST

```php
$response = http_get($url, ['key' => 'value'], ['Pragma' => 'no-cache']);
// or
$stream = http_get_stream($url, ['key' => 'value'], ['Pragma' => 'no-cache']);
```

### Reading Response Headers



### Response Download



### Response Proxy



### Handling Exceptions
