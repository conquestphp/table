<?php

namespace Jdw5\Vanguard\Filters\Concerns;

use Closure;
use Jdw5\Vanguard\Refining\Filters\Exceptions\InvalidQueryException;

trait HasQuery
{
    protected Closure $query;

    public function query(Closure $query): static
    {
        $this->setQuery($query);
        return $this;
    }

    public function using(Closure $query): static
    {
        return $this->query($query);
    }

    protected function setQuery(Closure|null $query): void
    {
        if (is_null($query)) return;
        $this->query = $query;
    }

    public function getQuery(): Closure
    {
        if (!isset($this->query)) throw InvalidQueryException::missing();
        return $this->query;
    }
}