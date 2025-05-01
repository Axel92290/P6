<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TrickDetailsController extends AbstractController
{
    #[Route('/trick/details/{name}/{id}', name: 'app_trick_details', methods: ['GET', 'POST', 'DELETE'])]
    public function index(int $id, TricksRepository $tricksRepository): Response
    {
        return $this->render('trick_details/index.html.twig', [
            'controller_name' => 'TrickDetailsController',
            'trick' => $tricksRepository->getTrickById($id),
        ]);
    }


    #[Route('/trick/delete/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(int $id, TricksRepository $tricksRepository, EntityManagerInterface $em, Request $request): Response
    {
        $trick = $tricksRepository->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('Trick not found');
        }

        if ($this->isCsrfTokenValid('delete_trick_' . $trick->getId(), $request->request->get('_token'))) {
            $em->remove($trick);
            $em->flush();

            $this->addFlash('success', 'Trick supprimé avec succès');
        }

        return $this->redirectToRoute('app_index');
    }





}
