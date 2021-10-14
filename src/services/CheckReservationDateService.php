<?php

namespace App\services;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager)
    {
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
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

}