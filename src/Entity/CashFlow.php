<?php

declare(strict_types=1);

namespace App\Entity;

use App\Constant\CashFlowTypes;
use App\Repository\CashFlowRepository;
use App\Trait\TimestampTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CashFlowRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CashFlow implements EntityInterface
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cash_flow:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Groups(['cash_flow:get', 'cash_flow:create'])]
    #[
        Assert\NotNull(
            message: 'Type is required.',
            groups: ['cash_flow:create']
        ),
        Assert\NotBlank(
            message: 'Type is required.',
            groups: ['cash_flow:create']
        ),
        Assert\Choice(
            choices: CashFlowTypes::TYPES_ARRAY,
            message: 'Choose a valid Cash Flow type.',
            groups: ['cash_flow:create']
        )
    ]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'cashFlows')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cash_flow:get', 'cash_flow:create'])]
    #[
        Assert\NotNull(
            message: 'Wallet is required.',
            groups: ['cash_flow:create']
        )
    ]
    private ?Wallet $wallet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['cash_flow:get', 'cash_flow:create'])]
    #[
        Assert\NotNull(
            message: 'Description is required.',
            groups: ['cash_flow:create']
        ),
        Assert\NotBlank(
            message: 'Description is required.',
            groups: ['cash_flow:create']
        )
    ]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 65, scale: 2)]
    #[Groups(['cash_flow:get', 'cash_flow:create'])]
    #[
        Assert\Type(
            type: 'digit',
            message: 'Balance is not a valid decimal number.',
            groups: ['wallet:create']
        )
    ]
    private ?string $amount = '0.00';

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cash_flow:get', 'cash_flow:create'])]
    #[
        Assert\NotNull(
            message: 'Category is required.',
            groups: ['cash_flow:create']
        )
    ]
    private ?Category $category = null;

    #[ORM\Column]
    #[Groups(['cash_flow:get', 'cash_flow:create'])]
    #[
        Assert\NotNull(
            message: 'Dated At is required.',
            groups: ['cash_flow:create']
        ),
        Assert\NotBlank(
            message: 'Dated At is required.',
            groups: ['cash_flow:create']
        )
    ]
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
