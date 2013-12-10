<?php

namespace stojg\recommend\tests;

use stojg\recommend\strategy\Paerson;

class PaersonTest extends \PHPUnit_Framework_TestCase
{

    protected $set;

    public function setUp()
    {
        $data = file_get_contents(__DIR__ . '/../fixtures/users.json');
        $this->set = json_decode($data, true);
    }

    public function testPaersonAngelicaBill()
    {
        $paerson = new Paerson();
        $score = $paerson->run($this->set['Angelica'], $this->set['Bill']);
        $this->assertEquals(0.09594650093173, $score);
    }

    public function testPaersonAngelicaHaily()
    {
        $paerson = new Paerson();
        $score = $paerson->run($this->set['Angelica'], $this->set['Hailey']);
        $this->assertEquals(0.5799159747916, $score);
    }

    public function testPaersonAngelicaJordyn()
    {
        $paerson = new Paerson();
        $score = $paerson->run($this->set['Angelica'], $this->set['Jordyn']);
        $this->assertEquals(0.23602513945246, $score);
    }

    public function testPaersonNoMatch()
    {
        $paerson = new Paerson();
        $score = $paerson->run($this->set['Angelica'], $this->set['Bosse']);
        $this->assertEquals(0, $score);
    }
}
