<?php

declare(strict_types = 1);

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'/../data/day1.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line);
    }
};

$floor =
    Sequence::lazy($data(...))
        ->map(static fn (Str $line) => $line->split())
        ->get(0)
        ->match(
            static fn (Sequence $sequence): Sequence => $sequence,
            static fn () => throw new \Exception('Unexpected value'),
        )
        ->reduce(0, static function (int $carry, Str $char) {
            return $carry + ($char->equals(Str::of('(')) ? 1 : -1);
        });

print_r($floor);