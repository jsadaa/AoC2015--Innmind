<?php

declare(strict_types = 1);

use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = Str::of(file_get_contents('./../data/day1.txt'));
$index =
    $data
        ->split()
        ->map(static fn (Str $char) => $char->equals(Str::of('(')) ? 1 : -1)
        ->map(static function (int $int) {
            static $sum = 1;
            $sum += $int;
            if ($sum >= 0) return 1;
            else return 0;
        })
        ->indexOf(0)
        ->match(
            static fn (int $index) => $index,
            static fn () => null
        );

print_r($index);