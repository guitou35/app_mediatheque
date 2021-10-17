<?php

namespace App\services;

use App\Entity\Personne;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckReservationDateService
{

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function updateReservation()
    {
        $reservations = $this->reservationRepository->findReservationAttente();

        if (count($reservations) > 0 ){
            foreach ($reservations as $reservation){
                $reservation->setStatut('done');
                $reservation->getLivre()->setStatut('dispo');
            }
            $this->entityManager->flush();
        }
    }

    public function countReservationRetardByOne(Personne $personne)
    {
        $session = $this->requestStack->getSession();
        $countReservations = count($this->reservationRepository->findEmpruntRetardPersonnee($personne));
        $session->set('countReservationsRetard',$countReservations);
    }

    public function countReservationRetard()
    {
        $session = $this->requestStack->getSession();
        $countReservations = count($this->reservationRepository->findEmpruntRetard(new \DateTime('now')));
        $session->set('countReservationsRetard',$countReservations);
    }
}