<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\Operator;

trait HasOperator
{
    protected ?Operator $operator = null;

    /**
     * Set the operator to be used, chainable.
     */
    public function operator(string|Operator $operator): static
    {
        $this->setOperator($operator);

        return $this;
    }

    /**
     * Set the operator to be used quietly.
     */
    public function setOperator(string|Operator|null $operator): void
    {
        if (is_null($operator)) {
            return;
        }

        if ($operator instanceof Operator) {
            $this->operator = $operator;
        } else {
            $this->operator = Operator::tryFrom($operator);
        }
    }

    /**
     * Get the operator to be used.
     */
    public function getOperator(): ?Operator
    {
        return $this->evaluate($this->operator);
    }

    /**
     * Set the operator to be '>'.
     */
    public function gt(): static
    {
        $this->setOperator(Operator::GREATER_THAN);

        return $this;
    }

    /**
     * Set the operator to be '>='.
     */
    public function gte(): static
    {
        $this->setOperator(Operator::GREATER_THAN_OR_EQUAL);

        return $this;
    }

    /**
     * Set the operator to be '<'.
     */
    public function lt(): static
    {
        $this->setOperator(Operator::LESS_THAN);

        return $this;
    }

    /**
     * Set the operator to be '<='.
     */
    public function lte(): static
    {
        $this->setOperator(Operator::LESS_THAN_OR_EQUAL);

        return $this;
    }

    /**
     * Set the operator to be '='.
     */
    public function eq(): static
    {
        $this->setOperator(Operator::EQUAL);

        return $this;
    }

    /**
     * Set the operator to be '!='.
     */
    public function neq(): static
    {
        $this->setOperator(Operator::NOT_EQUAL);

        return $this;
    }

    // Alias
    public function equals(): static
    {
        return $this->eq();
    }

    public function equal(): static
    {
        return $this->eq();
    }

    public function notEqual(): static
    {
        return $this->neq();
    }

    public function greaterThan(): static
    {
        return $this->gt();
    }

    public function greaterThanOrEqual(): static
    {
        return $this->gte();
    }

    public function lessThan(): static
    {
        return $this->lt();
    }

    public function lessThanOrEqual(): static
    {
        return $this->lte();
    }

    public function fuzzy(): static
    {
        $this->setOperator(Operator::LIKE);

        return $this;
    }

    public function greater(): static
    {
        return $this->gt();
    }

    public function less(): static
    {
        return $this->lt();
    }
}
