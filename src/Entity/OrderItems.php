<?php

namespace App\Entity;

use App\Repository\OrderItemsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: OrderItemsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'ord' => 'exact'])]
class OrderItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Assert\Positive]
    #[Groups(['read', 'write'])]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn]
    #[Groups(['read', 'write'])]
    private ?Orders $ord = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        exactly: 8,
        exactMessage: 'SN is 8 bits',
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z0-9]{4}[0-9]{4}$/',
        message: 'Wrong SN format',
    )]
    private ?string $snStart = null;

    public function __toString(): string
    {
        return $this->product;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOrd(): ?Orders
    {
        return $this->ord;
    }

    public function setOrd(?Orders $ord): self
    {
        $this->ord = $ord;

        return $this;
    }

    public function getSnStart(): ?string
    {
        return $this->snStart;
    }

    public function setSnStart(string $snStart): self
    {
        $this->snStart = $snStart;

        return $this;
    }
}
