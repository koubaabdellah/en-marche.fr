<?php

namespace App\Adherent\Authorization;

use App\Entity\Geo\Zone;
use App\Scope\ScopeEnum;

final class ZoneBasedRoleTypeEnum
{
    public const ALL = [
        ScopeEnum::CORRESPONDENT,
        ScopeEnum::LEGISLATIVE_CANDIDATE,
        ScopeEnum::DEPUTY,
    ];

    public const ZONE_TYPES = [
        ScopeEnum::CORRESPONDENT => [
            Zone::DEPARTMENT,
        ],
        ScopeEnum::LEGISLATIVE_CANDIDATE => [
            Zone::DISTRICT,
            Zone::FOREIGN_DISTRICT,
        ],
        ScopeEnum::DEPUTY => [
            Zone::DISTRICT,
            Zone::FOREIGN_DISTRICT,
        ],
    ];
}
