<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\Categorie;
use App\Form\JeuxType;
use App\Repository\JeuxRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

#[Route('/admin', name: 'admin_')]
#[IsGranted('ROLE_ADMIN')]

class AdminController extends AbstractController
{

    /**
     * Cette fonction affiche tous les jeux disponibles
     * 
     * @param JeuxRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/', name: 'index', methods: ['GET'])]

    public function index(JeuxRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $jeux = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            8 
        );
    
        return $this->render('admin/index.html.twig', [
            'jeux' => $jeux
        ]);
    }
    


    /**
     * Ajouter un jeu Via le CRUD dans la base de donnée
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/nouveau', name: 'nouveau_', methods: ['GET', 'POST'])] /* Soumission du formulaire en POST */
    public function new(Request $request, EntityManagerInterface $manager, CategorieRepository $categorieRepository): Response
    {
        $jeux = new Jeux();
        $form = $this->createForm(JeuxType::class, $jeux);
        $categorie = $categorieRepository->findAll();
  
    
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $jeux = $form->getData();
            $tabCategorieId = $form->get('categorie')->getData();
          /*   dd($form->getData('categorie')); */
          $tmp = array();
            foreach($tabCategorieId as $categorie){
                $categories = $categorieRepository->find(array('id' => $categorie));
                array_push($tmp, $categories);
                $jeux->addCategorie($categories);
            }
            
            $manager->persist($jeux);
            $manager->flush();
            $this->addFlash(
                'success',
                'Jeux ajouté avec succès!'
            );

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/nouveau.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorie
        ]);
    }
    

    
    #[Route('/editer/{id}', name: 'editer', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editer(
        JeuxRepository $repository, 
        $id, 
        Request $request, 
        EntityManagerInterface $manager
        ) : Response
    {
        $jeux= $repository->find(array('id' => $id));
        /* dd($jeux); */
        $form = $this -> createForm(JeuxType::class, $jeux);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $manager->flush();

        $this->addFlash(
            'success',
            'Jeux modifié avec succès!'
        );

        return $this->redirectToRoute('admin_index');
    }

        return $this->render('admin/editer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    #[Route('/supprimer/{id}', name: 'supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function supprimer(
        EntityManagerInterface $manager, 
        Jeux $jeux
        ) : Response
    {
        if(!$jeux){
            $this->addFlash(
                'warning',
                'Le jeux n\a pas été trouvé !'
            );
            return $this->render('admin_index');
        }
        $manager->remove($jeux);
        $manager->flush();

        $this->addFlash(
            'success',
            'Jeux supprimé avec succès!'
        );
        return $this->redirectToRoute('admin_index');
    }
       
} 