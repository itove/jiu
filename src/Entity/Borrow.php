<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    paginationEnabled: false,
)]
#[ApiFilter(SearchFilter::class, properties: ['salesman' => 'exact'])]
class Borrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?User $salesman = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?Product $product = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['read', 'write'])]
    private ?int $qty = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['read'])]
    private ?int $status = 0;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['read'])]
    private ?int $used = 0;
    
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalesman(): ?User
    {
        return $this->salesman;
    }

    public function setSalesman(?User $salesman): self
    {
        $this->salesman = $salesman;

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

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUsed(): ?int
    {
        return $this->used;
    }

    public function setUsed(int $used): self
    {
        $this->used = $used;

        return $this;
    }
}
