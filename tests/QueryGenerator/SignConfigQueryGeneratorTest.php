<?php

namespace App\Tests\QueryGenerator;

use App\Entity\SignConfig;
use App\Entity\SignRule;
use App\Enum\MetadataEnum;
use App\Query\Query;
use App\Query\QueryBuilder;
use App\QueryGenerator\SignConfigQueryGenerator;
use PHPUnit\Framework\TestCase;

class SignConfigQueryGeneratorTest extends TestCase
{
    public function testOptionalRuleIsIgnored(): void
    {
        $signConfig = new SignConfig();
        $signConfig->setSlug('test');

        $rule = new SignRule();
        $rule->setGlueOperator('AND');
        $rule->setComparisonOperator('eq');
        $rule->setValue('test');
        $rule->setMetadata(MetadataEnum::TYPE_DOC);
        $rule->setOptional(true);

        $signConfig->addRule($rule);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())
            ->method('reset')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('buildQuery')
            ->willReturn((new Query())->setQuery(''));

        $queryGenerator = new SignConfigQueryGenerator($queryBuilder);
        $query = $queryGenerator->generate($signConfig);
        $this->assertEquals('', $query->getQuery());
    }
}
