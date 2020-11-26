<?php

namespace Pointotech\CodeIgniter\Tools;

class CodeIgniterRoutingOptionsImpl implements CodeIgniterRoutingOptions
{
  function default_controller_url()
  {
    return $this->default_controller_url;
  }

  private $default_controller_url;

  function translate_uri_dashes()
  {
    return $this->translate_uri_dashes;
  }

  private $translate_uri_dashes;

  function __construct(object $code_igniter_router)
  {
    $this->default_controller_url = $code_igniter_router->default_controller;
    $this->translate_uri_dashes = $code_igniter_router->translate_uri_dashes;
  }
}
