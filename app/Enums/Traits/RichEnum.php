<?php

namespace App\Enums\Traits;

use Illuminate\Support\Collection;

trait RichEnum
{
    public static function options(): Collection
    {
        return collect(static::cases())->mapWithKeys(fn ($c) => [
            $c->value => ucfirst($c->value),
        ]);
    }

    public static function values(): array
    {
        return static::options()->keys()->toArray();
    }
}
