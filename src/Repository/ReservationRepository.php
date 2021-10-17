<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findReservations($id)
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('r','l','p')
            ->join('r.livre', 'l')
            ->join('r.personne','p')
            ->andWhere('p.id = :id');
        $query->setParameter('id',$id);

        return $query->getQuery()->getResult();
    }

    public function findEmprunt()
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('r','l','p')
            ->join('r.livre', 'l')
            ->join('r.personne','p')
            ->andWhere("r.statut = 'en-cours' ")
            ->orderBy('r.DateRetour','DESC');

        return $query->getQuery()->getResult();
    }
    public function findEmpruntRetard(\DateTime $dateTime)
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('r','l','p')
            ->join('r.livre', 'l')
            ->join('r.personne','p')
            ->andWhere("r.statut = 'en-cours' ")
            ->andWhere("r.DateRetour < :date")
            ->setParameter('date', $dateTime);

        return $query->getQuery()->getResult();
    }

    public function findReservationAttente()
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('r','l','p')
            ->join('r.livre', 'l')
            ->join('r.personne','p')
            ->andWhere("r.statut = 'attente' ")
            ->andWhere('DATE_DIFF(CURRENT_DATE(), r.DateRetour) < 3')
        ;
             //$query= $this->_em->createQuery('select r, l,DATE_DIFF(CURRENT_DATE(), r.DateRetour) as jour from d ')

        return $query->getQuery()->getResult();
    }

    public function findEmpruntRetardPersonnee(Personne $personne)
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('r','l','p')
            ->join('r.livre', 'l')
            ->join('r.personne','p')
            ->andWhere("r.statut = 'en-cours' ")
            ->andWhere("r.DateRetour < CURRENT_DATE()")
            ->andWhere('p.id = :id')
            ->setParameter('id', $personne->getId());

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
