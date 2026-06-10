<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface ConfigInterface
{
    public function getRules(): Collection;
}
