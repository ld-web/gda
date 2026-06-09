<?php

namespace App\Entity;

use App\Repository\StaplingConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaplingConfigRepository::class)]
class StaplingConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, StaplingRule>
     */
    #[ORM\OneToMany(targetEntity: StaplingRule::class, mappedBy: 'staplingConfig', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, StaplingRule>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(StaplingRule $rule): static
    {
        if (!$this->rules->contains($rule)) {
            $this->rules->add($rule);
            $rule->setStaplingConfig($this);
        }

        return $this;
    }

    public function removeRule(StaplingRule $rule): static
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getStaplingConfig() === $this) {
                $rule->setStaplingConfig(null);
            }
        }

        return $this;
    }
}
