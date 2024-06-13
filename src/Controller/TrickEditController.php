<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickEditController extends AbstractController
{
    #[Route('/trick/edit', name: 'app_trick_edit')]
    public function index(): Response
    {
        return $this->render('trick_edit/index.html.twig', [
            'controller_name' => 'TrickEditController',
        ]);
    }
}
