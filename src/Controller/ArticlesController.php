<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Form\AvisType;
use App\Entity\Jeux;
use App\Entity\Utilisateur;
use App\Form\CommentsType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JeuxRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name: 'articles_')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(JeuxRepository $JeuxRepository): Response
    {
        $jeux = $JeuxRepository->findAll();
        return $this->render('articles/index.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function showArticle(
        JeuxRepository $JeuxRepository, 
        AvisRepository $avisRepository,
        EntityManagerInterface $entityManager, 
        Request $request,
        Security $security,
        $id
    ): Response {
        $jeu = $JeuxRepository->find($id);
    
        if (!$jeu) {
            throw $this->createNotFoundException('Jeux non trouvé');
        }
    
        $avis = new Avis();
        $avis->setUtilisateur($security->getUser());
        $avis->setJeux($jeu);
        /* dd($avis); */
        $avisForm = $this->createForm(AvisType::class, $avis);
        $avisForm->handleRequest($request);
    
        if ($avisForm->isSubmitted() && $avisForm->isValid()) {
            $avis->setJeux($jeu);
            $entityManager->persist($avis);
            $entityManager->flush();
    
            return $this->redirectToRoute('articles_article', ['id' => $id]);
        }
    
        $commentaires = $avisRepository->findBy(['jeux' => $jeu]);
        /* dd($commentaires);  */
    
        return $this->render('articles/article.html.twig', [
            'jeu' => $jeu,
            'avisForm' => $avisForm->createView(),
            'commentaires' => $commentaires
        ]);
    }
}
