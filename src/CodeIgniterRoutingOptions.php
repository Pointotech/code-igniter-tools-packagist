<?php

namespace Pointotech\CodeIgniter\Tools;

interface CodeIgniterRoutingOptions
{
  /**
   * @return string
   */
  function default_controller_url();

  /**
   * @return boolean
   */
  function translate_uri_dashes();
}
