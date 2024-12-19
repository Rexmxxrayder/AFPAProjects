<?php

namespace App\DataFixtures;


use App\Entity\Recipe;
use App\Enum\RecipeCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager): void
    {   
        $recipes = [];
        for($i = 0; $i < 200; $i++ ) {
            $recipes[$i] = new Recipe();
            $recipes[$i]->setTitle("Ptit Burger". $i);
            $recipes[$i]->setDescription("Ilé bon le gerbur");
            $recipes[$i]->setIngredients("1 steak, 2 bread, ketchup");
            $recipes[$i]->setPreparation("cookthe steak then : \n bread \n ketchu^p \n steak \n bread");
            $recipes[$i]->setPreparationTime(2);
            $recipes[$i]->setCookingTime(10);
            $recipes[$i]->setImageName(rand(0,1) == 1 ? 'images.jfif' : 'Smash-Burger.webp');
            $recipes[$i]->setCategory(RecipeCategory::Dessert);
            $manager->persist($recipes[$i]);
        }

        $manager->flush();
    }

}