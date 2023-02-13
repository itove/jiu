<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoxRepository::class)]
class Box
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cipher = null;

    #[ORM\OneToMany(mappedBy: 'box', targetEntity: Bottle::class)]
    private Collection $bottles;

    #[ORM\OneToMany(mappedBy: 'box', targetEntity: Orders::class)]
    private Collection $orders;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(1000)]
    private ?int $quantity = 1000;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(10)]
    private ?int $bottleQty = 6;

    #[ORM\OneToMany(mappedBy: 'box', targetEntity: BoxPrize::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $boxPrizes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $snStart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $snEnd = null;

    #[ORM\Column(nullable: true)]
    private ?int $idStart = null;

    #[ORM\Column(nullable: true)]
    private ?int $idEnd = null;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->boxPrizes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSn(): ?string
    {
        return $this->sn;
    }

    public function setSn(?string $sn): self
    {
        $this->sn = $sn;

        return $this;
    }

    public function getCipher(): ?string
    {
        return $this->cipher;
    }

    public function setCipher(?string $cipher): self
    {
        $this->cipher = $cipher;

        return $this;
    }

    /**
     * @return Collection<int, Bottle>
     */
    public function getBottles(): Collection
    {
        return $this->bottles;
    }

    public function addBottle(Bottle $bottle): self
    {
        if (!$this->bottles->contains($bottle)) {
            $this->bottles->add($bottle);
            $bottle->setBox($this);
        }

        return $this;
    }

    public function removeBottle(Bottle $bottle): self
    {
        if ($this->bottles->removeElement($bottle)) {
            // set the owning side to null (unless already changed)
            if ($bottle->getBox() === $this) {
                $bottle->setBox(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setBox($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getBox() === $this) {
                $order->setBox(null);
            }
        }

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

    public function getBottleQty(): ?int
    {
        return $this->bottleQty;
    }

    public function setBottleQty(int $bottleQty): self
    {
        $this->bottleQty = $bottleQty;

        return $this;
    }

    /**
     * @return Collection<int, BoxPrize>
     */
    public function getBoxPrizes(): Collection
    {
        return $this->boxPrizes;
    }

    public function addBoxPrize(BoxPrize $boxPrize): self
    {
        if (!$this->boxPrizes->contains($boxPrize)) {
            $this->boxPrizes->add($boxPrize);
            $boxPrize->setBox($this);
        }

        return $this;
    }

    public function removeBoxPrize(BoxPrize $boxPrize): self
    {
        if ($this->boxPrizes->removeElement($boxPrize)) {
            // set the owning side to null (unless already changed)
            if ($boxPrize->getBox() === $this) {
                $boxPrize->setBox(null);
            }
        }

        return $this;
    }

    public function getSnStart(): ?string
    {
        return $this->snStart;
    }

    public function setSnStart(?string $snStart): self
    {
        $this->snStart = $snStart;

        return $this;
    }

    public function getSnEnd(): ?string
    {
        return $this->snEnd;
    }

    public function setSnEnd(?string $snEnd): self
    {
        $this->snEnd = $snEnd;

        return $this;
    }

    public function getIdStart(): ?int
    {
        return $this->idStart;
    }

    public function setIdStart(?int $idStart): self
    {
        $this->idStart = $idStart;

        return $this;
    }

    public function getIdEnd(): ?int
    {
        return $this->idEnd;
    }

    public function setIdEnd(?int $idEnd): self
    {
        $this->idEnd = $idEnd;

        return $this;
    }
}
