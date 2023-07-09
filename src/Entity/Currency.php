<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[UniqueEntity('code', message: 'There is already a currency with the code {{ value }}', groups: ['currency:create', 'currency:update'])]
class Currency implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['currency:get', 'wallet:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['currency:get', 'wallet:get'])]
    #[
        Assert\NotNull(
            message: 'Name is required.',
            groups: ['currency:create']
        ),
        Assert\NotBlank(
            message: 'Name is required.',
            groups: ['currency:create']
        )
    ]
    private ?string $name = null;

    #[ORM\Column(length: 3, unique: true)]
    #[Groups(['currency:get', 'wallet:get'])]
    #[
        Assert\NotNull(
            message: 'Code is required.',
            groups: ['currency:create']
        ),
        Assert\NotBlank(
            message: 'Code is required.',
            groups: ['currency:create']
        ),
        Assert\Currency(
            message: 'Code must have exactly 3 letters.',
            groups: ['currency:create']
        )
    ]
    private ?string $code = null;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
