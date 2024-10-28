<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/listDB',name:'list_book')]
    public function listDB(ManagerRegistry $doctrine): Response
    {
        $repo= $doctrine->getRepository(Book::class);
        $list=$repo->findAll();
        return $this->render('book/list.html.twig', [
            'list' => $list,
        ]);
    }
    #[Route('/search', name: 'book_search')]
    public function search(Request $request, BookRepository $repo): Response
    {
        $ref = $request->get('ref');
        $books = [];

        if ($ref) {
            $book = $repo->searchBookByRef($ref);
            if ($book) {
                $books[] = $book;
            }
        }
        return $this->render('book/bookDetail.html.twig', [
            'books' => $books,
            'ref' => $ref,
        ]);
    }

    #[Route('/byAuthors', name: 'books_by_authors')]
    public function booksByAuthors(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->booksListByAuthors();

        return $this->render('book/by_authors.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/prolific', name: 'books_prolific')]
    public function prolificAuthors(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->prolificAuthors();
        return $this->render('book/prolific_authors.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/sci-fiToRomance', name: 'update_sci_fi_to_romance')]
    public function updateSciFiToRomance(BookRepository $bookRepository): Response
    {
        $bookRepository->updateSciFiToRomance();
        return new Response("Updated books from 'Sci-Fi' to 'Romance'.");
    }

    // Part DQL
    #[Route('/countRomance', name: 'count_romance_books')]
    public function countRomanceBooks(BookRepository $bookRepository): Response
    {
        $romanceBooksCount = $bookRepository->countRomanceBooks();

        return $this->render('book/romance_count.html.twig', [
            'count' => $romanceBooksCount,
        ]);
    }

    #[Route('/publishedBetween', name: 'books_published_between')]
    public function publishedBetween(BookRepository $bookRepository): Response
    {
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2018-12-31');
        $books = $bookRepository->findBooksPublishedBetween($startDate, $endDate);
        return $this->render('book/published_between.html.twig', [
            'books' => $books,
        ]);
    }
}
