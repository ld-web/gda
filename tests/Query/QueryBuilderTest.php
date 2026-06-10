<?php

namespace App\Tests\App\Tests\Query;

use App\Enum\MetadataEnum;
use App\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testEmptyOnConstruction(): void
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('', $query->getQuery());
    }

    public function testEq(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->eq('name', 'John');
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name = "John"', $query->getQuery());
    }

    public function testNeq(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->neq('name', 'John');
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name <> "John"', $query->getQuery());
    }

    public function testGt(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->gt('name', 'John');
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name > "John"', $query->getQuery());
    }

    public function testLt(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->lt('name', 'John');
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name < "John"', $query->getQuery());
    }

    public function testLte(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->lte('name', 'John');
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name <= "John"', $query->getQuery());
    }

    public function testGte(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->gte('name', 'John');
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name >= "John"', $query->getQuery());
    }

    public function testReset(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->eq('name', 'John');
        $queryBuilder->reset();
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('', $query->getQuery());
    }

    public function testSingleStatement(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->and($queryBuilder->eq('name', 'John'));
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name = "John"', $query->getQuery());
    }

    public function testMultipleStatements(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->eq('name', 'John');
        $queryBuilder->and($queryBuilder->eq('age', 30));
        $queryBuilder->or($queryBuilder->eq('city', 'Paris'));
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name = "John" AND age = 30 OR city = "Paris"', $query->getQuery());
    }

    public function testMultipleFormats()
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->eq('name', 'John');
        $queryBuilder->and($queryBuilder->eq('active', true));
        $queryBuilder->and($queryBuilder->gt('registration_date', new \DateTime('2026-01-01')));
        $queryBuilder->and($queryBuilder->neq(MetadataEnum::DOMAIN, 'domain'));
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name = "John" AND active = true AND registration_date > 01/01/2026 AND domaine <> "domain"', $query->getQuery());
    }

    public function testCombineStatements(): void
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->eq('name', 'John');
        $queryBuilder->and(
            $queryBuilder->or(
                $queryBuilder->eq('type_doc', 'FACTURE'),
                $queryBuilder->eq('type_doc', 'BON'),
            )
        );
        $query = $queryBuilder->buildQuery();
        $this->assertEquals('name = "John" AND (type_doc = "BON" OR type_doc = "FACTURE")', $query->getQuery());
    }
}
