<?php
namespace Tests;

use Liquid\Records\Record;
use Liquid\Nodes\PolicyNode;
use Liquid\Nodes\States\ActiveState;
use Liquid\Processors\PolicyProcessor;
use Liquid\Processors\CycleProcessor;

use Liquid\Processors\Units\Policies\BasePolicy;
use Liquid\Processors\Units\Rewards\BaseReward;

class ProcessorTest extends TestCase
{
    public function testPolicyProcessorSuccess()
    {
        $node = new PolicyNode('testing_node');

        $processor = new PolicyProcessor;
        $processor->registerPolicy(new DummyPolicy(true));
        $processor->registerReward(new DummyReward(['point' => 10]));

        $node->bind($processor);
        $node->change(new ActiveState);
        $node->setInput(new Record(['name' => 'David Pham']));
        $node->process();

        $this->assertEquals(['point' => 10], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => true,
                'memory' => [],
                'result' => ['point' => 10]
            ]
        ], Record::history()['checkpoint']);

        // check node process second time, result must remain unchanged
        $node->setInput(new Record(['name' => 'Come-Stay']));
        $node->process();
        $this->assertEquals(['point' => 10], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => true,
                'memory' => [],
                'result' => []
            ]
        ], Record::history()['checkpoint']);
    }

    public function testPolicyProcessorFailed()
    {
        $node = new PolicyNode('testing_node');

        $processor = new PolicyProcessor;
        $processor->registerPolicy(new DummyPolicy(false));
        $processor->registerReward(new DummyReward(['point' => 10]));

        $node->bind($processor);
        $node->change(new ActiveState);
        $node->setInput(new Record(['name' => 'David Pham']));
        $node->process();

        $this->assertEquals([], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => false,
                'memory' => [],
                'result' => []
            ]
        ], Record::history()['checkpoint']);
    }

    public function testCycleProcessorPass()
    {
        $node = new PolicyNode('testing_node');

        $processor = new CycleProcessor(2);
        $processor->registerPolicy(new DummyPolicy(true));
        $processor->registerReward(new DummyReward(['point' => 10]));

        $node->bind($processor);
        $node->change(new ActiveState);
        $node->setInput(new Record(['name' => 'David Pham']));
        $node->process();

        $this->assertEquals(['point' => 10], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => true,
                'memory' => ['_number' => 1],
                'result' => ['point' => 10]
            ]
        ], Record::history()['checkpoint']);

        // check node process second time
        $node->setInput(new Record(['name' => 'Come-Stay']));
        $node->process();
        $this->assertEquals(['point' => 20], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => true,
                'memory' => ['_number' => 2],
                'result' => ['point' => 10]
            ]
        ], Record::history()['checkpoint']);

        // check node process third time, result must remain unchanged
        $node->setInput(new Record(['name' => 'Come-Stay']));
        $node->process();
        $this->assertEquals(['point' => 20], Record::history()['result']);
    }

    public function testCycleProcessorFail()
    {
        $node = new PolicyNode('testing_node');

        $processor = new CycleProcessor(2);
        $processor->registerPolicy(new DummyPolicy([true, false, true]));
        $processor->registerReward(new DummyReward(['point' => 10]));

        $node->bind($processor);
        $node->change(new ActiveState);
        $node->setInput(new Record(['name' => 'David Pham']));
        $node->process();

        $this->assertEquals(['point' => 10], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => true,
                'memory' => ['_number' => 1],
                'result' => ['point' => 10]
            ]
        ], Record::history()['checkpoint']);

        // check node process second time, this time will result in failed
        $node->setInput(new Record(['name' => 'Come-Stay']));
        $node->process();
        $this->assertEquals(['point' => 10], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => false,
                'memory' => ['_number' => 1],
                'result' => []
            ]
        ], Record::history()['checkpoint']);

        // check node process third time, result must remain unchanged
        $node->setInput(new Record(['name' => 'Come-Stay']));
        $node->process();
        $this->assertEquals(['point' => 20], Record::history()['result']);
        $this->assertEquals([
            'testing_node' => [
                'status' => true,
                'memory' => ['_number' => 2],
                'result' => ['point' => 10]
            ]
        ], Record::history()['checkpoint']);
    }
}

class DummyPolicy extends BasePolicy
{
    protected $result;

    public static function getFormat() {

    }

    public static function validate(array $config)
    {

    }

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function compile()
    {
        if (is_array($this->result)) {
            $result = each($this->result)['value'];
        } else {
            $result = $this->result;
        }
        return function (Record $record) use ($result) {
            return (boolean)$result;
        };
    }
}

class DummyReward extends BaseReward
{
    protected $result;

    public static function getFormat() {

    }

    public static function validate(array $config)
    {

    }

    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function compile()
    {
        $result = $this->result;
        return function (Record $record) use ($result) {
            $record->setResult($result);
            return $record;
        };
    }
}
