<?php

namespace App\Controller\Api\Event;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use App\Entity\Event\BaseEvent;
use App\Repository\EventRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GetEventParticipantsController extends AbstractController
{
    public function __invoke(
        Request $request,
        BaseEvent $event,
        EventRegistrationRepository $eventRegistrationRepository
    ): PaginatorInterface {
        return $eventRegistrationRepository->findPaginatedByEvent(
            $event,
            $request->query->getInt('page', 1),
            $request->query->getInt('page_size', 30)
        );
    }
}
