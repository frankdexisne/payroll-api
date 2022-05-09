<?php

namespace App\Contributions;

interface ContributionInteface
{
    public function getAmount();

    public function compute();
}
