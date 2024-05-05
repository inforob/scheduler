<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Email
{
    public const SENDED = true;
    public const NOT_SENDED = false;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(length: 255)]
    private ?string $fromWho = null;

    #[ORM\Column(length: 255)]
    private ?string $toWho = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $sended = null;

    public function __construct()
    {
        $this->sended = self::NOT_SENDED;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getFromWho(): ?string
    {
        return $this->fromWho;
    }

    public function setFromWho(string $fromWho): static
    {
        $this->fromWho = $fromWho;

        return $this;
    }

    public function getToWho(): ?string
    {
        return $this->toWho;
    }

    public function setToWho(string $toWho): static
    {
        $this->toWho = $toWho;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

   #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function isSended(): ?bool
    {
        return $this->sended;
    }

    public function setSended(bool $sended): static
    {
        $this->sended = $sended;

        return $this;
    }
}
