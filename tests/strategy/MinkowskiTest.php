<?php

namespace stojg\datamine\tests;

use stojg\datamine\strategy\Minkowski;

class MinkowskiTest extends \PHPUnit_Framework_TestCase
{

    protected $set;

    public function setUp()
    {
        $data = file_get_contents(__DIR__ . '/../fixtures/users.json');
        $this->set = json_decode($data, true);
    }

    public function testMinkowskiAngelicaHailey()
    {
        $paerson = new Minkowski(2);
        $score = $paerson->run($this->set['Angelica'], $this->set['Hailey']);
        $this->assertEquals(2.7386127875258, $score);
    }
    
    public function testMinkowskiNoMatch()
    {
        $set = json_decode(file_get_contents(__DIR__ . '/../fixtures/users_nomatch.json'), true);
        $paerson = new Minkowski(2);
        $score = $paerson->run($set['Andrea'], $set['Bob']);
        $this->assertFalse($score);
    }
}
