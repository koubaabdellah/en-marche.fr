<?php

namespace App\ChezVous;

use App\Entity\ChezVous\MeasureType;
use Symfony\Contracts\EventDispatcher\Event;

class MeasureTypeEvent extends Event
{
    private $measureType;

    public function __construct(MeasureType $measureType)
    {
        $this->measureType = $measureType;
    }

    public function getMeasureType(): MeasureType
    {
        return $this->measureType;
    }
}
