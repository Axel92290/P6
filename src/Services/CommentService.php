<?php

namespace App\Services;

use App\Entity\Comments;
use App\Entity\User;
use App\Entity\Tricks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;

class CommentService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {

    }

    public function createComment(string $content, User $user, Tricks $trick): Comments
    {
        $comment = new Comments();
        $comment->setContent($content);
        $comment->setUser($user);
        $comment->setTricks($trick);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}