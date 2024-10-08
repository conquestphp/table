<?php

declare(strict_types=1);

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\HasValue;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Concerns\Transforms;
use Conquest\Core\Primitive;
use Conquest\Table\Contracts\Filters;
use Illuminate\Support\Facades\Request;

abstract class BaseFilter extends Primitive implements Filters
{
    use HasLabel;
    use HasMeta;
    use HasName;
    use HasType;
    use HasValue;
    use IsActive;
    use IsAuthorized;
    use Transforms;

    public function __construct(string|Closure $name, string|Closure|null $label = null)
    {
        parent::__construct();
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
    }

    /**
     * From the current request, get the value of the filter name
     */
    public function getValueFromRequest(): mixed
    {
        return Request::input($this->getName(), null);
    }

    /**
     * Determine if the filter should be applied.
     */
    public function filtering(mixed $value): bool
    {
        return ! is_null($value);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'active' => $this->isActive(),
            'value' => $this->getValue(),
            'meta' => $this->getMeta(),
        ];
    }
}
