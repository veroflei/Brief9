<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\jeux;
use App\Repository\JeuxRepository;
use Twig\Profiler\Profile;

#[Route('/profil', name: 'profil_')]
class ProfilController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session): Response
    {
        // je récupère le panier et je récupère toutes les informations du jeu

        $favoris=$session->get('favoris', []); //si jamais elle est vide je recupere un tableau vide
        
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/ajouter/{id}', name: 'ajouter_')]
    public function ajouterJeu($id, SessionInterface $session)
    {
        /* dd($session); */
        // On récupère les favoris actuels
        $favoris=$session->get('favoris', []); //Si panier n'existe pas, j'initialise $favoris avec un tableau vide 
        /* dd($favoris); */
        if(!empty($favoris[$id])){ // Si mon tableau n'est pas vide, j'incrémente de 1
            $favoris[$id]++;
        } else { //sinon je le crée, et je l'initialise à 1
            $favoris[$id] = 1;
        }
        /* dd($favoris); */
        // Une fois la quantité défini, on la sauvegarde dans la session
        $session->set('favoris', $favoris);
        /* dd($session); */

        return $this->render("profil/index.html.twig");


    }


}
