<?php

namespace App\Query;

class Query implements QueryInterface, \Stringable
{
    private string $query;

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function __toString(): string
    {
        return $this->query;
    }
}
