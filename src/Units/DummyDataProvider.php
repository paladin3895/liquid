<?php

namespace Liquid\Units;

class DummyDataProvider extends BaseUnit
{

	protected $data;

	public function __construct(array $data, $name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('unit_');
		$this->data = $data;
	}

	public function process(array $record)
  {
    return $this->data;
  }

	public static function getFormat()
	{
		return [
			'data' => 'array',
			'name' => 'string',
		];
	}
}
