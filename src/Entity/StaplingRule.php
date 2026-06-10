<?php

namespace App\Entity;

use App\Repository\StaplingRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaplingRuleRepository::class)]
class StaplingRule extends AbstractRule
{
    #[ORM\ManyToOne(inversedBy: 'rules')]
    private ?StaplingConfig $staplingConfig = null;

    public function getStaplingConfig(): ?StaplingConfig
    {
        return $this->staplingConfig;
    }

    public function setStaplingConfig(?StaplingConfig $staplingConfig): static
    {
        $this->staplingConfig = $staplingConfig;

        return $this;
    }
}
