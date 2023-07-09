<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WalletTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WalletTypeRepository::class)]
class WalletType implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['wallet-type:get', 'wallet:get', 'wallet:create'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['wallet-type:get', 'wallet:get'])]
    #[
        Assert\NotNull(
            message: 'Name is required.'
        ),
        Assert\NotBlank(
            message: 'Name is required.'
        )
    ]
    private ?string $name = null;

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
}
