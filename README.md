# pointotech/code-igniter-tools

Development tools for CodeIgniter.

## Installation

```bash
composer require pointotech/code-igniter-tools
```

## Usage

```php
use Pointotech\CodeIgniter\Tools\CodeIgniterRoutesFinder;

echo "Routes:\n" . json_encode(CodeIgniterRoutesFinder::find(), JSON_PRETTY_PRINT) . "\n";
```
