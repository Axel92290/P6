<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Entity\TricksPhoto;
use App\Entity\TricksVideo;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $usernames = []; // Tableau pour suivre les usernames déjà utilisés

        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            // Générer un username unique
            do {
                $username = $this->faker->userName; // Génère un username aléatoire
            } while (in_array($username, $usernames)); // Vérifie si le username existe déjà

            $user->setUsername($username);
            $usernames[] = $username; // Ajoute le username au tableau

            $user->setEmail($this->faker->email);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $this->faker->password
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

            for ($j = 0; $j < mt_rand(1, 4); $j++) {
                $tricks = new Tricks();
                $tricks->setName('Trick ' . $this->faker->streetName);
                $tricks->setDescription($this->faker->paragraph);
                $tricks->setChapo($this->faker->word);
                $tricks->setCreatedAt(new \DateTimeImmutable());
                $tricks->setUser($user);

                $manager->persist($tricks);

                $tricksPhoto = new TricksPhoto();
                $tricksPhoto->setPath('/assets/pictures/' . mt_rand(0, 2) . '.jpg');
                $tricksPhoto->setCreatedAt(new \DateTimeImmutable());
                $tricksPhoto->setFirst(true);
                $tricksPhoto->setTricks($tricks);
                $manager->persist($tricksPhoto);

                $tricksVideo = new TricksVideo();
                $tricksVideo->setPath('https://youtu.be/FuVwExvBtdo?si=OQ71FX01VW2KJQu5');
                $tricksVideo->setCreatedAt(new \DateTimeImmutable());
                $tricksVideo->setTricks($tricks);
                $manager->persist($tricksVideo);

                for ($m = 0; $m < mt_rand(1, 5); $m++) {
                    $comment = new Comments();
                    $comment->setContent($this->faker->paragraph);
                    $comment->setCreatedAt(new \DateTimeImmutable());
                    $comment->setUpdatedAt(new \DateTimeImmutable());
                    $comment->setTricks($tricks);
                    $comment->setUser($user);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }


}
