<?php
namespace Liquid\Interfaces;

use Liquid\Processors\BaseProcessor;
use Liquid\Interfaces\ConfigurableInterface;

interface ProcessUnitInterface extends ConfigurableInterface
{
	/**
	 * @return closure
	 */
	public function compile();
}
