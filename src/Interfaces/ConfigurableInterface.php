<?php
namespace Liquid\Interfaces;

interface ConfigurableInterface
{
  /**
   * @return array
   */
  public static function getFormat();

  /**
   * @param array $config
   * @return array
   * @throws Exception
   */
  public static function validate(array $config);
}
