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
        $video = new TricksVideo();
        $url = $this->transformUrl($url);
        if (!$url) {
            throw new BadRequestException('URL de vidéo invalide');
        }
        $video
            ->setPath($url)
            ->setTricks($trick)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(null)
        ;

        $this->em->persist($video);
        $this->em->flush();

        return $video;
    }

    /**
     * Remplace le lien d’une vidéo existante.
     */
    public function replace(string $url, TricksVideo $video): TricksVideo
    {
        $url = $this->transformUrl($url);
        if (!$url) {
            throw new BadRequestException('URL de vidéo invalide');
        }
        $video
            ->setPath($url)
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
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

    /**
     * Transforme une URL de vidéo en un lien exploitable par le lecteur vidéo.
     */
    private function transformUrl(string $url): string
    {
        // Si c’est déjà un embed YouTube, on renvoie l’URL brute (query incluse)
        if (preg_match('#^https?://(?:www\.)?youtube\.com/embed/[A-Za-z0-9_-]+#', $url)) {
            return $url;
        }


        // Format « watch?v=VIDEO_ID »
        if (preg_match('/[?&]v=([A-Za-z0-9_-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Format « youtu.be/VIDEO_ID »
        if (preg_match('#youtu\.be/([A-Za-z0-9_-]+)#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        throw new BadRequestException(sprintf(
            'Impossible d’extraire l’ID YouTube depuis « %s »',
            $url
        ));

    }
}
