<?php

namespace App\Repository;

use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    // /**
    //  * @return Groupe[] Returns an array of Groupe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findGrpeInPromo($idPro,$type)
    {
        return $this->createQueryBuilder('g')
                ->innerJoin('g.promo','promo')
                ->andWhere('promo.id=:idPro')
                ->setParameter('idPro',$idPro)
                ->andWhere('g.type=:type')
                ->setParameter('type',$type)
                ->getQuery()
                ->getResult();
    }

    public function findWaitingStudents($status)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.apprenants','a')
            ->andWhere('a.isWaiting=:status')
            ->setParameter('status',$status)
            ->getQuery()
            ->getResult();
    }

    public function findWaitingStudentsInPromo($id,$status)
    {
        return $this->createQueryBuilder("g")
                ->innerJoin('g.promo','p')
                ->andWhere('p.id=:id')
                ->setParameter('id',$id)
                ->innerJoin('g.apprenants',"a")
                ->andWhere('a.isWaiting=:status')
                ->setParameter('status',$status)
                ->getQuery()
                ->getResult();
    }

    public function findStudentInPromoGrpe($idPromo,$idGrpe)
    {
        return $this->createQueryBuilder('g')
                ->andWhere('g.id=:idGrpe')
                ->setParameter('idGrpe',$idGrpe)
                ->innerJoin('g.promo','p')
                ->andWhere('p.id=:idPromo')
                ->setParameter('idPromo',$idPromo)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function fetchGroupesInPromo($idPromo)
    {
        return $this->createQueryBuilder('g')
                ->innerJoin('g.promo','p')
                ->andWhere('p.id=:idPromo')
                ->setParameter('idPromo',$idPromo)
                ->getQuery()
                ->getResult();
    }
    
    /*
    public function findOneBySomeField($value): ?Groupe
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
