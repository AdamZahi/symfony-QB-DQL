<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function orderedListQB(): mixed{
        return $this->createQueryBuilder('a')
        ->orderBy("a.email","ASC")
        ->getQuery()
        ->getResult();
    }
    public function findAuthorsByBookCount(int $min, int $max): mixed
    {
        $dql = 'SELECT * FROM App\Entity\Author a where a.nbBooks < :maxBooks 
        and where a.nbBooks > :minBooks';
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('minBooks', $min)
            ->setParameter('maxBooks', $max);
        return $query->getResult();
    }

    public function deleteAuthor0 (){
        $em= $this->getEntityManager();
        return $em->createQuery(
            'delete APP\Entity\Author a where a.nbBooks = 0'
            )->getResult();
    }
}
