<?php

namespace stojg\datamine\tests;

use stojg\datamine\strategy\Paerson;

class PaersonTest extends \PHPUnit_Framework_TestCase {
	
	protected $set;
	
	function setUp() {
		$data = file_get_contents(__DIR__. '/fixtures/users.json');
		$this->set = json_decode($data, true);
	}
	
	function testPaersonAngelicaBill() {
		$paerson = new Paerson();
		$score = $paerson->run($this->set['Angelica'], $this->set['Bill']);
		$this->assertEquals(0.09594650093173, $score);
	}
	
	function testPaersonAngelicaHaily() {
		$paerson = new Paerson();
		$score = $paerson->run($this->set['Angelica'], $this->set['Hailey']);
		$this->assertEquals(0.5799159747916, $score);
	}
	
	function testPaersonAngelicaJordyn() {
		$paerson = new Paerson();
		$score = $paerson->run($this->set['Angelica'], $this->set['Jordyn']);
		$this->assertEquals(0.23602513945246, $score);
	}
	
	function testPaersonNoMatch() {
		$paerson = new Paerson();
		$score = $paerson->run($this->set['Angelica'], $this->set['Bosse']);
		$this->assertEquals(0, $score);
	}

}
