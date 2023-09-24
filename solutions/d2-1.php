<?php

declare(strict_types = 1);

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

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

$totalSurface = Str::of(\file_get_contents('./../data/day2.txt'))
    ->split("\n")
    ->filter(static function(Str $line): bool {
        return !$line->empty();
    })
    ->map(static fn (Str $line) => Sequence::of(...\explode('x', $line->toString())))
    ->map($calcSurfaceAreaNeeded(...))
    ->reduce(0, fn ($carry, $item) => $carry + $item);

print_r($totalSurface);