<?php

namespace Pointotech\CodeIgniter\Tools;

interface CodeIgniterRoutesOptions
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
