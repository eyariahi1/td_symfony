<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function ShowAuthor(string $name='default'): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
    #[Route('/author', name: 'app_author')]
    public function listAuthors():Response
    {
        $authors = [

            ['id' => 1, 'picture' => '/images/vh.png', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],

            ['id' => 2, 'picture' => '/images/ws.png', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],

            ['id' => 3, 'picture' => '/images/th.png', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],

        ];
        // Vérification si le tableau est vide
        if (empty($authors)) {

            return $this->render('author/liste.html.twig', [
                'authors' => [],
                'message' => 'Aucun auteur trouvé.',
            ]);
        }

        return $this->render('author/liste.html.twig', [
            'authors' => $authors,
            'message' => null,
        ]);
    }
    #[Route('/author/{id}', name: 'author_details')]
    public function authorDetails(int $id): Response
    {

        $authors = [
            1 => ['id' => 1, 'picture' => '/images/vh.png', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            2 => ['id' => 2, 'picture' => '/images/ws.png', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            3 => ['id' => 3, 'picture' => '/images/th.png', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        $author = $authors[$id] ?? null;

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }

}
