<?php

namespace App\Entity;

use App\Repository\SignConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignConfigRepository::class)]
class SignConfig implements ConfigInterface
{
    use EntityIdTrait;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, SignRule>
     */
    #[ORM\OneToMany(targetEntity: SignRule::class, mappedBy: 'signConfig', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
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
     * @return Collection<int, SignRule>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(SignRule $rule): static
    {
        if (!$this->rules->contains($rule)) {
            $this->rules->add($rule);
            $rule->setSignConfig($this);
        }

        return $this;
    }

    public function removeRule(SignRule $rule): static
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getSignConfig() === $this) {
                $rule->setSignConfig(null);
            }
        }

        return $this;
    }
}
