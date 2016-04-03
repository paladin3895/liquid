<?php
namespace Liquid\Processors\Units\Policies;

use Liquid\Helpers\Condition;
use Liquid\Records\Record;

class CheckValueIncrement extends BasePolicy
{

  /**
	 * @return array
	 */
	public static function getFormat()
  {
    return [
      'attribute' => 'field',
      'increment' => 1,
      'name' => 'check_increment',
      'description' => 'Check if an attribute has increased a minimum increment',
      'note' => 'you should name the policy uniquely to prevent name collision',
    ];
  }

	/**
	 * @param array $config
	 * @return array
	 */
	public static function validate(array $config)
  {
    $result = [];

    $config['name'] = isset($config['name']) ? $config['name'] : 'check_increment';

    if (isset($config['attribute']) && is_string($config['attribute'])) {
      $result['attribute'] = $config['attribute'];
    } else {
      throw new \Exception("invalid value for policy {$config['name']} config: attribute");
    }

    if (isset($config['increment']) && is_numeric($config['increment'])) {
      $result['increment'] = $config['increment'];
    } else {
      throw new \Exception("invalid value for policy {$config['name']} config: increment");
    }

    $result['name'] = $config['name'];

    return $result;
  }

  public function __construct($attribute, $increment, $name)
  {
    $this->attribute = $attribute;
    $this->increment = $increment;
    $this->name = $name;
  }

	/**
	 * @return closure
	 */
	public function compile()
  {
    $attribute = $this->attribute;
    $increment = $this->increment;
    $name = $this->name;
    return function (Record $record) use ($attribute, $increment, $name) {
      $current = $record->getData($attribute);
      $recent = $record->getMemory($name . '.' . $attribute);

      if ((int)$current > (int)$recent) {
        $record->setMemory($current, $name . '.' . $attribute);
        return true;
      }

      return false;
    };
  }
}
