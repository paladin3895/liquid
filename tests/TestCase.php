<?php
namespace Tests;

use Liquid\Records\Record;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        Record::forget();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
