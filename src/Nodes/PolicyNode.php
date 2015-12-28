<?php
namespace Liquid\Nodes;

use Liquid\Nodes\BaseNode;
use Liquid\Nodes\States\PassiveState;
use Liquid\Messages\MessageInterface;

class PolicyNode extends BaseNode
{
  public function process()
	{
		if ($this->status & self::STATUS_ACTIVE) {
			$record = call_user_func($this->state->compileProcess()->bindTo($this), $this->collection);
      if ($record) {
        $record->toHistory($this);
        if ($record->status) call_user_func($this->state->compilePush()->bindTo($this), $record);
      }
		} else {
			throw new \Exception('node ' . $this->name . ' doesnt have a processor');
		}
	}

  public function handle(MessageInterface $message)
  {
    call_user_func($this->state->compileHandle()->bindTo($this), $message);
    call_user_func($this->state->compileBroadcast()->bindTo($this), $message);
  }
}
