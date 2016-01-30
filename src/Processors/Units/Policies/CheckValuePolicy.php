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
      'attribute' => 'name',
      'condition' => 'condition',
    ];
  }

	/**
	 * @param array $config
	 * @return array
	 */
	public static function validate(array $config)
  {
    $result = [];
    if (isset($config['attribute']) && is_scalar($config['attribute'])) {
      $result['attribute'] = $config['attribute'];
    } else {
      throw new \Exception('invalid value for policy config: attribute');
    }

    if (isset($config['condition']) && is_scalar($config['condition'])) {
      $result['condition'] = $config['condition'];
    } else {
      throw new \Exception('invalid value for policy config: condition');
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
      if ($condition($record->data)) return true;
      else return false;
    };
  }
}
