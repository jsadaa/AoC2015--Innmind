<?php

declare(strict_types = 1);

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'./../data/day2.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line);
    }
};

$calcSurfaceAreaNeeded = function (Sequence $line) {
    $l = (int) $line->get(0)->match(
        static fn (string $l) => $l,
        static fn () => null
    );
    $w = (int) $line->get(1)->match(
        static fn (string $w) => $w,
        static fn () => null
    );
    $h = (int) $line->get(2)->match(
        static fn (string $h) => $h,
        static fn () => null
    );

    $lw = $l * $w;
    $wh = $w * $h;
    $hl = $h * $l;

    $lowest  = Sequence::of($lw, $wh, $hl)
        ->sort(static fn (int $a, int $b) => $a <=> $b)
        ->first()
        ->match(
            static fn (int $lowest) => $lowest,
            static fn () => null
        );

    return $lowest + (2 * $lw) + (2 * $wh) + (2 * $hl);
};

$totalSurface = Sequence::lazy($data(...))
    ->map(static fn (Str $line) => $line->split('x'))
    ->map(static fn (Sequence $line) => $line->map(static fn (Str $value) => $value->toString()))
    ->map($calcSurfaceAreaNeeded(...))
    ->reduce(0, fn ($carry, $item) => $carry + $item);

print_r($totalSurface);