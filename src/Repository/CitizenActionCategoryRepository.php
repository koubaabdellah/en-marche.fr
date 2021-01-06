<?php

namespace App\Repository;

use App\Entity\CitizenActionCategory;
use Doctrine\Persistence\ManagerRegistry;

class CitizenActionCategoryRepository extends BaseEventCategoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CitizenActionCategory::class);
    }
}
