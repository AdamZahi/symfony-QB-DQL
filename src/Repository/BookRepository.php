<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchBookByRef(string $ref): ?Book
    {
        return $this->createQueryBuilder('b')
            ->where('b.id = :ref')
            ->setParameter('ref', $ref)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function booksListByAuthors(): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->orderBy('a.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function prolificAuthors(): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->where('b.publicationDate < :year2023')
            ->andWhere('a.nbBooks > 10')
            ->setParameter('year2023', new \DateTime('2023-01-01'))
            ->orderBy('a.username', 'ASC')     
            ->addOrderBy('b.publicationDate', 'ASC'); 
        return $queryBuilder->getQuery()->getResult();
    }

    public function updateSciFiToRomance():mixed
    {
        return $this->createQueryBuilder('b')
            ->update(Book::class, 'b')
            ->set('b.category', ':newCategory')
            ->where('b.category = :oldCategory')
            ->setParameter('newCategory', 'Romance')
            ->setParameter('oldCategory', 'Sci-Fi')
            ->getQuery()
            ->execute();
    }

    //PartDQL
    public function orderedListDQL(){
        $em = $this->getEntityManager();
        return  $em->createQuery(
            "select * from APP\Entity\Author a orderBy a.username"
            )->getResult();
        }

        public function countRomanceBooks(): int
        {
            $dql = 'SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.category = :category';
            $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('category','Romance');   
            return (int) $query->getSingleScalarResult();
        }

        public function findBooksPublishedBetween(\DateTime $startDate, \DateTime $endDate): array
    {
        $dql = 'SELECT b FROM App\Entity\Book b WHERE b.publicationDate BETWEEN :startDate AND :endDate';
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
        return $query->getResult();
    }
}
