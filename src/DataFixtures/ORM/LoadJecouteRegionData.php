<?php

namespace App\DataFixtures\ORM;

use App\Entity\Geo\Region as GeoRegion;
use App\Entity\Jecoute\Region;
use App\Jecoute\RegionColorEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class LoadJecouteRegionData extends Fixture implements DependentFixtureInterface
{
    public const REGION_1_UUID = '88275043-adb5-463a-8a62-5248fe7aacbf';
    public const REGION_2_UUID = 'c91391e9-4a08-4d14-8960-6c3508c1dddc';
    public const REGION_3_UUID = '62c6bf4c-72c9-4a29-bd5e-bf27b8ee2228';

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createRegion(
            self::REGION_1_UUID,
            $this->getReference('geo_region_28'),
            'Bienvenue en Normandie',
            'Description de la normandie',
            RegionColorEnum::RED,
            'region-logo.jpg',
            'region-banner.jpg',
            'https://en-marche.fr'
        ));

        $manager->persist($this->createRegion(
            self::REGION_2_UUID,
            $this->getReference('geo_region_32'),
            'Bienvenue en Hauts-de-France',
            'Description des Hauts-de-France',
            RegionColorEnum::GREEN,
            'region-logo.jpg',
            'region-banner.jpg',
            'https://en-marche.fr'
        ));

        $manager->persist($this->createRegion(
            self::REGION_3_UUID,
            $this->getReference('geo_region_93'),
            'Bienvenue en PACA',
            'Description PACA',
            RegionColorEnum::BLUE,
            'region-logo.jpg'
        ));

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LoadGeoData::class,
        ];
    }

    private function createRegion(
        string $uuid,
        GeoRegion $region,
        string $subtitle,
        string $description,
        string $primaryColor,
        string $logo,
        string $banner = null,
        string $externalLink = null
    ): Region {
        return new Region(
            Uuid::fromString($uuid),
            $region,
            $subtitle,
            $description,
            $primaryColor,
            $logo,
            $banner,
            $externalLink
        );
    }
}
