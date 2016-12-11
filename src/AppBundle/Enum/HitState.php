<?php

namespace AppBundle\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static HitState CAPTURED()
 * @method static HitState MISSED()
 */
class HitState extends Enum
{
    const CAPTURED = 'captured';

    const MISSED = 'missed';
}
