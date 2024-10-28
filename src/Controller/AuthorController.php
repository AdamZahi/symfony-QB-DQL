<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/list',name:'list_author')]
    public function listAuthors(ManagerRegistry $doctrine): Response
    {
        $repo= $doctrine->getRepository(Author::class);
        $list=$repo->orderedListQB();
        return $this->render('author/list.html.twig', [
            'list' => $list,
        ]);
    }
    
    
    #[Route('/search', name: 'search_authors')]
    public function searchAuthors(Request $request,ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Author::class);
            $valueMax = $request->get("max");
            $valueMin = $request->get("min");
            if($valueMax === null || $valueMin === null){
                $list = $repo->findAll();
            }
            // else{
            //     $list = $repo->findAuthorsByBookCount($valueMax,$valueMin);
            // }
        return $this->render('author/search.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/delete0' , name:'author_delete0')]
    public function deleteAuthor0(AuthorRepository $repo):Response{
        $repo->deleteAuthor0();
        return $this->redirectToRoute('list_author');
    }
    
}
