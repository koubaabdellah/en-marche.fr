<?php

namespace App\DataFixtures\ORM;

use App\Assessor\AssessorRequestElectionRoundsEnum;
use App\Assessor\AssessorRequestFactory;
use App\Entity\AssessorOfficeEnum;
use App\Entity\VotePlace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class LoadAssessorRequestData extends Fixture implements DependentFixtureInterface
{
    private const ASSESSOR_REQUEST_1_UUID = 'be61ba07-c8e7-4533-97e1-0ab215cd752c';
    private const ASSESSOR_REQUEST_2_UUID = '91b8d3a3-6757-4cf6-becc-5e3786bbbf5a';
    private const ASSESSOR_REQUEST_3_UUID = 'dac2c85f-4cff-4a09-a015-20168cfbe27c';
    private const ASSESSOR_REQUEST_4_UUID = 'a2087904-b05d-4bd6-b155-b3e486a25adf';
    private const ASSESSOR_REQUEST_5_UUID = '9e2e1fe6-9ff9-4b04-8fe7-1620c9df0e45';
    private const ASSESSOR_REQUEST_6_UUID = 'd320b698-10b7-4dd7-a70a-cedb95fceeda';
    private const ASSESSOR_REQUEST_7_UUID = 'f9286607-c3b5-4531-be03-81c3fb4fafe8';
    private const ASSESSOR_REQUEST_8_UUID = '64b8b8ca-0708-4fcc-a3ce-844ff2e3852d';
    private const ASSESSOR_REQUEST_9_UUID = '884d16de-30f7-45c4-bffb-e93df9357279';

    public function load(ObjectManager $manager)
    {
        /** @var VotePlace $votePlaceLilleWazemmes */
        $votePlaceLilleWazemmes = $this->getReference('vote-place-lille-wazemmes');

        /** @var VotePlace $votePlaceLilleJeanZay */
        $votePlaceLilleJeanZay = $this->getReference('vote-place-lille-jean-zay');

        /** @var VotePlace $votePlaceBobigny */
        $votePlaceBobigny = $this->getReference('vote-place-bobigny-blanqui');

        $unmatchedRequest1 = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_1_UUID),
            'gender' => 'female',
            'lastName' => 'Kepoura',
            'firstName' => 'Adrienne',
            'birthdate' => '14-05-1973',
            'birthCity' => 'Lille',
            'address' => '4 avenue du peuple Belge',
            'postalCode' => '59000',
            'city' => 'Lille',
            'voteCity' => 'Lille',
            'officeNumber' => '59350_0108',
            'emailAddress' => 'adrienne.kepoura@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00001',
            'assessorCity' => 'Lille',
            'assessorPostalCode' => '59350',
            'office' => AssessorOfficeEnum::SUBSTITUTE,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
            'reachable' => true,
        ]);

        $unmatchedRequest1->addVotePlaceWish($votePlaceLilleWazemmes);
        $unmatchedRequest1->addVotePlaceWish($votePlaceLilleJeanZay);

        $matchedRequest1 = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_2_UUID),
            'gender' => 'male',
            'lastName' => 'Hytté',
            'firstName' => 'Prosper',
            'birthdate' => '10-07-1989',
            'birthCity' => 'Paris',
            'address' => '72 Rue du Faubourg Saint-Martin',
            'postalCode' => '93008',
            'city' => 'Paris',
            'voteCity' => 'Bobigny',
            'officeNumber' => '93008_0005',
            'emailAddress' => 'prosper.hytte@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00002',
            'assessorCity' => 'Bobigny',
            'assessorPostalCode' => '93008',
            'office' => AssessorOfficeEnum::SUBSTITUTE,
            'electionRounds' => [AssessorRequestElectionRoundsEnum::FIRST_ROUND],
        ]);

        $matchedRequest1->addVotePlaceWish($votePlaceBobigny);
        $matchedRequest1->process($votePlaceBobigny);

        $matchedRequest2 = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_3_UUID),
            'gender' => 'male',
            'lastName' => 'Luc',
            'firstName' => 'Ratif',
            'birthdate' => '04-02-1992',
            'birthCity' => 'Paris',
            'address' => '70 Rue Saint-Martin',
            'postalCode' => '93008',
            'city' => 'Paris',
            'voteCity' => 'Bobigny',
            'officeNumber' => '93008_0005',
            'emailAddress' => 'luc.ratif@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00003',
            'assessorCity' => 'Bobigny',
            'assessorPostalCode' => '93008',
            'office' => AssessorOfficeEnum::HOLDER,
            'electionRounds' => [AssessorRequestElectionRoundsEnum::SECOND_ROUND],
        ]);

        $matchedRequest2->addVotePlaceWish($votePlaceBobigny);
        $matchedRequest2->process($votePlaceBobigny);

        $matchedRequest3 = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_4_UUID),
            'gender' => 'female',
            'lastName' => 'Coptère',
            'firstName' => 'Elise',
            'birthdate' => '14-01-1986',
            'birthCity' => 'Lille',
            'address' => 'Pl. du Théâtre',
            'postalCode' => '59000',
            'city' => 'Lille',
            'voteCity' => 'Lille',
            'officeNumber' => '59350_0108',
            'emailAddress' => 'elise.coptere@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00004',
            'assessorCity' => 'Lille',
            'assessorPostalCode' => '59000',
            'office' => AssessorOfficeEnum::HOLDER,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
        ]);

        $matchedRequest3->addVotePlaceWish($votePlaceLilleWazemmes);
        $matchedRequest3->process($votePlaceLilleWazemmes);

        $request5 = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_5_UUID),
            'gender' => 'male',
            'lastName' => 'Sahalor',
            'firstName' => 'Aubin',
            'birthdate' => '12-08-1986',
            'birthCity' => 'Lille',
            'address' => ' Pl. du Théâtre',
            'postalCode' => '59100',
            'city' => 'Lille',
            'voteCity' => 'Lille',
            'officeNumber' => '59350_0108',
            'emailAddress' => 'aubin.sahalor@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00005',
            'assessorCity' => 'Lille',
            'assessorPostalCode' => '59100',
            'office' => AssessorOfficeEnum::SUBSTITUTE,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
        ]);

        $requestOutOfManagedArea = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_6_UUID),
            'gender' => 'male',
            'lastName' => 'Parbal',
            'firstName' => 'Gilles',
            'birthdate' => '12-08-1986',
            'birthCity' => 'Angers',
            'address' => '4 rue Saint-Nicolas',
            'postalCode' => '49000',
            'city' => 'Angers',
            'voteCity' => 'Angers',
            'officeNumber' => '49000_0108',
            'emailAddress' => 'gilles.parbal@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00006',
            'assessorCity' => 'Angers',
            'assessorPostalCode' => '49000',
            'office' => AssessorOfficeEnum::HOLDER,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
        ]);

        $foreignRequestDisabled = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_7_UUID),
            'gender' => 'male',
            'lastName' => 'Cochet',
            'firstName' => 'Henri',
            'birthdate' => '12-10-1980',
            'birthCity' => 'London',
            'address' => '4 cover garden',
            'postalCode' => null,
            'city' => 'London',
            'voteCity' => 'London',
            'officeNumber' => '99999_0108',
            'emailAddress' => 'henri.cochet@example.fr',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00007',
            'assessorCity' => 'London',
            'assessorPostalCode' => null,
            'office' => AssessorOfficeEnum::HOLDER,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
            'enabled' => false,
            'assessorCountry' => 'GB',
        ]);

        $foreignRequestOutOfManagedArea = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_8_UUID),
            'gender' => 'male',
            'lastName' => 'Luigi',
            'firstName' => 'Leonardo',
            'birthdate' => '12-11-1972',
            'birthCity' => 'Italie',
            'address' => '4 piazza della pasta',
            'postalCode' => null,
            'city' => 'Italie',
            'voteCity' => 'Italie',
            'officeNumber' => '99999_0253',
            'emailAddress' => 'luigi.leonardo@example.it',
            'phoneNumber' => '+33612345678',
            'voterNumber' => '00008',
            'assessorCity' => 'Rome',
            'assessorPostalCode' => null,
            'office' => AssessorOfficeEnum::HOLDER,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
            'enabled' => true,
            'assessorCountry' => 'IT',
        ]);

        $matchedRequest4 = AssessorRequestFactory::createFromArray([
            'uuid' => Uuid::fromString(self::ASSESSOR_REQUEST_9_UUID),
            'gender' => 'male',
            'lastName' => 'Pélisson',
            'firstName' => 'Philippe',
            'birthdate' => '29-03-1985',
            'birthCity' => 'Lille',
            'address' => 'Pl. du Théâtre',
            'postalCode' => '59000',
            'city' => 'Lille',
            'voteCity' => 'Lille',
            'officeNumber' => '59350_0108',
            'emailAddress' => 'philippe.pelisson@example.fr',
            'phoneNumber' => '+33612345679',
            'voterNumber' => '00015',
            'assessorCity' => 'Lille',
            'assessorPostalCode' => '59000',
            'office' => AssessorOfficeEnum::SUBSTITUTE,
            'electionRounds' => AssessorRequestElectionRoundsEnum::ALL,
        ]);

        $matchedRequest4->addVotePlaceWish($votePlaceLilleWazemmes);
        $matchedRequest4->process($votePlaceLilleWazemmes);

        $manager->persist($unmatchedRequest1);
        $manager->persist($matchedRequest1);
        $manager->persist($matchedRequest2);
        $manager->persist($matchedRequest3);
        $manager->persist($matchedRequest4);
        $manager->persist($request5);
        $manager->persist($requestOutOfManagedArea);
        $manager->persist($foreignRequestDisabled);
        $manager->persist($foreignRequestOutOfManagedArea);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LoadElectionVotePlaceData::class,
        ];
    }
}
