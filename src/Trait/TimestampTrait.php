<?php

declare(strict_types=1);

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeImmutable;

trait TimestampTrait
{
    #[Groups(['timestamp', 'created_at'])]
    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[Groups(['timestamp', 'updated_at'])]
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function onCreate(): self
    {
        $this->createdAt = new DateTimeImmutable('now');
        $this->updatedAt = new DateTimeImmutable('now');

        return $this;
    }

    #[ORM\PreUpdate]
    public function onUpdate(): self
    {
        $this->updatedAt = new DateTimeImmutable('now');

        return $this;
    }

}