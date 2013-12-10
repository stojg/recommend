<?php

namespace stojg\datamine\strategy;

/**
 * Computes the Minkowski distance
 * 
 * If the data is dense (allmost all attributes have a non
 * zero value) and the magnitude of the attributes values
 * are important, this is a good similarity comparisator
 *
 */
class Minkowski
{

    /**
     *
     * @var int
     */
    protected $r = 1;

    /**
     * 
     * @param int $r
     */
    public function __construct($r = 1)
    {
        $this->r = $r;
    }

    /**
     * 
     * Both rating1 and rating2 are an array of the form
     * ['The Strokes'=> 3.0, 'Slightly Stoopid' =>  2.5, ...]
     * 
     * @param array $rating1
     * @param array $rating2
     */
    public function run($rating1, $rating2)
    {
        $distance = 0;
        $commonRatings = false;
        foreach ($rating1 as $key => $rating) {
            if (isset($rating2[$key])) {
                $distance += pow(abs($rating1[$key] - $rating2[$key]), $this->r);
                $commonRatings = true;
            }
        }
        if ($commonRatings) {
            return pow($distance, 1 / $this->r);
        }
        return 0;
    }
}
