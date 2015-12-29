<?php
namespace Liquid\Processors\Units\Rewards;

use Liquid\Helpers\Expression;
use Liquid\Records\Record;

class AddValueReward extends BaseReward
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
      'value' => 'expression',
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

    if (!isset($config['value'])) return false;
    if (!is_scalar($config['value'])) return false;

    return true;
  }

  public function __construct($attribute, $value)
  {
    $this->attribute = $attribute;
    $this->computation = Expression::makeExpression($value);
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
      $old_value = $record->data[$attribute];
      $record->data[$attribute] = $computation($record->data);
      $record->result[$attribute] = $record->data[$attribute] - $old_value;
      return $record;
    };
  }
}
