<?php
namespace Liquid\Processors\Units\Policies;

use Liquid\Helpers\Condition;
use Liquid\Records\Record;

class CheckValuePolicy extends BasePolicy
{
  protected $condition;

  /**
	 * @return array
	 */
	public static function getFormat()
  {
    return [
      'attribute' => 'field',
      'condition' => 'condition',
      'name' => 'check_value',
      'description' => 'check value of an attribute based on a predefined rule',
    ];
  }

	/**
	 * @param array $config
	 * @return array
	 */
	public static function validate(array $config)
  {
    $result = [];

    $config['name'] = isset($config['name']) ? $config['name'] : 'check_policy';

    if (isset($config['attribute']) && is_scalar($config['attribute'])) {
      $result['attribute'] = $config['attribute'];
    } else {
      throw new \Exception("invalid value for policy {$config['name']} config: attribute");
    }

    if (isset($config['condition']) && is_scalar($config['condition'])) {
      $result['condition'] = $config['condition'];
    } else {
      throw new \Exception("invalid value for policy {$config['name']} config: condition");
    }

    return $result;
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
      return $condition($record->getData());
    };
  }
}
