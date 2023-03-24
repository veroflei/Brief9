<?php 

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\JeuxRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    #[Route('/', name: 'home')]
    public function index(JeuxRepository $jeuxRepository, CategorieRepository $categorieRepository, SessionInterface $session, Request $request): Response {
        
        $session = $request -> getSession();
        // Récupérer une catégorie aléatoire
        $categories = $categorieRepository->findAll();
        if (!empty($categories)) {
            $categorie = $categories[array_rand($categories)];
          /*   dd($categories); */
            
            // Récupérer 4 jeux aléatoires de la catégorie
            $jeux = $jeuxRepository->findAll();
        } else {
            // Si la base de données est vide, initialiser des variables par défaut
            $categorie = null;
            $jeux = [];
        }
        
        return $this->render('home/accueil.html.twig', [
            'jeux' => $jeux,
        ]);
    }
}