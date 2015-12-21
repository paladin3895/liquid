<?php
namespace Liquid\Processors\Units\Rewards;

use Liquid\Processors\Units\ProcessUnitInterface;

abstract class BaseReward implements ProcessUnitInterface
{
  /**
	 * @return array
	 */
	abstract public static function getFormat();

	/**
	 * @param array $config
	 * @return boolean
	 */
	abstract public static function validate(array $config);

	/**
	 * @return closure
	 */
	abstract public function compile();
}
