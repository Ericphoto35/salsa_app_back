<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <=20; $i++) {
        $user = (new User())
            ->setNom("Nom $i")
            ->setCours("Cours $i")
            ->setEmail("email.$i@rico.fr");
        
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));

        $manager->persist($user);
        $manager->flush();

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
}