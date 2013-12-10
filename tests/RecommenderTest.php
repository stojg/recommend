<?php

namespace stojg\datamine\tests;

use stojg\datamine\Recommender;
use stojg\datamine\strategy\Manhattan;
use stojg\datamine\strategy\Paerson;
use stojg\datamine\strategy\Cosine;

class RecommenderTest extends \PHPUnit_Framework_TestCase
{

    protected $set;

    public function setUp()
    {
        $data = file_get_contents(__DIR__ . '/fixtures/users.json');
        $this->set = json_decode($data, true);
    }

    public function testRecommendManhattanHailey()
    {
        $base = new Recommender('Hailey', $this->set);
        $recommendations = $base->recommend(new Manhattan());
        $this->assertEquals(3, count($recommendations));
        $this->assertEquals('Phoenix', $recommendations[0]['key']);
        $this->assertEquals(4.0, $recommendations[0]['value']);
        $this->assertEquals('Blues Traveler', $recommendations[1]['key']);
        $this->assertEquals(3.0, $recommendations[1]['value']);
        $this->assertEquals('Slightly Stoopid', $recommendations[2]['key']);
        $this->assertEquals(2.5, $recommendations[2]['value']);
    }

    public function testRecommendManhattanChan()
    {
        $base = new Recommender('Chan', $this->set);
        $recommendations = $base->recommend(new Manhattan());
        $this->assertEquals(2, count($recommendations));
        $this->assertEquals('The Strokes', $recommendations[0]['key']);
        $this->assertEquals(5.0, $recommendations[0]['value']);
    }

    public function testRecommendManhattanSam()
    {
        $base = new Recommender('Sam', $this->set);
        $recommendations = $base->recommend(new Manhattan());
        $this->assertEquals(1, count($recommendations));
        $this->assertEquals('Deadmau5', $recommendations[0]['key']);
        $this->assertEquals(1.0, $recommendations[0]['value']);
    }

    public function testRecommendManhattanAngelica()
    {
        $base = new Recommender('Angelica', $this->set);
        $recommendations = $base->recommend(new Manhattan());
        $this->assertEquals(0, count($recommendations));
    }

    public function testRecommendPaersonHailey()
    {
        $base = new Recommender('Hailey', $this->set);
        $recommendations = $base->recommend(new Paerson());
        $this->assertEquals(3, count($recommendations));
        $this->assertEquals('Blues Traveler', $recommendations[0]['key']);
        $this->assertEquals(5.0, $recommendations[0]['value']);
        $this->assertEquals('Phoenix', $recommendations[1]['key']);
        $this->assertEquals(5.0, $recommendations[1]['value']);
        $this->assertEquals('Slightly Stoopid', $recommendations[2]['key']);
        $this->assertEquals(4.0, $recommendations[2]['value']);
    }
    
    public function testRecommendPaersonNoMatch()
    {
        $set = json_decode(file_get_contents(__DIR__ . '/fixtures/users_nomatch.json'), true);
        $base = new Recommender('Andrea', $set);
        $recommendations = $base->recommend(new Paerson());
        $this->assertEquals(0, count($recommendations));
    }

    public function testRecommendCosineHailey()
    {
        $base = new Recommender('Hailey', $this->set);
        $recommendations = $base->recommend(new Cosine());
        $this->assertEquals(2, count($recommendations));
        $this->assertEquals('Phoenix', $recommendations[0]['key']);
        $this->assertEquals(5.0, $recommendations[0]['value']);
        $this->assertEquals('Slightly Stoopid', $recommendations[1]['key']);
        $this->assertEquals(4.5, $recommendations[1]['value']);
    }
    
    public function testFromReadme()
    {
        $artistRatings = array(
            "Abe" => array(
                "Blues Traveler" => 3,
                "Broken Bells" => 2,
                "Norah Jones" => 4,
                "Phoenix" => 5,
                "Slightly Stoopid" => 1,
                "The Strokes" => 2,
                "Vampire Weekend" => 2
            ),
            "Blair" => array(
                "Blues Traveler" => 2,
                "Broken Bells" => 3,
                "Deadmau5" => 4,
                "Phoenix" => 2,
                "Slightly Stoopid" => 3,
                "Vampire Weekend" => 3
            ),
            "Clair" => array(
                "Blues Traveler" => 5,
                "Broken Bells" => 1,
                "Deadmau5" => 1,
                "Norah Jones" => 3,
                "Phoenix" => 5,
                "Slightly Stoopid" => 1
            )
        );
        
        $recommender = new \stojg\datamine\Recommender('Blair', $artistRatings);
        $recommendations = $recommender->recommend(new \stojg\datamine\strategy\Manhattan());
        var_export($recommendations);
        
    }
}
