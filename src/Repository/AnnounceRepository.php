<?php

namespace App\Repository;

use App\Entity\Announce;
use App\Entity\Garage;
use App\Entity\Professional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Query\Expr\Join as ExprJoin;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Announce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announce[]    findAll()
 * @method Announce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnounceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announce::class);
    }

    /**
     * @return Announce[] Returns an array of Announce objects
     */

    public function findByProId($id)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin(
                Garage::class,
                'g',
                'WITH',
                'g.id = a.garage'
            )
            ->innerJoin(
                Professional::class,
                'p',
                'WITH',
                'g.professional = p.id'
            )
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Announce[] Returns an array of Announce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Announce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
