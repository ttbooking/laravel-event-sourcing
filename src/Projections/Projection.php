<?php

namespace Spatie\EventSourcing\Projections;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $parameters = [])
 */
abstract class Projection extends Model
{
    private bool $isWriteable = false;

    protected static function boot(): void
    {
        parent::boot();

        static::observe(ProjectionObserver::class);
    }

    public static function new(): static
    {
        return new static();
    }

    public function writeable(): static
    {
        $clone = clone $this;

        $clone->isWriteable = true;

        return $clone;
    }

    public function isWriteable(): bool
    {
        return $this->isWriteable;
    }

    public function refresh(): static
    {
        $this->isWriteable = false;

        return parent::refresh();
    }

    public function fresh($with = []): ?static
    {
        $this->isWriteable = false;

        return parent::fresh($with);
    }

    public function newInstance($attributes = [], $exists = false): static
    {
        $instance = parent::newInstance($attributes, $exists);

        $instance->isWriteable = $this->isWriteable;

        return $instance;
    }
}