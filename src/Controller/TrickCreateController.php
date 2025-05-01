<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TricksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickCreateController extends AbstractController
{
    #[Route('/trick/create', name: 'app_trick_create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $trick = new Tricks();
        $user = $this->getUser();
        $trick->setUser($user);

        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $trick->setUser($this->getUser());
            $trick->setUpdatedAt(new \DateTimeImmutable());
            // $trick->setChapo($form->get('chapo')->getData());
            // $trick->setDescription($form->get('description')->getData());

            $entityManager->persist($trick);
            $entityManager->flush();


            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getName(),

            ]);
        }


        return $this->render('trick_create/index.html.twig', [
            'trickForm' => $form->createView(),
        ]);
    }
}

