<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\HasFallback;
use Conquest\Table\Columns\Concerns\IsSearchable;

abstract class FallbackColumn extends BaseColumn
{
    use HasFallback {
        getFallback as protected getFallbackAttribute;
    }
    use IsSearchable;

    protected function defaultFallback(): mixed
    {
        return config('table.fallback.default', null);
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) {
            return $this->getFallback();
        }

        return parent::apply($value);
    }

    public function getFallback()
    {
        return $this->getFallbackAttribute() ?? $this->defaultFallback();
    }
}
