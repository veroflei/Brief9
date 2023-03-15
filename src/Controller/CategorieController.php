<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\JeuxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
     public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        /* dd($categories); */
        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{id}', name: 'categorie')]
    public function afficherJeuxParCategorie(JeuxRepository $jeuxRepository, CategorieRepository $categorieRepository, $id): Response
    {
        $categorie = $categorieRepository->find(array('id' => $id));
        $jeux = $categorie->getJeuxes();
        return $this->render('categories/categorie.html.twig', [
            'categorie' => $categorie,
            'jeux' => $jeux,
        ]);
    }
}
