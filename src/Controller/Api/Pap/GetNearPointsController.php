<?php

namespace App\Controller\Api\Pap;

use App\Repository\Pap\AddressRepository;
use App\Repository\Pap\VotePlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GetNearPointsController extends AbstractController
{
    private const MAX_LIMIT = 300;

    /**
     * @Route("/v3/pap/address/near", name="api_pap_get_near_addresses", methods={"GET"})
     */
    public function getAddressAction(Request $request, AddressRepository $addressRepository): Response
    {
        if (
            !$request->query->has('latitude')
            || !$request->query->has('longitude')
            || !$request->query->has('zoom')
        ) {
            throw new BadRequestHttpException('Some required parameters are missing. (latitude, longitude, zoom)');
        }

        $limit = $request->query->getInt('limit', self::MAX_LIMIT);

        return $this->json($addressRepository->findNear(
            $request->query->filter('latitude', null, \FILTER_VALIDATE_FLOAT),
            $request->query->filter('longitude', null, \FILTER_VALIDATE_FLOAT),
            $request->query->getInt('zoom'),
            $limit > self::MAX_LIMIT ? self::MAX_LIMIT : $limit
        ), Response::HTTP_OK, [], ['groups' => ['pap_address_list']]);
    }

    /**
     * @Route("/v3/pap/vote-places/near", name="api_pap_get_near_vote_places", methods={"GET"})
     */
    public function getVotePlaceAction(Request $request, VotePlaceRepository $votePlaceRepository): Response
    {
        if (!$request->query->has('latitude') || !$request->query->has('longitude')) {
            throw new BadRequestHttpException('Some required parameters are missing. (latitude, longitude)');
        }

        $limit = $request->query->getInt('limit', self::MAX_LIMIT);

        return $this->json($votePlaceRepository->findNear(
            $request->query->filter('latitude', null, \FILTER_VALIDATE_FLOAT),
            $request->query->filter('longitude', null, \FILTER_VALIDATE_FLOAT),
            $limit > self::MAX_LIMIT ? self::MAX_LIMIT : $limit
        ));
    }
}
