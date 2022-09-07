<?php

namespace App\Entity;

use App\Repository\RetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetailRepository::class)]
class Retail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'retails')]
    private ?Org $store = null;

    #[ORM\ManyToOne(inversedBy: 'retails')]
    private ?Consumer $consumer = null;

    #[ORM\ManyToOne(inversedBy: 'retails')]
    private ?Product $product = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?int $voucher = null;

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
}