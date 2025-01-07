<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["Station:read ", "FoundObject:read"])]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(["Station:read ", "FoundObject:read"])]
    
    private ?string $name = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["Station:read ", "FoundObject:read"])]
    private ?string $localisation = null;
    
    /**
     * @var Collection<int, FoundObject>
     */
    #[ORM\OneToMany(targetEntity: FoundObject::class, mappedBy: 'station', orphanRemoval: true)]
    #[Groups(['Station:read'])]
    private Collection $foundObjects;

    public function __construct()
    {
        $this->foundObjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection<int, FoundObject>
     */
    public function getFoundObjects(): Collection
    {
        return $this->foundObjects;
    }

    public function addFoundObject(FoundObject $foundObject): static
    {
        if (!$this->foundObjects->contains($foundObject)) {
            $this->foundObjects->add($foundObject);
            $foundObject->setStation($this);
        }

        return $this;
    }

    public function removeFoundObject(FoundObject $foundObject): static
    {
        if ($this->foundObjects->removeElement($foundObject)) {
            // set the owning side to null (unless already changed)
            if ($foundObject->getStation() === $this) {
                $foundObject->setStation(null);
            }
        }

        return $this;
    }
}
