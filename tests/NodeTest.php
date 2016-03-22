<?php
namespace Tests;

use Liquid\Nodes\PolicyNode;
use Liquid\Processors\BaseProcessor;
use Liquid\Processors\PolicyProcessor;
use Liquid\Nodes\States\ActiveState;
use Liquid\Registry;
use Liquid\Records\Record;
use Liquid\Records\Collection;

use Liquid\Processors\Units\Policies\BasePolicy;
use Liquid\Processors\Units\Rewards\BaseReward;

class NodeTest extends TestCase
{
    public function testNodeWithRegistry()
    {
        $registry = new Registry;
        $nodes = [];
        $nodes[1] = new PolicyNode;
        $nodes[2] = new PolicyNode;
        $nodes[3] = new PolicyNode;
        $nodes[4] = new PolicyNode;
        $nodes[5] = new PolicyNode;

        $nodes[1]->bind(new DummyProcessor);
        $nodes[2]->bind(new DummyProcessor);
        $nodes[3]->bind(new DummyProcessor);
        $nodes[4]->bind(new DummyProcessor);
        $nodes[5]->bind(new DummyProcessor);

        $nodes[1]->forward($nodes[2]);
        $nodes[1]->forward($nodes[3]);
        $nodes[4]->forward($nodes[1]);
        $nodes[5]->forward($nodes[3]);
        $nodes[2]->forward($nodes[5]);

        $this->assertEquals($nodes[1]->getDepth(), 1);
        $this->assertEquals($nodes[2]->getDepth(), 2);
        $this->assertEquals($nodes[3]->getDepth(), 4);
        $this->assertEquals($nodes[4]->getDepth(), 0);
        $this->assertEquals($nodes[5]->getDepth(), 3);

        foreach ($nodes as $node) {
            $node->register($registry);
        }

        $registry->initialize();
        foreach ($nodes as $node) {
            $this->assertTrue($registry->getDepth($node->getDepth())->contains($node));
        }

        $registry->process(new Record(['name' => 'David Pham']));
        $this->assertTrue(array_key_exists($nodes[1]->getName(), Record::history('checkpoint')));
        $this->assertTrue(array_key_exists($nodes[2]->getName(), Record::history('checkpoint')));
        $this->assertTrue(array_key_exists($nodes[3]->getName(), Record::history('checkpoint')));
        $this->assertTrue(array_key_exists($nodes[4]->getName(), Record::history('checkpoint')));
        $this->assertTrue(array_key_exists($nodes[5]->getName(), Record::history('checkpoint')));
    }

    public function testNodeWithDummyProcessor()
    {
        $nodes[1] = new PolicyNode;
        $nodes[2] = new PolicyNode;
        $nodes[3] = new PolicyNode;
        $nodes[4] = new PolicyNode;

        $nodes[1]->forward($nodes[2]);
        $nodes[1]->change(new ActiveState);
        $nodes[2]->forward($nodes[3]);
        $nodes[3]->forward($nodes[4]);

        $nodes[1]->bind(new DummyProcessor);
        $nodes[2]->bind(new DummyProcessor);
        $nodes[3]->bind(new DummyProcessor(false));
        $nodes[4]->bind(new DummyProcessor);

        $nodes[1]->setInput(new Record(['name' => 'David Pham']));
        $nodes[1]->process();
        $nodes[2]->process();
        $nodes[3]->process();
        $nodes[4]->process();

        $this->assertTrue(array_key_exists($nodes[1]->getName(), Record::history('checkpoint')));
        $this->assertTrue(Record::history('checkpoint')[$nodes[1]->getName()]['status']);

        $this->assertTrue(array_key_exists($nodes[2]->getName(), Record::history('checkpoint')));
        $this->assertTrue(Record::history('checkpoint')[$nodes[2]->getName()]['status']);

        $this->assertTrue(array_key_exists($nodes[3]->getName(), Record::history('checkpoint')));
        $this->assertFalse(Record::history('checkpoint')[$nodes[3]->getName()]['status']);

        $this->assertFalse(array_key_exists($nodes[4]->getName(), Record::history('checkpoint')));
    }
}

class DummyProcessor extends BaseProcessor
{
    protected $status;

    public function __construct($status = true)
    {
        parent::__construct();
        $this->status = $status;
    }

  	public function process(Collection $collection)
  	{
        $record = $collection->merge();
        $record->setStatus($this->status);
    		return $record;
  	}
}
