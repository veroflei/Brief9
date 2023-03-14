<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'app_profil_')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'Profil de l\utilisateur',
        ]);
    }

    #[Route('/jeux', name: 'jeux')]
    public function jeux(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'Jeux de l\utilisateur',
        ]);
    }


}
