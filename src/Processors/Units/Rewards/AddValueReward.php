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
	 * @return array
	 */
   public static function validate(array $config)
   {
     $result = [];
     if (isset($config['attribute']) && is_scalar($config['attribute'])) {
       $result['attribute'] = $config['attribute'];
     } else {
       throw new \Exception('invalid value for reward config: attribute');
     }

     if (isset($config['value']) && is_scalar($config['value'])) {
       $result['value'] = $config['value'];
     } else {
       throw new \Exception('invalid value for reward config: value');
     }

     return $result;
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
      $data = array_merge($record->getResult(), $record->getData());
      if (!isset($data[$attribute])) $data[$attribute] = 0;
      $old_value = $data[$attribute];
      $new_value = $computation($data);
      $record->setResult([$attribute => $new_value - $old_value]);
      return $record;
    };
  }
}
