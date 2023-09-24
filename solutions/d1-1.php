<?php

declare(strict_types = 1);

use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = Str::of(file_get_contents('./../data/day1.txt'));

$floor =
    $data
        ->split()
        ->map(static fn (Str $char) => $char->toString())
        ->reduce(0, static function ($carry, $item) {
            return $item === "(" ? ++$carry : --$carry;
        });

print_r($floor);