<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Livre::class);
        $this->paginator = $paginator;
    }

    /**
     * @param SearchData $data
     * @return PaginationInterface
     */
    public function searchLivre(SearchData $data) : PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('l')
            ->select('l','g','a')
            ->join('l.genre', 'g')
            ->join('l.auteur','a');

        if(!empty($data->q)){
            $query = $query
                ->andWhere('l.titre LIKE :q')
                ->setParameter('q',"%{$data->q}%");
        }

        if($data->status){
            $query = $query
                ->andWhere('l.statut = :status')
                ->setParameter('status','dispo');
        }

        if(!empty($data->genres)){
            $query= $query
                ->andWhere('g.id IN (:genres)')
                ->setParameter('genres',$data->genres);
        }
        $query = $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $data->page,
            8
        );
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
