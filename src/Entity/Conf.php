<?php

namespace App\Entity;

use App\Repository\ConfRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConfRepository::class)]
class Conf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $returnDays = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReturnDays(): ?int
    {
        return $this->returnDays;
    }

    public function setReturnDays(int $returnDays): self
    {
        $this->returnDays = $returnDays;

        return $this;
    }
}
