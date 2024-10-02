<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/show/{name}', name: 'app_service')]
    public function showService(string $name ='Motez'): Response
    {
        return $this->render('show.html.twig', [
            'ServiceName' => $name,
        ]);
    }
     #[Route('/gotoindex', name: 'go_index')]
public function Gotoindex(): Response
{
    return $this->redirectToRoute('app_home');
}
}
