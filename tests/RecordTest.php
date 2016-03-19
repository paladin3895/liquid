<?php
namespace Tests;

use Liquid\Nodes\PolicyNode;
use Liquid\Records\Record;
use Liquid\Records\Collection;

class RecordTest extends TestCase
{
    public function testCreateRecord()
    {
        $data = [
            'name' => 'David Pham',
            'career' => 'Developer',
        ];

        $record = new Record($data);
        $this->assertEquals($record->getData(), $data);
    }

    public function testRecordHistory()
    {
        $data = [
            'name' => 'David Pham',
            'career' => 'Developer',
        ];

        $result = [
            'point' => 10,
            'level' => 'gold',
        ];

        $memory = [
            'last_login' => '1990/01/01'
        ];

        $record_1 = new Record($data, $result, $memory);
        $record_1->setStatus(true);

        $node_1 = new PolicyNode('testing_node_1');

        $record_1->toHistory($node_1);
        // file_put_contents('./liquid.log', json_encode($record_1->getHistory())); exit;
        $this->assertEquals(
            $record_1->getHistory(),
            [
              'result' => ['point' => 10, 'level' => 'gold'],
              'checkpoint' => [
                  'testing_node_1' => [
                      'status' => true,
                      'memory' => ['last_login' => '1990/01/01'],
                      'result' => ['point' => 10, 'level' => 'gold'],
                        ]
                    ]
                ]
        );

        $result = [
            'point' => 100,
            'level' => 'silver',
        ];

        $memory = [
            'login_count' => '4'
        ];

        $record_2 = new Record($data, $result, $memory);
        $record_2->setStatus(false);

        $node_2 = new PolicyNode('testing_node_2');

        $record_2->toHistory($node_2);
        // file_put_contents('./liquid.log', json_encode($record_2->getHistory())); exit;
        $this->assertEquals(
            $record_1->getHistory(),
            [
              'result' => ['point' => 110, 'level' => 'silver'],
              'checkpoint' => [
                  'testing_node_1' => [
                      'status' => true,
                      'memory' => ['last_login' => '1990/01/01'],
                      'result' => ['point' => 10, 'level' => 'gold'],
                    ],
                  'testing_node_2' => [
                      'status' => false,
                      'memory' => ['login_count' => '4'],
                      'result' => ['point' => 100, 'level' => 'silver'],
                    ],
                ],
            ]
        );

        $record_3 = new Record;
        $record_3->fromHistory($node_1);
        $this->assertEquals($record_3->getStatus(), true);
        $this->assertEquals($record_3->getMemory(), ['last_login' => '1990/01/01']);

        $record_4 = new Record;
        $record_4->fromHistory($node_2);
        $this->assertEquals($record_4->getStatus(), false);
        $this->assertEquals($record_4->getMemory(), ['login_count' => 4]);
    }

    public function testCollection()
    {
        $collection = new Collection;
        $collection->push(new Record([
            'name' => 'David Pham',
        ], [
            'point' => 10,
            'rewards' => ['gold'],
        ]));
        $collection->push(new Record([
            'name' => 'David Pham',
        ], [
            'point' => 20,
            'more' => 'thing',
            'rewards' => ['silver'],
        ]));
        $record = $collection->merge();
        $this->assertEquals($record->getData(), ['name' => 'David Pham']);
        $this->assertEquals($record->getResult(), [
            'point' => 30,
            'more' => 'thing',
            'rewards' => ['gold', 'silver'],
        ]);
    }
}
