<?php
namespace Liquid\Processors\Units\Policies;

use Liquid\Helpers\Condition;
use Liquid\Records\Record;

class CheckValuePolicy implements BasePolicy
{
  protected $condition;

  /**
	 * @return array
	 */
	public static function getFormat()
  {
    return [
      'attribute' => 'name',
      'condition' => 'condition',
      'class' => 'CheckValuePolicy',
    ];
  }

	/**
	 * @param array $config
	 * @return boolean
	 */
	public static function validate(array $config)
  {
    if (!isset($config['attribute'])) return false;
    if (!is_scalar($config['attribute'])) return false;

    if (!isset($config['condition'])) return false;
    if (!is_scalar($config['condition'])) return false;

    return true;
  }

  public function __construct($attribute, $condition)
  {
    $this->condition = Condition::makeEvaluations($attribute, $condition);
  }

	/**
	 * @return closure
	 */
	public function compile()
  {
    $condition = $this->condition;
    return function (Record $record) use ($condition) {
      if ($condition($record->data)) return true;
      else return false;
    }
  }
}
