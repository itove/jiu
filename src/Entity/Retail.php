<?php

namespace App\Entity;

use App\Repository\RetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: RetailRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'store' => 'exact', 'consumer' => 'exact'])]
class Retail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'retails')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Org $store = null;

    #[ORM\ManyToOne(inversedBy: 'retails')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Consumer $consumer = null;

    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Product $product = null;

    #[ORM\Column(type: Types::SMALLINT, options: ["unsigned" => true])]
    #[Assert\Positive]
    #[Groups(['read'])]
    private ?int $quantity = null;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Groups(['read'])]
    private ?int $amount = 0;

    #[ORM\Column(options: ["unsigned" => true])]
    #[Groups(['read'])]
    private ?int $voucher = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read'])]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStore(): ?Org
    {
        return $this->store;
    }

    public function setStore(?Org $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getConsumer(): ?Consumer
    {
        return $this->consumer;
    }

    public function setConsumer(?Consumer $consumer): self
    {
        $this->consumer = $consumer;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getVoucher(): ?int
    {
        return $this->voucher;
    }

    public function setVoucher(int $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
