<?php

namespace App\QueryGenerator;

use App\Entity\AbstractRule;
use App\Entity\ConfigInterface;
use App\Query\QueryBuilder;
use App\Query\QueryInterface;

abstract class AbstractConfigQueryGenerator
{
    private const VALUE_SEPARATOR = ' OU ';

    public function __construct(
        private readonly QueryBuilder $builder,
    ) {
    }

    public function generate(ConfigInterface $config): QueryInterface
    {
        $this->builder->reset();
        $rules = $config->getRules();

        foreach ($rules as $rule) {
            $this->processRule($rule);
        }

        return $this->builder->buildQuery();
    }

    protected function processRule(AbstractRule $rule): void
    {
        $values = $this->parseRuleValues($rule);

        if ($this->hasMultipleValues($values)) {
            $this->buildMultiValueQuery($rule, $values);
        } else {
            $this->buildSingleValueQuery($rule, $values[0]);
        }
    }

    protected function parseRuleValues(AbstractRule $rule): array
    {
        return explode(self::VALUE_SEPARATOR, (string) $rule->getValue());
    }

    protected function hasMultipleValues(array $values): bool
    {
        return count($values) > 1;
    }

    protected function buildMultiValueQuery(AbstractRule $rule, array $values): void
    {
        $glueOperator = $rule->getGlueOperator();
        $comparisonOperator = $rule->getComparisonOperator();

        $this->builder->$glueOperator(
            $this->builder->or(
                function () use ($comparisonOperator, $rule, $values): void {
                    foreach ($values as $value) {
                        $this->builder->$comparisonOperator($rule->getMetadata(), $value);
                    }
                }
            )
        );
    }

    protected function buildSingleValueQuery(AbstractRule $rule, string $value): void
    {
        $glueOperator = $rule->getGlueOperator();
        $comparisonOperator = $rule->getComparisonOperator();

        $this->builder->$glueOperator(
            $this->builder->$comparisonOperator($rule->getMetadata(), $value)
        );
    }
}
