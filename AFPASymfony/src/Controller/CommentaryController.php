<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Recipe;
use App\Form\CommentaryType;
use App\Repository\CommentaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commentary')]
final class CommentaryController extends AbstractController
{
    #[Route(name: 'app_commentary_index', methods: ['GET'])]
    public function index(CommentaryRepository $commentaryRepository): Response
    {
        return $this->render('commentary/index.html.twig', [
            'commentaries' => $commentaryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentary_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $comment = $request->request->get('comment');
            $recipeId = $request->request->get('Recipe');
            $recipe = $entityManager->getRepository(Recipe::class)->find($recipeId);
            $author = $this->getUser();
            $commentary = new Commentary();
            $commentary->setComment($comment);
            $commentary->setRecipe($recipe);
            $commentary->setAuthor($author);
            $commentary->setPostedDate(new \DateTime('now', new \DateTimeZone('UTC')));
            $entityManager->persist($commentary);
            $entityManager->flush();
        }

        return $this->redirect('/recipe/show/' . $request->request->get('Recipe'));
    }

    #[Route('/{id}', name: 'app_commentary_show', methods: ['GET'])]
    public function show(Commentary $commentary): Response
    {
        return $this->render('commentary/show.html.twig', [
            'commentary' => $commentary,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentary_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentary $commentary, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentary/edit.html.twig', [
            'commentary' => $commentary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_commentary_delete', methods: ['POST'])]
    public function delete(Request $request, Commentary $commentary, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentary->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentary_index', [], Response::HTTP_SEE_OTHER);
    }
}
