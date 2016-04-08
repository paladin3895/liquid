<?php
namespace Liquid\Processors\Units\Policies;

use Liquid\Helpers\Condition;
use Liquid\Records\Record;

class CheckInDayRepeat extends BasePolicy
{

  /**
	 * @return array
	 */
	public static function getFormat()
  {
    return [
      'attribute' => 'field',
			'repeat' => 1,
      'name' => 'in_day_repeat',
      'description' => 'Check if an attribute has been repeated in a daytime period',
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

    $config['name'] = isset($config['name']) ? $config['name'] : 'in_day_repeat';

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

        // check if timestamp is between a daytime period
				if ($recent['timestamp'] == date('Y-m-d')) {

          // if repeat count is reached
					if ($recent['count'] >= $repeat) {
            // reset and return true
						$record->setMemory([
							'value' => $currentValue,
							'timestamp' => date('Y-m-d'),
							'count' => 0,
						], $name . '.' . $attribute);
						return true;
					} else /* repeat count is not reached */ {
            // increment count
						$record->setMemory([
							'value' => $currentValue,
							'timestamp' => date('Y-m-d'),
							'count' => ++$recent['count'],
						], $name . '.' . $attribute);
						return false;
					}

				} else /* timestamp is not in current daytime period */ {
					// reset memory
					$record->setMemory([
						'value' => $currentValue,
						'timestamp' => date('Y-m-d'),
						'count' => 0,
					], $name . '.' . $attribute);
					return false;
				}
			}
    };
  }
}
