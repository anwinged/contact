<?php

namespace AppBundle\Value;

use AppBundle\Document\Catcher;
use AppBundle\Document\Hit;

class ProcessingPair
{
    /**
     * @var Hit
     */
    private $hit;

    /**
     * @var Catcher
     */
    private $catcher;

    /**
     * @param Hit     $hit
     * @param Catcher $catcher
     */
    public function __construct(Hit $hit, Catcher $catcher)
    {
        $this->hit = $hit;
        $this->catcher = $catcher;
    }

    /**
     * @return Hit
     */
    public function getHit(): Hit
    {
        return $this->hit;
    }

    /**
     * @return Catcher
     */
    public function getCatcher(): Catcher
    {
        return $this->catcher;
    }
}
