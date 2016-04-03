<?php
namespace Tests;

use Liquid\Builders\PolicyBuilder;
use Liquid\Processors\Units\Policies\CheckValueIncrement;
use Liquid\Processors\Units\Policies\CheckConsecutiveRepeat;

use Liquid\Records\Record;

class PolicyUnitTest extends TestCase
{
    public function testCheckValueIncrement()
    {
        $config = CheckValueIncrement::getFormat();
        $config['attribute'] = 'point';
        $config['increment'] = 1;

        $this->assertEquals([
            'attribute' => 'point',
            'increment' => 1,
            'name' => 'check_increment',
        ], CheckValueIncrement::validate($config));

        $policy = new CheckValueIncrement($config['attribute'], $config['increment'], $config['name']);

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 0], [], ['check_increment.point' => 1])
        );
        $this->assertFalse($result);
        $this->assertEquals(1, $record->getMemory('check_increment.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 1], [], ['check_increment.point' => 1])
        );
        $this->assertFalse($result);
        $this->assertEquals(1, $record->getMemory('check_increment.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 2], [], ['check_increment.point' => 1])
        );
        $this->assertTrue($result);
        $this->assertEquals(2, $record->getMemory('check_increment.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 3], [], ['check_increment.point' => 1])
        );
        $this->assertTrue($result);
        $this->assertEquals(3, $record->getMemory('check_increment.point'));
    }

    public function testCheckConsecutiveRepeat()
    {
        $config = CheckConsecutiveRepeat::getFormat();
        $config['attribute'] = 'point';
        $config['repeat'] = 2;

        $this->assertEquals([
            'attribute' => 'point',
            'repeat' => 2,
            'name' => 'consecutive_repeat'
        ], CheckConsecutiveRepeat::validate($config));

        $policy = new CheckConsecutiveRepeat($config['attribute'], $config['repeat'], $config['name']);

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 1], [], [])
        );
        $this->assertFalse($result);
        $this->assertEquals([
            'value' => 1,
            'timestamp' => date('Y-m-d'),
            'count' => 1,
        ], $record->getMemory('consecutive_repeat.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 2], [], [
                'consecutive_repeat.point' => [
                    'value' => 1,
                    'timestamp' => date('Y-m-d'),
                    'count' => 2,
                ]
            ])
        );
        $this->assertFalse($result);
        $this->assertEquals([
            'value' => 2,
            'timestamp' => date('Y-m-d'),
            'count' => 2,
        ], $record->getMemory('consecutive_repeat.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 2], [], [
                'consecutive_repeat.point' => [
                    'value' => 1,
                    'timestamp' => date('Y-m-d', strtotime('-30 hours')),
                    'count' => 0,
                ]
            ])
        );
        $this->assertFalse($result);
        $this->assertEquals([
            'value' => 2,
            'timestamp' => date('Y-m-d'),
            'count' => 1,
        ], $record->getMemory('consecutive_repeat.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 2], [], [
                'consecutive_repeat.point' => [
                    'value' => 1,
                    'timestamp' => date('Y-m-d', strtotime('-30 hours')),
                    'count' => 1,
                ]
            ])
        );
        $this->assertFalse($result);
        $this->assertEquals([
            'value' => 2,
            'timestamp' => date('Y-m-d'),
            'count' => 2,
        ], $record->getMemory('consecutive_repeat.point'));

        $result = call_user_func(
            $policy->compile(),
            $record = new Record(['point' => 2], [], [
                'consecutive_repeat.point' => [
                    'value' => 1,
                    'timestamp' => date('Y-m-d', strtotime('-30 hours')),
                    'count' => 2,
                ]
            ])
        );
        $this->assertTrue($result);
        $this->assertEquals([
            'value' => 2,
            'timestamp' => date('Y-m-d'),
            'count' => 0,
        ], $record->getMemory('consecutive_repeat.point'));
    }
}
