<?php

declare(strict_types = 1);

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'./../data/day1.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line);
    }
};

$directions =
    Sequence::lazy($data(...))
        ->map(static fn (Str $line) => $line->split())
        ->get(0)
        ->match(
            static fn (Sequence $sequence): Sequence => $sequence,
            static fn () => throw new \Exception('Unexpected value'),
        )
        ->map(static fn (Str $char) => $char->equals(Str::of('(')) ? 1 : -1);

$sum = 0;
for ($index = 1; $index < $directions->size(); $index++) {
    $sum += $directions
        ->get($index - 1)
        ->match(
            static fn (int $value): int => $value,
            static fn () => throw new \Exception('Unexpected value'),
        );

    if ($sum === -1) {
        break;
    }
}

print_r($index);