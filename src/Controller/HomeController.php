<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/index', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig',[
            'message' => 'Bonjour mes étudiants'
        ]);
    }

}
