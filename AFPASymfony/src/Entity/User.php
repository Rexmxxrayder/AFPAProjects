<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $recipes;

    #[ORM\Column]
    private array $favorites = [];

    /**
     * @var Collection<int, Commentary>
     */
    #[ORM\OneToMany(targetEntity: Commentary::class, mappedBy: 'Author',  orphanRemoval: true)]
    private Collection $commentaries;

    /**
     * @var Collection<int, RecipeRating>
     */
    #[ORM\OneToMany(targetEntity: RecipeRating::class, mappedBy: 'Author', orphanRemoval: true)]
    private Collection $recipeRatings;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->commentaries = new ArrayCollection();
        $this->recipeRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setAuthor($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getAuthor() === $this) {
                $recipe->setAuthor(null);
            }
        }

        return $this;
    }

    public function getFavorites(): array
    {
        return $this->favorites;
    }

    public function setFavorites(array $favorites): static
    {
        $this->favorites = $favorites;

        return $this;
    }

    /**
     * @return Collection<int, Commentary>
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentary(Commentary $commentary): static
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries->add($commentary);
            $commentary->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): static
    {
        if ($this->commentaries->removeElement($commentary)) {
            // set the owning side to null (unless already changed)
            if ($commentary->getAuthor() === $this) {
                $commentary->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeRating>
     */
    public function getRecipeRatings(): Collection
    {
        return $this->recipeRatings;
    }

    public function addRecipeRating(RecipeRating $recipeRating): static
    {
        if (!$this->recipeRatings->contains($recipeRating)) {
            $this->recipeRatings->add($recipeRating);
            $recipeRating->setAuthor($this);
        }

        return $this;
    }

    public function removeRecipeRating(RecipeRating $recipeRating): static
    {
        if ($this->recipeRatings->removeElement($recipeRating)) {
            // set the owning side to null (unless already changed)
            if ($recipeRating->getAuthor() === $this) {
                $recipeRating->setAuthor(null);
            }
        }

        return $this;
    }

    public function HaveAlreadyRateThisRecipe(Recipe $recipe){
        $rates = $this->getRecipeRatings();
        foreach ($rates as $rate) {
            if($rate->getRecipe()->getId() == $recipe->getId()){
                return true;
            }
        }

        return false;
    }

    public function HaveAlreadyCommentThisRecipe(Recipe $recipe){
        $comments = $this->getCommentaries();
        foreach ($comments as $comment) {
            if($comment->getRecipe()->getId() == $recipe->getId()){
                return true;
            }
        }

        return false;
    }


    public function GetRate(Recipe $recipe){
        $rates = $this->getRecipeRatings();
        foreach ($rates as $rate) {
            if($rate->getRecipe()->getId() == $recipe->getId()){
                return $rate->getRate();
            }
        }

        return 0;
    }
}
