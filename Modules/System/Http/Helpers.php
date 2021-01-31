<?php

use Illuminate\Support\Collection;

if (! function_exists('pluckExplodedUnique')) {
    function pluckExplodedUnique(Collection $data, string $pluckBy, string $delimiter, $explodedIndex = 0)
    {
        return $data->pluck($pluckBy)
            ->map(function ($v) use ($delimiter, $explodedIndex) {
                return explode($delimiter, $v)[$explodedIndex];
            })->unique()->values();
    }
}
