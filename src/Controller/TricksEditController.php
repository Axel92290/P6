<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TricksType;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tricks/edit')]
class TricksEditController extends AbstractController
{
    #[Route('/', name: 'app_tricks_edit_index', methods: ['GET'])]
    public function index(TricksRepository $tricksRepository): Response
    {
        return $this->render('tricks_edit/index.html.twig', [
            'tricks' => $tricksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tricks_edit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trick = new Tricks();
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau trick créé avec succès');

            return $this->redirectToRoute('app_tricks_edit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tricks_edit/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tricks_edit_show', methods: ['GET'])]
    public function show(Tricks $trick): Response
    {
        return $this->render('tricks_edit/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tricks_edit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tricks $trick, EntityManagerInterface $entityManager): Response
    {
        $editForm = $this->createForm(TricksType::class, $trick);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Trick modifié avec succès');

            // Redirection vers la page de détails du trick
            return $this->redirectToRoute('app_trick_details', [
                'name' => $trick->getUri(),
                'id' => $trick->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tricks_edit/edit.html.twig', [
            'trick' => $trick,
            'editForm' => $editForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tricks_edit_delete', methods: ['POST'])]
    public function delete(Request $request, Tricks $trick, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Trick supprimé avec succès');
        }

        return $this->redirectToRoute('app_tricks_edit_index', [], Response::HTTP_SEE_OTHER);
    }
}
