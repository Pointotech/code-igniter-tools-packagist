# pointotech/code-igniter-tools

Development tools for CodeIgniter.

## Installation

```bash
composer require pointotech/code-igniter-tools
```

## Usage

This code should be called from within a CodeIgniter controller, since it
depends on data and functions defined by CodeIgniter.

```php
use Pointotech\CodeIgniter\Tools\CodeIgniterRoutesFinder;

echo "# Routes\n\n";
foreach (CodeIgniterRoutesFinder::all_routes() as $route_url => $controller_method) {
  echo "- " . $route_url . ': ' . (is_string($controller_method) ? $controller_method : json_encode($controller_method)) . "\n";
}

echo "\n## Routing options\n\n";
$routes_options = CodeIgniterRoutesFinder::routing_options();
echo "- default_controller_url: " . $routes_options->default_controller_url() . "\n";
echo "- translate_uri_dashes: " . ($routes_options->translate_uri_dashes() ? "Yes" : "No") . "\n";
```

## Example output

```
# Routes

- routes/index: Routes::index
- welcome/index: Welcome::index
- 404_override:

## Routing options

- default_controller_url: welcome
- translate_uri_dashes: No
```
