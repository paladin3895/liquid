<?php
namespace Liquid\Nodes;

use Liquid\Nodes\BaseNode;
use Liquid\Nodes\States\PassiveState;

class PolicyNode extends BaseNode
{
  public function process()
	{
		if ($this->status & self::STATUS_ACTIVE) {
			$record = call_user_func($this->state->compileProcess()->bindTo($this), $this->collection);
      if ($record->status) {
		    call_user_func($this->state->compilePush()->bindTo($this), $record);
      } else {
        foreach ($this->nexts as $node) {
          $node->change(new PassiveState);
        }
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
