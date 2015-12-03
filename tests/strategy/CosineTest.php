<?php

namespace stojg\recommend\tests;

use stojg\recommend\strategy\Cosine;

class CosineTest extends \PHPUnit_Framework_TestCase
{
    public function testCosinSimilarity()
    {
        $cosin = new Cosine();
        $users = json_decode(file_get_contents(__DIR__.'/../fixtures/perfect_users.json'), true);
        $score = $cosin->run($users['Clara'], $users['Robert']);
        $this->assertEquals(0.064846541429478, $score);
    }
}
