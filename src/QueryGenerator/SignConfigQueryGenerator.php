<?php

namespace App\QueryGenerator;

use App\Entity\AbstractRule;
use App\Entity\SignRule;

class SignConfigQueryGenerator extends AbstractConfigQueryGenerator
{
    /**
     * @param SignRule $rule
     */
    protected function processRule(AbstractRule $rule): void
    {
        if ($rule->isOptional()) {
            return;
        }

        parent::processRule($rule);
    }
}
