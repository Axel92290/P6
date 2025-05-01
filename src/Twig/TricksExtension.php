<?php

namespace App\Twig;

use App\Entity\Tricks;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TricksExtension extends AbstractExtension
{

    /**
     * @param Tricks $trick
     * @return string
     */

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getFeaturedImage', [$this, 'getFeaturedImage']),
            new TwigFunction('getFeaturedImageId', [$this, 'getFeaturedImageId']),
        ];
    }

    /**
     * @param Tricks $trick
     * @return string|null
     */
    public function getFeaturedImage(?Tricks $trick): ?string
    {
        // Vérifie si $trick est null
        if ($trick === null) {
            return null; // Ou une image par défaut
        }

        $photosList = $trick->getTricksPhotos();

        // Vérifie si la liste des photos n'est pas vide
        if (count($photosList) === 0) {
            return null; // Ou une image par défaut
        }

        $featuredImage = null;

        // Recherche l'image vedette
        foreach ($photosList as $photo) {
            if ($photo->isFirst()) {
                $featuredImage = $photo->getPath();
                break;
            }
        }

        // Si aucune image vedette n'est trouvée, prends la première photo de la liste
        if ($featuredImage === null) {
            $featuredImage = $photosList[0]->getPath(); // Utilisation de l'index pour accéder au premier élément
        }

        return $featuredImage;
    }
    public function getFeaturedImageId(?Tricks $trick): ?string
    {
        // Vérifie si $trick est null
        if ($trick === null) {
            return null; // Ou une image par défaut
        }

        $photosList = $trick->getTricksPhotos();

        // Vérifie si la liste des photos n'est pas vide
        if (count($photosList) === 0) {
            return null; // Ou une image par défaut
        }

        $featuredImageId = null;

        // Recherche l'image vedette
        foreach ($photosList as $photo) {
            if ($photo->isFirst()) {
                $featuredImageId = $photo->getId();
                break;
            }
        }

        // Si aucune image vedette n'est trouvée, prends la première photo de la liste
        if ($featuredImageId === null) {
            $featuredImageId = $photosList[0]->getId(); // Utilisation de l'index pour accéder au premier élément
        }

        return $featuredImageId;
    }




}