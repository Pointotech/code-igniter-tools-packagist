<?php

namespace Pointotech\CodeIgniter\Tools;

class CodeIgniterRoutesFinder
{
  public static function find()
  {
    $result = [];
    foreach (self::get_all_controller_routes() as $controller_name => $controller_routes) {
      foreach ($controller_routes as $controller_route) {
        $url = strtolower($controller_name) . self::slash . $controller_route;
        $method_mapped_to_url = $controller_name . self::double_colon . $controller_route;
        $result[$url] = $method_mapped_to_url;
      }
    }

    $configuration_routes = self::get_configuration_routes();

    return array_merge($result, $configuration_routes);
  }

  /**
   * @return CodeIgniterRoutesOptions
   */
  public static function routes_options()
  {
    $ci = &get_instance();
    return new CodeIgniterRoutesOptionsImpl($ci->router);
  }

  private static function get_configuration_routes()
  {
    $ci = &get_instance();

    return json_decode(json_encode($ci->router->routes));
  }

  private static function get_all_controller_routes()
  {
    $result = [];

    $controllers_directory_items = glob(APPPATH . self::controllers_directory_name . self::slash . self::asterisk);

    foreach ($controllers_directory_items as $controllers_directory_item) {

      if (is_dir($controllers_directory_item)) {
        $result = self::add_directory_routes_to_result($result, $controllers_directory_item);
      } else if (self::is_php_file($controllers_directory_item)) {
        $result = self::add_controller_routes_to_result($result, $controllers_directory_item);
      }
    }

    return $result;
  }

  private static function add_controller_routes_to_result(array $result, string $controller_file_path)
  {
    $controller_file_path = $controller_file_path;
    $controller_name = self::get_controller_name_from_file_path($controller_file_path);

    $result[$controller_name] = self::get_controller_routes($controller_name, $controller_file_path);

    return $result;
  }

  private static function add_directory_routes_to_result(array $result, string $directory_path)
  {
    $controller_file_paths = glob(
      $directory_path . self::slash . self::asterisk
    );

    foreach ($controller_file_paths as $controller_file_path) {
      if (is_dir($controller_file_path)) {
        $result = self::add_directory_routes_to_result($result, $controller_file_path);
      } elseif (self::is_php_file($controller_file_path)) {
        $result = self::add_controller_routes_to_result($result, $controller_file_path);
      }
    }

    return $result;
  }

  private static function is_php_file(string $file_path)
  {
    return pathinfo($file_path, PATHINFO_EXTENSION) === self::php_file_extension;
  }

  private static function get_controller_name_from_file_path(string $controller_file_path)
  {
    return basename($controller_file_path, self::dot_php_file_extension);
  }

  private static function get_controller_routes(string $controller_name, string $controller_file_path)
  {
    $ci = &get_instance();

    if (!class_exists($controller_name)) {
      $ci->load->file($controller_file_path);
    }

    $controller_methods = get_class_methods($controller_name);

    $result = [];

    foreach ($controller_methods as $controller_method) {
      if (self::is_public_controller_route($controller_name, $controller_method)) {
        $result[] = $controller_method;
      }
    }

    return $result;
  }

  private static function is_public_controller_route(string $controllerName, string $methodName)
  {
    return $methodName !== self::constructor_method_name
      && $methodName !== self::code_igniter_singleton_method_name
      && $methodName !== $controllerName
      && self::is_public_method($controllerName, $methodName);
  }

  private static function is_public_method(string $className, string $methodName)
  {
    return is_callable([$className, $methodName]);
  }

  private const dot_php_file_extension = '.php';

  private const php_file_extension = 'php';

  private const controllers_directory_name = 'controllers';

  private const slash = '/';

  private const asterisk = '*';

  /**
   * Also known as `PAAMAYIM_NEKUDOTAYIM` by the PHP parser.
   * 
   * See also: 
   * - http://phpsadness.com/sad/1
   * - https://en.wikipedia.org/wiki/Scope_resolution_operator
   */
  private const double_colon = '::';

  private const constructor_method_name = '__construct';

  private const code_igniter_singleton_method_name = 'get_instance';
}
