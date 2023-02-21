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

    #[ORM\ManyToOne]
    private ?Batch $batch = null;

    #[ORM\Column]
    private ?int $bid = null;

    #[ORM\Column(length: 255)]
    private ?string $cipher = null;

    #[ORM\OneToMany(mappedBy: 'box', targetEntity: Bottle::class, orphanRemoval: true)]
    private Collection $bottles;

    #[ORM\ManyToOne(inversedBy: 'boxes')]
    private ?Org $org = null;

    #[ORM\ManyToOne(inversedBy: 'boxes')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'boxes')]
    private ?OrderItems $orderItems = null;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->sn;
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

    public function getBatch(): ?Batch
    {
        return $this->batch;
    }

    public function setBatch(?Batch $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBid(): ?int
    {
        return $this->bid;
    }

    public function setBid(int $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getCipher(): ?string
    {
        return $this->cipher;
    }

    public function setCipher(string $cipher): self
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

    public function getOrg(): ?Org
    {
        return $this->org;
    }

    public function setOrg(?Org $org): self
    {
        $this->org = $org;

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

    public function getOrderItems(): ?OrderItems
    {
        return $this->orderItems;
    }

    public function setOrderItems(?OrderItems $orderItems): self
    {
        $this->orderItems = $orderItems;

        return $this;
    }
}
