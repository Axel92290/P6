<?php

namespace App\DataFixtures;

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

    public function  __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {


            $user = new User();
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
                $tricks->setCreatedAt(new \DateTimeImmutable());
                $tricks->setUser($user);
                $manager->persist($tricks);


                for ($k = 0; $k < mt_rand(1, 3); $k++) {
                    $tricksPhoto = new TricksPhoto();
                    $tricksPhoto->setPath('/assets/pictures/' . mt_rand(0,2) . '.jpg');
                    $tricksPhoto->setCreatedAt(new \DateTimeImmutable());
                    $tricksPhoto->setFirst(true);
                    $tricksPhoto->setTricks($tricks);
                    $manager->persist($tricksPhoto);

                }

                for ($l = 0; $l < mt_rand(1, 3); $l++) {
                    $tricksVideo = new TricksVideo();
                    $tricksVideo->setPath('https://youtu.be/FuVwExvBtdo?si=OQ71FX01VW2KJQu5');
                    $tricksVideo->setCreatedAt(new \DateTimeImmutable());
                    $tricksVideo->setTricks($tricks);
                    $manager->persist($tricksVideo);

                }
            }
        }
        $manager->flush();






    }
}
