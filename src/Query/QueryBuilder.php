<?php

namespace App\Query;

use Symfony\Component\String\UnicodeString;

class QueryBuilder
{
    private array $statements = [];

    public function reset(): self
    {
        $this->statements = [];

        return $this;
    }

    public function eq($column, $value): self
    {
        $this->addStatement("=", $column, $value);
        return $this;
    }

    public function neq($column, $value): self
    {
        $this->addStatement("<>", $column, $value);
        return $this;
    }

    public function gt($column, $value): self
    {
        $this->addStatement(">", $column, $value);
        return $this;
    }

    public function gte($column, $value): self
    {
        $this->addStatement(">=", $column, $value);
        return $this;
    }

    public function lt($column, $value): self
    {
        $this->addStatement("<", $column, $value);
        return $this;
    }

    public function lte($column, $value): self
    {
        $this->addStatement("<=", $column, $value);
        return $this;
    }

    public function and(...$statements): self
    {
        $separator = " AND ";

        $this->glue($statements, $separator);

        return $this;
    }

    public function or(...$statements): self
    {
        $separator = " OR ";

        $this->glue($statements, $separator);

        return $this;
    }

    public function buildQuery(): QueryInterface
    {
        if (!empty($this->statements)) {
            $firstStatement = trim(new UnicodeString($this->statements[0]));

            $firstStatement = explode(' ', $firstStatement);

            if ($firstStatement[0] === "AND" || $firstStatement[0] === "OR") {
                unset($firstStatement[0]);
            }

            $this->statements[0] = implode(' ', $firstStatement);
        }

        return (new Query())->setQuery(implode("", $this->statements));
    }

    private function fetchFormat(string $operator, $value): string
    {
        $format = '%s ' . $operator . ' ';
        if ($value instanceof \DateTime) {
           return $format . '%s';
        }
        if (is_bool($value)) {
            return $format . '%s';
        }
        return $format . (is_numeric($value) ? '%s' : '"%s"');
    }

    private function addStatement(string $operator, $column, $value): void
    {
        if ($column instanceof \BackedEnum) {
            $column = $column->value;
        }

        $format = $this->fetchFormat($operator, $value);
        if ($value instanceof \DateTime) {
            $this->statements[] = sprintf($format, $column, $value->format('m/d/Y'));
        } else {

            if (is_bool($value)) {
                $value = $value ? "true" : "false";
            }
            $this->statements[] = sprintf($format, $column, $value);
        }
    }

    private function glue(array $statements, string $separator): void
    {
        if (count($statements) === 1 && !is_callable($statements[0])) {
            $this->addStatementOperator($separator);
        } else {
            $this->combine($separator, $statements);
        }
    }

    /**
     * Add separator to last statement
     */
    private function addStatementOperator(string $separator): void
    {
        $statement = array_pop($this->statements);

        $this->statements[] = sprintf(" %s %s", trim($separator), $statement);
    }

    /**
     * Combine statements with given separator.
     *
     * Allowed statements are :
     *     - QueryBuilder object : take many as given object last statement
     *     - QueryInterface object : load object query string
     *     - string : take given string
     *
     * Non allowed statements are skipped
     */
    private function combine(string $separator, array $statements): void
    {
        foreach ($statements as $index => $statement) {
            if ($statement instanceof QueryInterface) {
                $queries[] = $statement->getQuery();
                unset($statements[$index]);
            }
            elseif (is_string($statement)) {
                $queries[] = $statement;
                unset($statements[$index]);
            }
            elseif (is_callable($statement)) {
                $actualStatements = $this->statements;
                $statement();
                $queries = array_diff($this->statements, $actualStatements);
                $this->statements = $actualStatements;
                unset($statements[$index]);
            }
            elseif (!$statement instanceof self) {
                unset($statements[$index]);
            }
        }

        $statementsNumber = count($statements);
        $storedStatementsNumber = count($this->statements);
        $lastPopIndex = $storedStatementsNumber - $statementsNumber;

        $toCombineStatements = [];
        for ($i = $storedStatementsNumber; $i > $lastPopIndex; $i--) {
            $toCombineStatements[] = array_pop($this->statements);
        }

        $toCombine = array_merge($toCombineStatements, $queries ?? []);

        $this->statements[] = sprintf("(%s)", implode($separator, $toCombine));
    }
}
