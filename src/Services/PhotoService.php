<?php

namespace App\Services;

use App\Entity\Tricks;
use App\Entity\TricksPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhotoService
{
    /**
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @param string $uploadsDirectory
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SluggerInterface       $slugger,
        private readonly string                 $uploadsDirectory
    )
    {
    }

    /**
     * @param Tricks $trick
     * @return TricksPhoto[]
     */

    /**
     * Ajoute une nouvelle photo à un Trick
     */
    public function addPhoto(UploadedFile $file, Tricks $trick, bool $isFirst = false): ?TricksPhoto
    {
        if (!$file->isValid()) {
            return null;
        }

        // Génération du nom
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($original);
        $newName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

        // Déplacement du fichier
        $file->move($this->uploadsDirectory, $newName);

        // Si c'est la featured image, on désactive les anciennes
        if ($isFirst) {
            foreach ($trick->getTricksPhotos() as $p) {
                $p->setFirst(false);
            }
        }

        // Création et persist de la nouvelle photo
        $photo = new TricksPhoto();
        $photo
            ->setPath($newName)
            ->setFirst($isFirst)
            ->setTricks($trick)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($photo);
        $this->em->flush();

        return $photo;
    }


    /**
     * Met à jour une photo existante
     */


    public function updatePhoto(UploadedFile $file, TricksPhoto $photo): ?TricksPhoto
    {
        $trick = $photo->getTricks();
        $wasFirst = $photo->isFirst();

        // Supprime l'ancien fichier (silencieux)
        unlink($this->uploadsDirectory . '/' . $photo->getPath());

        // Supprime l'entité
        $this->em->remove($photo);
        $this->em->flush();

        // Ajoute la nouvelle
        return $this->addPhoto($file, $trick, $wasFirst);
    }

    /**
     * Supprime une photo
     */
    public function deletePhoto(TricksPhoto $photo): void
    {
        unlink($this->uploadsDirectory . '/' . $photo->getPath());
        $this->em->remove($photo);
        $this->em->flush();
    }
}
