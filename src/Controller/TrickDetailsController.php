<?php

namespace App\Controller;


use App\Services\CommentService;
use App\Services\PhotoService;
use App\Services\VideoService;
use App\Services\TrickService;
use App\Entity\TricksPhoto;
use App\Entity\Tricks;
use App\Entity\TricksVideo;
use App\Entity\User;
use App\Repository\TricksPhotoRepository;
use App\Repository\TricksVideoRepository;
use App\Entity\Comments;
use App\Form\CommentType;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;







class TrickDetailsController extends AbstractController
{

    /**
     * @param int $id
     * @param TricksRepository $tricksRepository
     * @param Security $security
     * @param Request $request
     * @param CommentService $commentService
     * @return Response
     */

    #[Route('/trick/details/{name}/{id}', name: 'app_trick_details', methods: ['GET', 'POST'])]
    public function index(
        int                 $id,
        TricksRepository    $tricksRepository,
        Security            $security,
        Request             $request,
        CommentService      $commentService
    ): Response {
        $trick = $tricksRepository->getTrickById($id);
        if (!$trick) {
            throw $this->createNotFoundException('Trick introuvable');
        }

        $user = $security->getUser();


        $comment = new Comments();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user instanceof User) {
                throw new AccessDeniedException('Tu dois être connecté pour commenter.');
            }
            $commentService->createComment(
                $form->get('content')->getData(),
                $user,
                $trick
            );

            $this->addFlash('success', 'Commentaire ajouté avec succès');
            return $this->redirectToRoute('app_trick_details', [
                'id'   => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        return $this->render('trick_details/index.html.twig', [
            'trick' => $trick,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param TricksRepository $tricksRepository
     * @param PhotoService $photoService
     * @return Response
     */

    #[Route('/trick/{id}/add-photo', name: 'app_trick_add_photo', methods: ['POST'])]
    public function addPhoto(
        int $id,
        Request $request,
        TricksRepository $tricksRepository,
        PhotoService $photoService
    ): Response {
        $trick = $tricksRepository->find($id);
        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé.');
        }
        if (!$this->isCsrfTokenValid('add_photo_'.$id, $request->request->get('_token'))) {
            $this->addFlash('error','Token invalide.');
        } else {
            $file = $request->files->get('photo');
            if ($file instanceof UploadedFile) {
                $photoService->addPhoto($file, $trick);
                $this->addFlash('success','Photo ajoutée.');
            }
        }
        return $this->redirectToRoute('app_trick_details',['id'=>$id,'name'=>$trick->getUri()]);
    }


    /**
     * @param int $id
     * @param Request $request
     * @param TricksPhotoRepository $photoRepo
     * @param PhotoService $photoService
     * @return Response
     */

    #[Route('/photo/update/{id}', name: 'app_photo_update', methods: ['POST'])]
    public function updatePhoto(
        int $id,
        Request $request,
        TricksPhotoRepository $photoRepo,
        PhotoService $photoService
    ): Response {
        $photo = $photoRepo->find($id);
        if (!$photo) {
            throw $this->createNotFoundException('Photo non trouvée.');
        }

        if ($this->isCsrfTokenValid('edit_photo_'.$id, $request->request->get('_token'))) {
            $file = $request->files->get('newPhoto');
            if ($file instanceof UploadedFile) {
                $newPhoto = $photoService->updatePhoto($file, $photo);
                if ($newPhoto) {
                    $this->addFlash('success', 'Photo mise à jour !');
                } else {
                    $this->addFlash('error', 'Mise à jour impossible.');
                }
            }
        }

        return $this->redirectToRoute('app_trick_details', [
            'id'   => $photo->getTricks()->getId(),
            'name' => $photo->getTricks()->getUri(),
        ]);
    }


    /**
     * @param int $id
     * @param Request $request
     * @param TricksPhotoRepository $photoRepo
     * @param PhotoService $photoService
     * @return Response
     */
    #[Route('/trick/delete-photo/{id}', name: 'app_trick_delete_photo', methods: ['POST'])]
    public function deletePhoto(
        int $id,
        Request $request,
        TricksPhotoRepository $photoRepo,
        PhotoService $photoService
    ): Response {
        $photo = $photoRepo->find($id);
        if ($photo && $this->isCsrfTokenValid('delete_photo_'.$id, $request->request->get('_token'))) {
            $photoService->deletePhoto($photo);
            $this->addFlash('success', 'Photo supprimée.');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id'   => $photo->getTricks()->getId(),
            'name' => $photo->getTricks()->getUri(),
        ]);
    }


    /**
     * @param int $id
     * @param Request $request
     * @param TricksRepository $tricksRepository
     * @param TrickService $trickService
     * @return Response
     */

    #[Route('/trick/update-fields/{id}', name: 'app_trick_update_fields', methods: ['POST'])]
    public function edit(
        int             $id,
        Request         $request,
        TricksRepository $tricksRepository,
        TrickService    $trickService
    ): Response {
        $trick = $tricksRepository->find($id);
        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé.');
        }

        // Récupère les valeurs du formulaire
        $chapo          = $request->request->get('chapo');
        $description    = $request->request->get('description');
        $featuredImage  = $request->files->get('featuredImage');

        $trickService->updateFields($trick, $chapo, $description, $featuredImage);

        $this->addFlash('success', 'Trick mis à jour avec succès');
        return $this->redirectToRoute('app_trick_details', [
            'id'   => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }

    /**
     * @param Tricks $trick
     * @param Request $request
     * @param TrickService $trickService
     * @return Response
     */
    #[Route('/trick/delete/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function remove(
        Tricks        $trick,           // on auto-hydrate l'entité directement
        Request       $request,
        TrickService  $trickService    // injection du service
    ): Response {
        // Vérification CSRF
        if ($this->isCsrfTokenValid('delete_trick_' . $trick->getId(), $request->request->get('_token'))) {
            // délégation au service
            $trickService->deleteTrick($trick);
            $this->addFlash('success', 'Trick supprimé avec succès');
        }

        return $this->redirectToRoute('app_index');
    }

    /**
     * @param int $id
     * @param Request $request
     * @param TricksRepository $tricksRepository
     * @param VideoService $videoService
     * @return Response
     */

    #[Route('/trick/{id}/add-video', name: 'app_trick_add_video', methods: ['POST'])]
    public function addVideo(
        int $id,
        Request $request,
        TricksRepository $tricksRepository,
        VideoService $videoService
    ): Response {
        $trick = $tricksRepository->find($id);
        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé.');
        }

        if ($this->isCsrfTokenValid('add_video_' . $id, $request->request->get('_token'))) {
            $url = $request->request->get('videoPath');
            if ($url) {
                $videoService->attach($url, $trick);
                $this->addFlash('success', 'Vidéo ajoutée avec succès.');
            } else {
                $this->addFlash('error', 'Aucun lien fourni.');
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id'   => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }


    /**
     * @param int $id
     * @param Request $request
     * @param TricksVideoRepository $videoRepo
     * @param VideoService $videoService
     * @return Response
     */

    #[Route('/video/update/{id}', name: 'app_video_update', methods: ['POST'])]
    public function updateVideo(
        int $id,
        Request $request,
        TricksVideoRepository $videoRepo,
        VideoService $videoService
    ): Response {
        $video = $videoRepo->find($id);
        if (!$video) {
            throw $this->createNotFoundException('Vidéo non trouvée.');
        }

        if ($this->isCsrfTokenValid('edit_video_'.$id, $request->request->get('_token'))) {
            $newUrl = $request->request->get('videoPath');
            if ($newUrl) {
                $videoService->replace($newUrl, $video);
                $this->addFlash('success', 'Lien vidéo mis à jour !');
            } else {
                $this->addFlash('error', 'Aucun lien fourni.');
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id'   => $video->getTricks()->getId(),
            'name' => $video->getTricks()->getUri(),
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param TricksVideoRepository $videoRepo
     * @param VideoService $videoService
     * @return Response
     */

    #[Route('/video/delete/{id}', name: 'app_trick_delete_video', methods: ['POST'])]
    public function deleteVideo(
        int $id,
        Request $request,
        TricksVideoRepository $videoRepo,
        VideoService $videoService
    ): Response {
        $video = $videoRepo->find($id);
        if (!$video) {
            throw $this->createNotFoundException('Vidéo non trouvée.');
        }

        if ($this->isCsrfTokenValid('delete_video_'.$id, $request->request->get('_token'))) {
            $videoService->detach($video);
            $this->addFlash('success', 'Vidéo supprimée.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id'   => $video->getTricks()->getId(),
            'name' => $video->getTricks()->getUri(),
        ]);
    }


    /**
     * @param int $id
     * @param Request $request
     * @param CommentsRepository $repo
     * @param TricksRepository $tricksRepository
     * @return JsonResponse
     */
    #[Route('/trick/{id}/comments/load', name: 'app_trick_load_comments', methods: ['GET'])]
    public function loadComments(
        int $id,
        Request $request,
        CommentsRepository $repo,
        TricksRepository $tricksRepository
    ): JsonResponse {
        $offset = $request->query->getInt('offset', 0);

        // Récupérer le trick pour éviter erreur si non trouvé
        $trick = $tricksRepository->find($id);
        if (!$trick) {
            return new JsonResponse(['html' => ''], Response::HTTP_NOT_FOUND);
        }

        // Charger les commentaires avec offset
        $comments = $repo->findBy(['tricks' => $trick], ['created_at' => 'DESC'], 5, $offset);


        // Générer le HTML depuis un partial Twig
        $html = $this->renderView('trick_details/_comments.html.twig', [
            'comments' => $comments,
        ]);

        return new JsonResponse(['html' => $html]);
    }





}
