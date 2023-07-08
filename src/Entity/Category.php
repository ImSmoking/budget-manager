<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['create', 'get'])]
    #[ORM\Column(length: 255)]
    #[
        Assert\NotNull(
            message: 'Name is required.',
            groups: ['create']
        ),
        Assert\NotBlank(
            message: 'Name is required.',
            groups: ['create']
        )
    ]
    private ?string $name = null;

    #[Groups(['create', 'get'])]
    #[ORM\Column(length: 9, nullable: true)]
    #[
        Assert\NotNull(
            message: 'Name is required.',
            groups: ['create']
        ),
        Assert\NotBlank(
            message: 'Name is required.',
            groups: ['create']
        )
    ]
    private ?string $colorHex = null;

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

    public function getColorHex(): ?string
    {
        return $this->colorHex;
    }

    public function setColorHex(?string $colorHex): static
    {
        $this->colorHex = $colorHex;

        return $this;
    }
}