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
use Symfony\Component\Validator\Constraints\Regex;

#[Route('/admin', name: 'admin_')]
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
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $jeux= new Jeux;
        $form = $this -> createForm(JeuxType::class, $jeux);
       /*  dd($form); */

        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $jeux=$form->getData(); /* dd($form->getData());*/
            $manager->persist($jeux);
            $manager->flush();

            $this->addFlash(
                'success',
                'Jeux ajouté avec succès!'
            );

            return $this->redirectToRoute('admin_index');
        } 
        return $this->render('admin/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/editer/{id}', name: 'editer_', methods: ['GET', 'POST'])]
    public function editer(
        JeuxRepository $repository, 
        int $id, 
        Request $request, 
        EntityManagerInterface $manager) : Response
    {
        $jeux= $repository->find(array('id' => $id));
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
       
} 