<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Options\Concerns\HasOptions;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Filters\Concerns\HasMultiple;
use Conquest\Table\Filters\Concerns\IsRestrictable;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;

class SelectFilter extends Filter
{
    use HasMultiple;
    use HasOptions;
    use IsRestrictable;

    public function __construct(
        array|string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
        bool $multiple = false,
        array $options = [],
        bool|Closure $restrict = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $clause, $operator, $negate);
        if ($multiple) $this->multiple();
        $this->setOptions($options);
        $this->setRestricted($restrict);
        $this->setType('filter:select');

    }

    public static function make(
        array|string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
        bool $multiple = false,
        array $options = [],
        bool|Closure $restrict = null,
    ): static {
        return new static($property, $name, $label, $authorize, $clause, $operator, $negate, $multiple, $options, $restrict);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        $queryValue = $request->query($this->getName());
        if ($this->hasMultiple() && $this->getClause()->isMultiple()) $queryValue = $this->splitToMultiple($queryValue);

        $transformedValue = $this->transformUsing($queryValue);
        $this->setValue($transformedValue);
        $this->setActive($this->filtering($request));

        $optionExists = false;

        foreach ($this->getOptions() as $option) {
            $isActive = $option->hasValue($this->getValue(), $this->isMultiple());
            $option->setActive($isActive);
            $optionExists = $optionExists || $isActive;
        }

        if ($this->hasOptions() && $this->isRestricted() && !$optionExists) return;

        $builder->when(
            $this->isActive() && $this->isValid($transformedValue),
            fn (Builder|QueryBuilder $builder) => $this->getClause()
                ->apply($builder, 
                    $this->getProperty(), 
                    $this->isNegated() ? $this->getOperator()->negate() : $this->getOperator(), 
                    $this->getValue()
                )
        );
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->hasMultiple(),
            'options' => $this->getOptions(),
        ]);
    }

    public function multiple(): static
    {
        $this->setClause(Clause::CONTAINS);
        return parent::multiple();
    }
}