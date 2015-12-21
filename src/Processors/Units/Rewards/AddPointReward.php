<?php
namespace Liquid\Processors\Units\Rewards;

use Liquid\Helpers\Expression;
use Liquid\Records\Record;

class AddPointReward implements BaseReward
{
  protected $attribute;
  protected $computation;

  /**
	 * @return array
	 */
	public static function getFormat()
  {
    return [
      'attribute' => 'name',
      'point' => 0,
      'class' => 'AddPointReward'
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

    if (!isset($config['point'])) return false;
    if (!is_numeric($config['point'])) return false;

    return true;
  }

  public function __construct($attribute, $point)
  {
    $this->attribute = $attribute;
    $this->computation = Expression::makeExpression("{$attribute} + {$point}");
  }

	/**
	 * @return closure
	 */
	public function compile()
  {
    $attribute = $this->attribute;
    $computation = $this->computation;
    return function (Record $record) use ($attribute, $computation) {
      if (!isset($record->data[$attribute])) $record->data[$attribute] = 0;
      if (!is_numeric($record->data[$attribute]))
        throw new \Exception('invalid attribute data type');
      $record->data[$attribute] = $computation($record->data);
      return $record;
    }
  }
}
