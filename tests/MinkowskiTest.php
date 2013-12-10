<?php

namespace stojg\datamine\tests;

use stojg\datamine\strategy\Minkowski;

class MinkowskiTest extends \PHPUnit_Framework_TestCase {
	
	protected $set;
	
	function setUp() {
		$data = file_get_contents(__DIR__. '/fixtures/users.json');
		$this->set = json_decode($data, true);
	}
	
	function testMinkowskiAngelicaHailey() {
		$paerson = new Minkowski(2);
		$score = $paerson->run($this->set['Angelica'], $this->set['Hailey']);
		$this->assertEquals(2.7386127875258, $score);
	}
}
