<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }
    #[Route('/add_employe', name: 'app_add_employe')]
    public function add_employe(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $employe=new Employe();
        $form=$this->createForm(EmployeType::class,$employe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('app_classe');
        }


        return $this->render('employe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/liste_employe/{id}', name: 'app_liste_employe')]
    public function liste_etudiant(int $id, EntrepriseRepository $entrepriseRepository): Response
    {
        // Récupérer la classe par ID
        $entreprise = $entrepriseRepository->find($id);

        if ($entreprise) {
            // Récupérer tous les étudiants de la classe
            $employe = $entreprise->getEmployes(); // Cette méthode doit être définie dans la classe

            return $this->render('employe/liste_employe.html.twig', [
                'entreprise' => $entreprise,
                'employe' => $employe,
                'message' => "Liste des employe pour la entreprise : " . $entreprise->getNom(),
            ]);
        } else {
            return $this->render('etudiant/liste_etudiant.html.twig', [
                'employe' => [],
                'message' => "Aucune entreprise trouvée pour l'ID {$id}.",
            ]);
        }
    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id,ManagerRegistry $managerRegistry,EmployeRepository $employeRepository): Response
    {
        $employe=$employeRepository->find($id);
        if($employe)
        {
            $cnx=$managerRegistry->getManager();
            $cnx->remove($employe);
            $cnx->flush();
            return $this->redirectToRoute('app_classe');

        }
        return $this->redirectToRoute('app_classe');
    }
   
}
