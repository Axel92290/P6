<?php

namespace App\Services;

use App\Entity\Tricks;
use App\Entity\TricksPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SluggerInterface       $slugger,
        private readonly string                 $uploadsDirectory
    )
    {
    }

    /**
     * Met à jour le chapo, la description et la featured image (si fourni).
     */
    public function updateFields(
        Tricks        $trick,
        ?string       $chapo,
        ?string       $description,
        ?UploadedFile $featuredImage
    ): void
    {

        if ($chapo !== null) {
            $trick->setChapo($chapo);
        }
        if ($description !== null) {
            $trick->setDescription($description);
        }

        // Featured image
        if ($featuredImage instanceof UploadedFile) {
            // Générer un nom safe
            $original = pathinfo($featuredImage->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = $this->slugger->slug($original);
            $newName = $safeName . '-' . uniqid() . '.' . $featuredImage->guessExtension();

            // Déplacer le fichier
            $featuredImage->move($this->uploadsDirectory, $newName);

            // Désactiver l’ancienne featured
            foreach ($trick->getTricksPhotos() as $photo) {
                $photo->setFirst(false);
            }

            // Créer et persist la nouvelle photo
            $photo = new TricksPhoto();
            $photo
                ->setPath($newName)
                ->setFirst(true)
                ->setTricks($trick)
                ->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($photo);
        }

        // Flush final
        $this->em->flush();
    }

    /**
     * Supprime un Trick.
     */
    public function deleteTrick(Tricks $trick): void
    {
        $this->em->remove($trick);
        $this->em->flush();
    }
}
