<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Recipe;
use App\Entity\User;
use App\Enum\RecipeCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    // Injection du service UserPasswordHasherInterface via le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setFirstName('John');
        $user->setsurname('Doe');
        $user->setEmail('JohnDoe@gmail.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'ffffffff'));

        $user2 = new User();
        $user2->setFirstName('F');
        $user2->setsurname('F');
        $user2->setEmail('F@gmail.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user, 'ffffffff'));

        $user3 = new User();
        $user3->setFirstName('Admin');
        $user3->setsurname('Admin');
        $user3->setEmail('Admin@gmail.com');
        $user3->setPassword($this->passwordHasher->hashPassword($user, 'Admin'));
        $user3->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);

        $recipes = [];
        for ($i = 0; $i < 200; $i++) {
            $recipes[$i] = new Recipe();
            $isBurger = rand(0,1) == 0;
            $recipes[$i]->setTitle(($isBurger ? "Ptit Burger" : "Big La Sagne"). $i);
            $recipes[$i]->setDescription($isBurger ? "Ilé bon le gerbur" : "La Sagne");
            $recipes[$i]->setIngredients($isBurger ? "1 steak, 2 bread, ketchup" : "1Kg Pasta, 1Kg horse, 1Kg Tomato, 1KG Salt");
            $recipes[$i]->setPreparation($isBurger ? "cook the steak then : \n bread \n ketchu^p \n steak \n bread" : "Mix all ingredients");
            $recipes[$i]->setPreparationTime(rand(10,50));
            $recipes[$i]->setCookingTime(rand(10,50));
            $recipes[$i]->setImageName($isBurger ? 'Smash-Burger.png' : 'Lasagna.jpg');
            $recipes[$i]->setCategory(RecipeCategory::cases()[array_rand(RecipeCategory::cases())]);
            $recipes[$i]->setAuthor(rand(0, 1) == 1  ? $user : $user2);
            $recipes[$i]->setPrivate(rand(0, 1));
            $manager->persist($recipes[$i]);
        }

        $manager->flush();
    }
}
