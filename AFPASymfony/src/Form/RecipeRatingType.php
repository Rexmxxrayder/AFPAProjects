<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\RecipeRating;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeRatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate')
            ->add('Author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('Recipe', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeRating::class,
        ]);
    }
}
