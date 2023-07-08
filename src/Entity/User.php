<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use App\Trait\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message: 'There is already a user with the email {{ value }}', groups: ['register'])]
#[UniqueEntity('username', message: 'There is already a user with the username {{ value }}', groups: ['register'])]
#[ORM\HasLifecycleCallbacks]
class User implements EntityInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['register', 'login', 'get'])]
    #[
        Assert\NotNull(
            message: 'Username is required.',
            groups: ['register']
        ),
        Assert\NotBlank(
            message: 'Username is required.',
            groups: ['register']
        )
    ]
    private ?string $username = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['register', 'get'])]
    #[
        Assert\Email(
            message: 'Provided email {{ value }} is not a valid email.',
            groups: ['register']
        ),
        Assert\NotNull(
            message: 'Email is required.',
            groups: ['register']
        ),
        Assert\NotBlank(
            message: 'Email is required.',
            groups: ['register']
        )
    ]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['login'])]
    private ?string $password = null;

    #[Groups(['register'])]
    #[
        Assert\PasswordStrength(
            minScore: Assert\PasswordStrength::STRENGTH_WEAK,
            groups: ['register']
        ),
        Assert\NotNull(
            message: 'Password is required.',
            groups: ['register']
        ),
        Assert\NotBlank(
            message: 'Password is required.',
            groups: ['register']
        )
    ]
    private ?string $rawPassword = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Wallet::class)]
    private Collection $wallets;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    public function setRawPassword(?string $rawPassword): static
    {
        $this->rawPassword = $rawPassword;

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
     * @return Collection<int, Wallet>
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    public function addWallet(Wallet $wallet): static
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets->add($wallet);
            $wallet->setUser($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): static
    {
        if ($this->wallets->removeElement($wallet)) {
            // set the owning side to null (unless already changed)
            if ($wallet->getUser() === $this) {
                $wallet->setUser(null);
            }
        }

        return $this;
    }
}
