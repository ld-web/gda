<?php

namespace App\Entity;

use App\Enum\MetadataEnum;
use App\Repository\StaplingRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaplingRuleRepository::class)]
class StaplingRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $glueOperator = null;

    #[ORM\Column(length: 255)]
    private ?string $comparisonOperator = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(type: 'string', length: 255, enumType: MetadataEnum::class)]
    private MetadataEnum $metadata;

    #[ORM\ManyToOne(inversedBy: 'rules')]
    private ?StaplingConfig $staplingConfig = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGlueOperator(): ?string
    {
        return $this->glueOperator;
    }

    public function setGlueOperator(string $glueOperator): static
    {
        $this->glueOperator = $glueOperator;

        return $this;
    }

    public function getComparisonOperator(): ?string
    {
        return $this->comparisonOperator;
    }

    public function setComparisonOperator(string $comparisonOperator): static
    {
        $this->comparisonOperator = $comparisonOperator;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getMetadata(): MetadataEnum
    {
        return $this->metadata;
    }

    public function setMetadata(MetadataEnum $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }

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
