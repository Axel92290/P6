<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\TricksPhoto;
use App\Repository\TricksPhotoRepository;




class TrickDetailsController extends AbstractController
{
    #[Route('/trick/details/{name}/{id}', name: 'app_trick_details', methods: ['GET', 'POST', 'DELETE'])]
    public function index(
        int $id,
        TricksRepository $tricksRepository
    ): Response
    {
        return $this->render('trick_details/index.html.twig', [
            'controller_name' => 'TrickDetailsController',
            'trick' => $tricksRepository->getTrickById($id),
        ]);
    }

    #[Route('/trick/{id}/add-photo', name: 'app_trick_add_photo', methods: ['POST'])]
    public function addPhoto(
        int $id,
        Request $request,
        TricksRepository $tricksRepository,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $trick = $tricksRepository->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé.');
        }

        if (!$this->isCsrfTokenValid('add_photo_' . $trick->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        $file = $request->files->get('photo');

        if ($file && $file->isValid()) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = $slugger->slug($originalName);
            $newName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

            $file->move($this->getParameter('uploads_directory'), $newName);

            $photo = new \App\Entity\TricksPhoto();
            $photo->setPath($newName);
            $photo->setCreatedAt(new \DateTimeImmutable());
            $photo->setTricks($trick);
            $photo->setFirst(false);

            $em->persist($photo);
            $em->flush();

            $this->addFlash('success', 'Nouvelle photo ajoutée.');
        } else {
            $this->addFlash('error', 'Erreur lors de l’envoi du fichier.');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }

    #[Route('/trick/{id}/add-video', name: 'app_trick_add_video', methods: ['POST'])]
    public function addVideo(
        int $id,
        Request $request,
        TricksRepository $tricksRepository,
        EntityManagerInterface $em
    ): Response {
        $trick = $tricksRepository->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé.');
        }

        if (!$this->isCsrfTokenValid('add_video_' . $trick->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        $path = $request->request->get('videoPath');

        if ($path) {
            $video = new \App\Entity\TricksVideo();
            $video->setPath($path);
            $video->setCreatedAt(new \DateTimeImmutable());
            $video->setTricks($trick);

            $em->persist($video);
            $em->flush();

            $this->addFlash('success', 'Vidéo ajoutée.');
        } else {
            $this->addFlash('error', 'Lien non valide.');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }



    #[Route('/trick/update-fields/{id}', name: 'app_trick_update_fields', methods: ['POST'])]
    public function updateFields(
        int                    $id,
        Request                $request,
        TricksRepository       $tricksRepository,
        EntityManagerInterface $em,
        SluggerInterface       $slugger
    ): Response
    {
        $trick = $tricksRepository->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('Trick non trouvé.');
        }

        $chapo = $request->request->get('chapo');
        $description = $request->request->get('description');

        if ($chapo !== null) {
            $trick->setChapo($chapo);
        }

        if ($description !== null) {
            $trick->setDescription($description);
        }

        $file = $request->files->get('featuredImage');
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            $file->move($this->getParameter('uploads_directory'), $newFilename);

            foreach ($trick->getTricksPhotos() as $photo) {
                $photo->setFirst(false);
            }

            $photo = new TricksPhoto();
            $photo->setPath($newFilename);
            $photo->setFirst(true);
            $photo->setTricks($trick);

            $em->persist($photo);
        }

        $em->flush();

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }

    #[Route('/photo/update/{id}', name: 'app_photo_update', methods: ['POST'])]
    public function updatePhoto(
        int $id,
        Request $request,
        TricksPhotoRepository $photoRepository,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $photo = $photoRepository->find($id);

        if (!$photo) {
            throw $this->createNotFoundException('Photo non trouvée.');
        }

        $trick = $photo->getTricks();

        if (!$this->isCsrfTokenValid('edit_photo_' . $photo->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        $newFile = $request->files->get('newPhoto');

        if ($newFile && $newFile->isValid()) {
            // 🔥 Supprimer l’ancienne image si elle existe
            $oldFilename = $photo->getPath();
            $uploadDir = $this->getParameter('uploads_directory');
            $oldFilePath = $uploadDir . '/' . $oldFilename;

            if (file_exists($oldFilePath) && str_starts_with(realpath($oldFilePath), realpath($uploadDir))) {
                unlink($oldFilePath);
            }


            // 📸 Enregistrer la nouvelle image
            $originalFilename = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $newFile->guessExtension();

            $newFile->move($uploadDir, $newFilename);
            $photo->setPath($newFilename);
        }

        $em->flush();

        $this->addFlash('success', 'Photo mise à jour et ancienne image supprimée ✅');

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }


    #[Route('/video/update/{id}', name: 'app_video_update', methods: ['POST'])]
    public function updateVideo(
        int $id,
        Request $request,
        \App\Repository\TricksVideoRepository $videoRepository,
        EntityManagerInterface $em
    ): Response {
        $video = $videoRepository->find($id);

        if (!$video) {
            throw $this->createNotFoundException('Vidéo non trouvée.');
        }

        $trick = $video->getTricks();

        // Vérifie le token CSRF
        if (!$this->isCsrfTokenValid('edit_video_' . $video->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        $newPath = $request->request->get('videoPath');

        if ($newPath) {
            $video->setPath($newPath);
            $em->flush();

            $this->addFlash('success', 'Lien vidéo modifié avec succès !');
        }

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }




    #[Route('/trick/delete/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(
        int $id,
        TricksRepository $tricksRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response
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

    #[Route('/photo/delete/{id}', name: 'app_trick_delete_photo', methods: ['POST'])]
    public function deletePhoto(
        int $id,
        Request $request,
        TricksPhotoRepository $photoRepository,
        EntityManagerInterface $em
    ): Response {
        $photo = $photoRepository->find($id);

        if (!$photo) {
            throw $this->createNotFoundException('Photo non trouvée.');
        }

        $trick = $photo->getTricks();

        if (!$this->isCsrfTokenValid('delete_photo_' . $photo->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        // 🔥 Supprimer le fichier image
        $filePath = $this->getParameter('uploads_directory') . '/' . $photo->getPath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $em->remove($photo);
        $em->flush();

        $this->addFlash('success', 'Photo supprimée.');

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }


    #[Route('/video/delete/{id}', name: 'app_trick_delete_video', methods: ['POST'])]
    public function deleteVideo(
        int $id,
        Request $request,
        \App\Repository\TricksVideoRepository $videoRepository,
        EntityManagerInterface $em
    ): Response {
        $video = $videoRepository->find($id);

        if (!$video) {
            throw $this->createNotFoundException('Vidéo non trouvée.');
        }

        $trick = $video->getTricks();

        if (!$this->isCsrfTokenValid('delete_video_' . $video->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_trick_details', [
                'id' => $trick->getId(),
                'name' => $trick->getUri(),
            ]);
        }

        $em->remove($video);
        $em->flush();

        $this->addFlash('success', 'Vidéo supprimée.');

        return $this->redirectToRoute('app_trick_details', [
            'id' => $trick->getId(),
            'name' => $trick->getUri(),
        ]);
    }



}
