<?php

namespace App\Controller;
use App\Repository\EntrepriseRepository;
use App\Form\EntrepriseType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Entreprise;


use Doctrine\Persistence\ManagerRegistry;



class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name:'app_entreprise')]
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }

    #[Route('/', name:'app_classe')]
    public function liste_entreprise(ManagerRegistry $managerRegistry, EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = $entrepriseRepository->findAll();
        if ($entreprise != null) {
            return $this->render('entreprise/liste_entreprise.html.twig', [
                'entreprise' => $entreprise,
                'message' => ""

            ]);
        } else {
            return $this->render('entreprise/liste_entreprise.html.twig', [
                'entreprise' => $entreprise,
                'message' => "aucune entreprise"
            ]);
        }


    }

    #[Route('/add_entreprise', name: 'app_add_entreprise')]
    public function add_entreprise(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the entreprise already exists
            if ($entrepriseRepository->findOneBy(['nom' => $entreprise->getNom()])) {
                $this->addFlash('error', 'Ce nom de classe existe déjà.');
            } else {
                // Save the new entreprise
                $entityManager->persist($entreprise);
                $entityManager->flush();

                // Flash success message and redirect
                $this->addFlash('success', 'Entreprise ajoutée avec succès!');
                return $this->redirectToRoute('app_classe');
            }
        }

        return $this->render('entreprise/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/entreprise/{id}/employe', name: 'app_classe_etudiants')]
    public function listeEmploye(EntrepriseRepository $entrepriseRepository, int $id): Response
    {
        $entreprise = $entrepriseRepository->find($id);

        if (!$entreprise) {
            throw $this->createNotFoundException('entreprise non trouvée');
        }

        // Récupérer les étudiants de la classe
        $employe = $entreprise->getEmployes();

        return $this->render('etudiant/liste_employe.html.twig', [
            'entreprise' => $entreprise,  // Classe mise à jour
            'employe' => $employe,  // Liste des étudiants de cette classe
            'message' => 'Étudiants de la classe mise à jour : '.$entreprise->getNom()  // Message d'information
        ]);
    }

    #[Route('/update_entreprise/{id}', name: 'app_update_classe')]
    public function update_classe(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, int $id): Response
    {
        $entreprise = $entrepriseRepository->find($id);

        if (!$entreprise) {
            throw $this->createNotFoundException('entreprise non trouvée');
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouveauNom = $entreprise->getNom();

            if ($entrepriseRepository->existsByNom($nouveauNom)) {
                $this->addFlash('error', 'Ce nom de entreprise existe déjà.');

                return $this->render('entreprise/update.html.twig', [
                    'form' => $form->createView(),
                    'entreprise' => $entreprise,
                ]);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_classe_etudiants', ['id' => $entreprise->getId()]);


        }

        return $this->render('entreprise/update.html.twig', [
            'form' => $form->createView(),
            'entreprise' => $entreprise
        ]);
    }
    #[Route('/delete_en/{id}', name: 'app_delete_en')]
    public function delete_en($id,ManagerRegistry $managerRegistry,EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise=$entrepriseRepository->find($id);
        if($entreprise)
        {
            $cnx=$managerRegistry->getManager();
            $cnx->remove($entreprise);
            $cnx->flush();
            return $this->redirectToRoute('app_classe');

        }
        return $this->redirectToRoute('app_classe');
    }







}


