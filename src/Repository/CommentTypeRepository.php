<?php

namespace App\Repository;

use App\Entity\CommentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentType[]    findAll()
 * @method CommentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentType::class);
    }

    // /**
    //  * @return CommentType[] Returns an array of CommentType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
