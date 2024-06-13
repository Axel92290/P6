<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickDetailsController extends AbstractController
{
    #[Route('/trick/details', name: 'app_trick_details')]
    public function index(): Response
    {
        return $this->render('trick_details/index.html.twig', [
            'controller_name' => 'TrickDetailsController',
        ]);
    }
}
