<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickCreateController extends AbstractController
{
    #[Route('/trick/create', name: 'app_trick_create')]
    public function index(): Response
    {
        return $this->render('trick_create/index.html.twig', [
            'controller_name' => 'TrickCreateController',
        ]);
    }
}
