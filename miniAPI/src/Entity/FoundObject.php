<?php

namespace App\Entity;

use App\Enum\ObjectStatusEnum;
use App\Repository\FoundObjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FoundObjectRepository::class)]
class FoundObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["Station:read", "FoundObject:read"])]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["Station:read", "FoundObject:read"])]
    private ?string $description = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["Station:read", "FoundObject:read"])]
    private ?string $localisation = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["Station:read", "FoundObject:read"])]
    private ?\DateTimeInterface $reportDate = null;
    
    #[ORM\Column(enumType: ObjectStatusEnum::class)]
    #[Groups(["Station:read", "FoundObject:read"])]
    private ObjectStatusEnum $status = ObjectStatusEnum::Found;
    
    #[ORM\ManyToOne(inversedBy: 'foundObjects')]
    #[ORM\JoinColumn(nullable: false, name:"station_id", referencedColumnName:"id")]
    #[Groups(['FoundObject:read'])]
    private ?Station $station = null;

    public function getStatus(): ObjectStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ObjectStatusEnum $status): self
    {
        $this->status = $status;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getReportDate(): ?\DateTimeInterface
    {
        return $this->reportDate;
    }

    public function setReportDate(\DateTimeInterface $reportDate): static
    {
        $this->reportDate = $reportDate;

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): static
    {
        $this->station = $station;

        return $this;
    }
}
