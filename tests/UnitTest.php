<?php
namespace Tests;

use Liquid\Builders\PolicyBuilder;
use Liquid\Processors\Units\Policies\CheckValuePolicy;
use Liquid\Processors\Units\Rewards\AddValueReward;

use Liquid\Records\Record;
use Liquid\Nodes\PolicyNode;
use Liquid\Nodes\States\ActiveState;

class UnitTest extends TestCase
{
    public function testCheckValuePolicy()
    {
        $config = CheckValuePolicy::getFormat();
        $config['attribute'] = 'name';
        $config['condition'] = 'regex:#Come\-Stay#';

        $policy = new CheckValuePolicy($config['attribute'], $config['condition']);

        $result = call_user_func($policy->compile(), new Record(['name' => 'Come-Stay']));
        $this->assertTrue($result);

        $result = call_user_func($policy->compile(), new Record(['name' => 'David Pham']));
        $this->assertFalse($result);
    }

    public function testAddValueReward()
    {
        $config = AddValueReward::getFormat();
        $config['attribute'] = 'point';
        $config['value'] = '${factor} * ${point} + 10';

        $policy = new AddValueReward($config['attribute'], $config['value']);

        $result = call_user_func($policy->compile(), new Record(['factor' => 3], ['point' => 10]));
        $this->assertEquals(['point' => 30], $result->getResult());

        $result = call_user_func($policy->compile(), new Record([], ['name' => 'David Pham']));
        $this->assertEquals(['point' => 10], $result->getResult());
    }

    public function testPolicyBuilder()
    {
        $policy_config = [
            'class' => 'Liquid\Processors\Units\Policies\CheckValuePolicy',
            'attribute' => 'name',
            'condition' => 'regex:#Come\-Stay#',
        ];

        $reward_config = [
            'class' => 'Liquid\Processors\Units\Rewards\AddValueReward',
            'attribute' => 'point',
            'value' => '${factor} * ${point} + 10',
        ];

        $processor_config = (object)[
            'class' => 'Liquid\Processors\CycleProcessor',
            'number' => 3,
        ];

        $builder = new PolicyBuilder;
        $processor = $builder->make([
            'id' => 1,
            'policies' => [$policy_config],
            'rewards' => [$reward_config],
            'config' => $processor_config
        ]);

        $record = new Record([
            'name' => 'Come-Stay',
            'factor' => 2,
        ], [
            'point' => 5
        ]);

        $node = new PolicyNode;
        $node->bind($processor);
        $node->change(new ActiveState);
        $node->setInput($record);

        $node->process();
        $this->assertEquals(['point' => 15], Record::history()['result']);
    }
}
