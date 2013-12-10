<?php

namespace stojg\recommend\strategy;

/**
 * Computes the Manhattan distance. 
 *
 */
class Manhattan extends Minkowski
{

    /**
     * Overrides the parent Minkowski to set the r-dimension to 1
     */
    public function __construct()
    {
        parent::__construct(1);
    }
}
