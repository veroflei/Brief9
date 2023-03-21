<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\jeux;
use App\Repository\JeuxRepository;

#[Route('/profil', name: 'profil_')]
class ProfilController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(JeuxRepository $jeuxRepository, Request $request)
    {
        // je récupère le panier et je récupère toutes les informations du jeu
        $session = $request->getSession();
        /* var_dump($_SESSION); */
        /* dd($session); */
        $favoris=$session->get('favoris', []); //si jamais elle est vide je recupere un tableau vide
        //On fabrique ensuite les données 
        $dataFavoris =[];
        
        //On boucle sur le panier pour récupérer les données du jeu via l'id
        foreach($favoris as $id){
            $jeux=$jeuxRepository->find($id);
            
            
            if ($jeux){
                $dataFavoris []=[  // le tableau permet de faire un push à l'interieur
                'id' => $id,
                'image' => $jeux->getImage(),
                'titre' => $jeux->getTitre(),
                'description' => $jeux->getDescription(),
                'image' => $jeux->getImage(),
            ];
            
        }
    }

        return $this->render('profil/index.html.twig', [
            'favoris' => $dataFavoris
        ]);
        dd($dataFavoris);
    }

    #[Route('/ajouter/{id}', name: 'ajouter_')]
    public function ajouterJeu($id, SessionInterface $session, JeuxRepository $jeuxRepository)
    {
        /* dd($session); */
        // On récupère les favoris actuels
        $favoris=$session->get('favoris', []); //Si panier n'existe pas, j'initialise $favoris avec un tableau vide 
        /* dd($favoris); */
        
        if (in_array($id, $favoris)) {
            $this->addFlash(
                'warning-user', 
                'Ce jeu est déjà présent dans votre liste de favoris.');
            return $this->redirectToRoute('articles_index');
        }

        $jeux = $jeuxRepository->find($id);
        if($jeux){
            $favoris[]= $jeux->getId();
            $session->set('favoris', $favoris);
        }
        /* dd($favoris); */
        // Une fois la quantité défini, on la sauvegarde dans la session
        $session->set('favoris', $favoris);
        $this->addFlash(
            'success-user',
            'Jeux ajouté à votre liste de jeu avec succès!'
        );

        return $this->redirectToRoute('articles_index');
    }




    #[Route('/supprimer/{id}', name: 'supprimer_')]
    public function supprimerJeu($id, SessionInterface $session)
    {
        // Récupérer les favoris actuels
    $favoris = $session->get('favoris', []);

    // On cherche l'index du jeu à supprimer dans le tableau des favoris
    $index = array_search($id, $favoris);

    // Si jeu dans tableau, on le supprime
    if ($index !== false) {
        unset($favoris[$index]);

        // Mettre à jour la session avec les favoris restants
        $session->set('favoris', $favoris);
    }




    // Rediriger vers la page d'accueil des favoris
    return $this->redirectToRoute('profil_index');
    }

    #[Route('/vider', name: 'vider_')]
    public function viderFavoris(SessionInterface $session)
    {
        // Supprimer les favoris de la session
        $session->remove('favoris');

        // Rediriger vers la page d'accueil des favoris
        return $this->redirectToRoute('profil_index');
    }
}
