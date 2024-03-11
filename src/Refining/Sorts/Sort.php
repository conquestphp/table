<?php

namespace Jdw5\Vanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;

class Sort extends BaseSort
{
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'direction' => $this->getDirection(),
        ]);
    }



}