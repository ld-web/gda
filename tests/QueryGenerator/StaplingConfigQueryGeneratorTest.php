<?php

namespace App\Tests\QueryGenerator;

use App\Entity\StaplingConfig;
use App\Entity\StaplingRule;
use App\Enum\MetadataEnum;
use App\Query\Query;
use App\Query\QueryBuilder;
use App\QueryGenerator\StaplingConfigQueryGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StaplingConfigQueryGeneratorTest extends KernelTestCase
{
    public function testSingleValueQueryGeneration(): void
    {
        $staplingConfig = new StaplingConfig();
        $staplingConfig->setSlug('test');

        $rule = new StaplingRule();
        $rule->setGlueOperator('AND');
        $rule->setComparisonOperator('eq');
        $rule->setValue('test');
        $rule->setMetadata(MetadataEnum::TYPE_DOC);

        $staplingConfig->addRule($rule);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())
            ->method('reset')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('buildQuery')
            ->willReturn((new Query())->setQuery('type_doc = "test"'));

        $queryGenerator = new StaplingConfigQueryGenerator($queryBuilder);
        $query = $queryGenerator->generate($staplingConfig);
        $this->assertEquals('type_doc = "test"', $query->getQuery());
    }

    public function testMultiValueQueryGeneration(): void
    {
        $staplingConfig = new StaplingConfig();
        $staplingConfig->setSlug('test');

        $rule = new StaplingRule();
        $rule->setGlueOperator('AND');
        $rule->setComparisonOperator('eq');
        $rule->setValue('test OU test2');
        $rule->setMetadata(MetadataEnum::TYPE_DOC);

        $staplingConfig->addRule($rule);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())
            ->method('reset')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('buildQuery')
            ->willReturn((new Query())->setQuery('type_doc = "test" OR type_doc = "test2"'));

        $queryGenerator = new StaplingConfigQueryGenerator($queryBuilder);
        $query = $queryGenerator->generate($staplingConfig);
        $this->assertEquals('type_doc = "test" OR type_doc = "test2"', $query->getQuery());
    }
}
