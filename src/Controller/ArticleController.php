<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    #[Route('/article/new', name: 'app_add_article',methods: ['GET', 'POST'])]
    public function newArticle(Request $request, EntityManagerInterface $manager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setAuteur($this->getUser());
            $manager->persist($article);
            $manager->flush();
            $this->addFlash(
                "success",
                "Votre article a été crée avec succès"
            );
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/new.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{id}', name: 'app_show_article', methods: ["GET"])]
    public function showArticle(int $id, Request $request, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
        if (!$article) {
            $this->addFlash(
                "error",
                "l'Article n'existe pas"
            );
            return $this->redirectToRoute("app_article");
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'app_edit_article', methods: ["GET", "POST"])]
    public function editArticle(int $id, Request $request, EntityManagerInterface $manager, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            $this->addFlash(
                "error",
                "l'Article n'existe pas"
            );
            return $this->redirectToRoute("app_article");
        }

        if ($article->getAuteur() != $this->getUser()) {
            $this->addFlash(
                "error",
                "Vous n'êtes pas propriétaire de l'article"
            );
            return $this->redirectToRoute("app_article");
        }
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'article a été modifié avec succès"
            );

            return $this->redirectToRoute("app_article");
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }
    #[Route('/article/delete/{id}', name: 'app_delete_article', methods: ["GET"])]
    public function deleteArticle(int $id,EntityManagerInterface $manager, ArticleRepository $articleRepository): Response
    {
        $article =  $articleRepository->find($id);
        if($article->getAuteur() != $this->getUser()){
            $this->addFlash(
                "error",
                "Vous n'êtes pas propriétaire de l'article"
            );
        } else {
            $manager->remove($article);
            $manager->flush();
            $this->addFlash(
                "success",
                "L'article a été avec succès"
            );
        }
        return $this->redirectToRoute("app_article");
    }
}
