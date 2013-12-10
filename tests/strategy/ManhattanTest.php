<?php

namespace stojg\recommend\tests;

use stojg\recommend\strategy\Manhattan;

class ManhattanTest extends \PHPUnit_Framework_TestCase
{

    protected $set;

    public function setUp()
    {
        $data = file_get_contents(__DIR__ . '/../fixtures/users.json');
        $this->set = json_decode($data, true);
    }

    public function testManhattanAngelicaBill()
    {
        $manhattan = new Manhattan();
        $score = $manhattan->run($this->set['Angelica'], $this->set['Bill']);
        $this->assertEquals(9.0, $score);
    }
}
