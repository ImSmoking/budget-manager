<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CashFlowRepository;
use App\Trait\TimestampTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CashFlowRepository::class)]
class CashFlow implements EntityInterface
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'cashFlows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 65, scale: 2)]
    private ?string $amount = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
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

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDatedAt(): ?\DateTimeImmutable
    {
        return $this->datedAt;
    }

    public function setDatedAt(\DateTimeImmutable $datedAt): static
    {
        $this->datedAt = $datedAt;

        return $this;
    }
}
