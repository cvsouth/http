# HTTP



## Installation

```bash
composer require cvsouth/http
```

## Usage

### Basic Requests

#### GET

```php
$response = http_get($url);
```
#### POST

```php
$response = http_get($url, ['key' => 'value']);
```

### Stream Response

#### GET

```php
$stream = http_get_stream($url);

$response = stream_get_contents($stream); // etc
```

#### POST

```php
$stream = http_post_stream($url, ['key' => 'value']);

$response = stream_get_contents($stream); // etc
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
