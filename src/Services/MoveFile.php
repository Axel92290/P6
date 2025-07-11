<?php

namespace App\Services;

use App\Entity\TricksPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;



class MoveFile
{
    /**
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     */
    public function __construct(private readonly RequestStack $requestStack, private readonly EntityManagerInterface $em, private readonly SluggerInterface $slugger)
    {
    }

    /**
     * @param string $target
     * @param Tricks $trick
     * @return void
     */
    public function upload($target, $trick) : void
    {

        $file = $this->requestStack->getCurrentRequest()->files->get('photo');
        if ($file && $file->isValid()) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = $this->slugger->slug($originalName);
            $newName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

            $file->move($target, $newName);


            $photo = new TricksPhoto();
            $photo->setPath($newName);
            $photo->setCreatedAt(new \DateTimeImmutable());
            $photo->setTricks($trick);
            $photo->setFirst(false);

            $this->em->persist($photo);
            $this->em->flush();

            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('success', 'Fichier envoyé avec succès.');
        } else {
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('error', 'Erreur lors de l\'envoi du fichier.');
        }

    }

}