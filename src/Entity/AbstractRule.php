<?php

namespace App\Entity;

use App\Enum\MetadataEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractRule
{
    use EntityIdTrait;

    #[ORM\Column(length: 255)]
    protected ?string $glueOperator = null;

    #[ORM\Column(length: 255)]
    protected ?string $comparisonOperator = null;

    #[ORM\Column(length: 255)]
    protected ?string $value = null;

    #[ORM\Column(type: 'string', length: 255, enumType: MetadataEnum::class)]
    protected MetadataEnum $metadata;

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
}
