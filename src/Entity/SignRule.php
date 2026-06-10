<?php

namespace App\Entity;

use App\Repository\SignRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignRuleRepository::class)]
class SignRule extends AbstractRule
{
    #[ORM\Column]
    private ?bool $optional = null;

    #[ORM\ManyToOne(inversedBy: 'rules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SignConfig $signConfig = null;

    public function isOptional(): ?bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): static
    {
        $this->optional = $optional;

        return $this;
    }

    public function getSignConfig(): ?SignConfig
    {
        return $this->signConfig;
    }

    public function setSignConfig(?SignConfig $signConfig): static
    {
        $this->signConfig = $signConfig;

        return $this;
    }
}
