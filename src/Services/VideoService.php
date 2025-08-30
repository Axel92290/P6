<?php
// src/Service/VideoService.php
namespace App\Services;

use App\Entity\Tricks;
use App\Entity\TricksVideo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VideoService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    /**
     * Attache un nouveau lien vidéo à un Trick.
     */
    public function attach(string $url, Tricks $trick): TricksVideo
    {
        $url = trim($url);
        if ($url === '') {
            throw new BadRequestException('URL de vidéo invalide');
        }

        $video = (new TricksVideo())
            ->setPath($url)
            ->setTricks($trick)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($video);
        $this->em->flush();

        return $video;
    }

    /**
     * Remplace l'URL d'une vidéo existante.
     */

    public function replace(string $url, TricksVideo $video): TricksVideo
    {
        $url = trim($url);
        if ($url === '') {
            throw new BadRequestException('URL de vidéo invalide');
        }

        $video
            ->setPath($url)
            ->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();

        return $video;
    }


    /**
     * Détache une vidéo d’un Trick.
     */
    public function detach(TricksVideo $video): void
    {
        $this->em->remove($video);
        $this->em->flush();
    }



}
