<?php


// src/Controller/IndexController.php
namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(TricksRepository $tricksRepository): Response
    {
        // Fetch the first 6 tricks
        $tricks = $tricksRepository->getAllTricks(6, 0);

        return $this->render('index/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    #[Route('/load-more-tricks', name: 'load_more_tricks', methods: ['GET'])]
    public function loadMoreTricks(Request $request, TricksRepository $tricksRepository): JsonResponse
    {

        $page = $request->query->getInt('page', 2);
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $tricks = $tricksRepository->getAllTricks($limit, $offset);

        //renommez en render et fais $this->render
        $html = $this->renderView('index/_tricks.html.twig', [
            'tricks' => $tricks,
        ]);

        return new JsonResponse(['html' => $html]);
    }
}



