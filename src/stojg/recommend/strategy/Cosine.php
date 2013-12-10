<?php

namespace stojg\recommend\strategy;

/**
 * Cosine similarity is a measure of similarity between two vectors of an inner product space that measures the 
 * cosine of the angle between them. The cosine of 0° is 1, and it is less than 1 for any other angle. It is thus a 
 * judgement of orientation and not magnitude: two vectors with the same orientation have a Cosine similarity of 1, 
 * two vectors at 90° have a  similarity of 0, and two vectors diametrically opposed have a similarity of -1, 
 * independent of their magnitude
 *
 */
class Cosine
{

    /**
     * Use if the data is sparse 
     */
    public function run($rating1, $rating2)
    {
        $dotProduct = 0;
        $sqrLenght1 = 0;
        foreach ($rating1 as $item => $rating) {
            if (!isset($rating2[$item])) {
                continue;
            }
            $sqrLenght1 += pow($rating, 2);
            $dotProduct += $rating * $rating2[$item];
        }
        $length1 = sqrt($sqrLenght1);

        $sqrLength2 = 0;
        foreach ($rating2 as $item => $rating) {
            $sqrLength2 += pow($rating, 2);
        }
        $length2 = sqrt($sqrLength2);

        return 1 - abs($dotProduct / ($length1 * $length2));
    }
}
