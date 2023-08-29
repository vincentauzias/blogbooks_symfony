<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\Comment1Type;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article/{id}/comment')]
class CommentController extends AbstractController
{
    // #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    // public function index(CommentRepository $commentRepository): Response
    // {
    //     return $this->render('comment/index.html.twig', [
    //         'comments' => $commentRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Article $article): Response
    {
        $comment = new Comment();
        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setUser($this->getUser());
        $comment->setArticle($article);
        $form = $this->createForm(Comment1Type::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', [
                'id' => $article->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    // public function show(Comment $comment): Response
    // {
    //     return $this->render('comment/show.html.twig', [
    //         'comment' => $comment,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(Comment1Type::class, $comment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('comment/edit.html.twig', [
    //         'comment' => $comment,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    // public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($comment);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    // }
}