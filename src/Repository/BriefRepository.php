<?php

namespace App\Repository;

use App\Entity\Brief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brief|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brief|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brief[]    findAll()
 * @method Brief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brief::class);
    }

    // /**
    //  * @return Brief[] Returns an array of Brief objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function fetchBriefsInPromo(int $id)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.promoBriefs',"p")
            ->innerJoin("p.promo",'pp')
            ->andWhere('pp.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function fetchTeacherBriefs(int $id,string $status)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.briefStatus=:status')
            ->setParameter('status',$status)
            ->innerJoin('b.creator','c')
            ->andWhere('c.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function findBriefInAPromo($brief,$promo)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.promoBriefs','pb')
            ->andWhere('pb.brief=:brief')
            ->setParameter('brief',$brief)
            ->andWhere('pb.promo=:promo')
            ->setParameter('promo',$promo)
            ->getQuery()
            ->getOneOrNullResult();

    }

    /*
    public function findOneBySomeField($value): ?Brief
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
