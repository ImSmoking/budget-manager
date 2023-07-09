<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WalletRepository;
use App\Trait\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Wallet implements EntityInterface
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['wallet:get'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'wallets')]
    #[Groups(['wallet:get', 'wallet:create'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Groups(['wallet:get', 'wallet:create'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 65, scale: 2)]
    #[Groups(['wallet:get', 'wallet:create'])]
    private ?string $balance = '0.00';

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: CashFlow::class, orphanRemoval: true)]
    private Collection $cashFlows;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['wallet:get', 'wallet:create'])]
    private ?Currency $currency = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['wallet:get', 'wallet:create'])]
    private ?WalletType $type = null;

    public function __construct()
    {
        $this->cashFlows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
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

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return Collection<int, CashFlow>
     */
    public function getCashFlows(): Collection
    {
        return $this->cashFlows;
    }

    public function addCashFlow(CashFlow $cashFlow): static
    {
        if (!$this->cashFlows->contains($cashFlow)) {
            $this->cashFlows->add($cashFlow);
            $cashFlow->setWallet($this);
        }

        return $this;
    }

    public function removeCashFlow(CashFlow $cashFlow): static
    {
        if ($this->cashFlows->removeElement($cashFlow)) {
            // set the owning side to null (unless already changed)
            if ($cashFlow->getWallet() === $this) {
                $cashFlow->setWallet(null);
            }
        }

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getType(): ?WalletType
    {
        return $this->type;
    }

    public function setType(?WalletType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
