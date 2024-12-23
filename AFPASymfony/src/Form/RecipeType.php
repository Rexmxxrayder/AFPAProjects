<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Enum\RecipeCategory;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4,
                        'max' => 255,
                        'minMessage' => 'At least {{ limit }} character for the title pls',
                        'maxMessage' => 'No more than {{ limit }} character for the title pls',
                    ]),
                ]
            ])
            ->add('description')
            ->add('ingredients', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('preparation', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('preparationTime', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'Preparation Duration too low'
                    ]),
                    new LessThanOrEqual([
                        'value' => 600,
                        'message' => 'Preparation Duration too high'
                    ]),
                ]
            ])
            ->add('cookingTime', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Cooking Duration too low'
                    ]),
                    new LessThanOrEqual([
                        'value' => 600,
                        'message' => 'Cooking Duration too high'
                    ]),
                ]
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Appetizer' => RecipeCategory::Appetizer,
                    'Main Course' => RecipeCategory::MainCourse,
                    'Dessert' => RecipeCategory::Dessert,
                    'Beverage' => RecipeCategory::Beverage,
                    'Other' => RecipeCategory::Other,
                ],
                'choice_value' => function (?RecipeCategory $category) {
                    return $category ? $category->value : null;
                },
                'attr' => ['class' => 'form-control'],
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('isPrivate', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
