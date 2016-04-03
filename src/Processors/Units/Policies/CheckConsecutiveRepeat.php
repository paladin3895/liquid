<?php
namespace Liquid\Processors\Units\Policies;

use Liquid\Helpers\Condition;
use Liquid\Records\Record;

class CheckConsecutiveRepeat extends BasePolicy
{

  /**
	 * @return array
	 */
	public static function getFormat()
  {
    return [
      'attribute' => 'field',
			'repeat' => 1,
      'name' => 'consecutive_repeat',
      'description' => 'Check if an attribute has been repeated in consecutive days',
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

    $config['name'] = isset($config['name']) ? $config['name'] : 'check_consecutive';

    if (isset($config['attribute']) && is_string($config['attribute'])) {
      $result['attribute'] = $config['attribute'];
    } else {
      throw new \Exception("invalid value for policy {$config['name']} config: attribute");
    }

		if (isset($config['repeat']) && is_numeric($config['repeat'])) {
      $result['repeat'] = $config['repeat'];
    } else {
      throw new \Exception("invalid value for policy {$config['name']} config: repeat");
    }

    $result['name'] = $config['name'];

    return $result;
  }

  protected $attribute;

  protected $repeat;

  protected $name;

  public function __construct($attribute, $repeat, $name)
  {
    $this->attribute = $attribute;
    $this->repeat = $repeat;
    $this->name = $name;
  }

	/**
	 * @return closure
	 */
	public function compile()
  {
    $attribute = $this->attribute;
    $repeat = $this->repeat;
    $name = $this->name;

    return function (Record $record) use ($attribute, $repeat, $name) {
      $currentValue = $record->getData($attribute);
      $recent = (array)$record->getMemory($name . '.' . $attribute);

			// first time and the record memory has not been initilized
			if (!$recent) {

				$record->setMemory([
					'value' => $currentValue,
					'timestamp' => date('Y-m-d'),
					'count' => 1,
				], $name . '.' . $attribute);
				return false;

			} else /* memory has been initialized */ {

				// only process further if value incrementing
				if (($currentValue <= $recent['value'])) return false;
				if ($recent['timestamp'] == date('Y-m-d', strtotime('-1 day'))) {
					if ($recent['count'] >= $repeat) {
						$record->setMemory([
							'value' => $currentValue,
							'timestamp' => date('Y-m-d'),
							'count' => 0,
						], $name . '.' . $attribute);
						return true;
					} else {
						$record->setMemory([
							'value' => $currentValue,
							'timestamp' => date('Y-m-d'),
							'count' => ++$recent['count'],
						], $name . '.' . $attribute);
						return false;
					}
				} elseif ($recent['timestamp'] < date('Y-m-d', strtotime('-1 day'))) {
					// reset memory
					$record->setMemory([
						'value' => $currentValue,
						'timestamp' => date('Y-m-d'),
						'count' => 1,
					], $name . '.' . $attribute);
					return false;
				} else /* attribute updated in the same day */ {
					$record->setMemory([
						'value' => $currentValue,
						'timestamp' => $recent['timestamp'],
						'count' => $recent['count'],
					], $name . '.' . $attribute);
					return false;
				}

			}
    };
  }
}
