<?php

declare(strict_types = 1);

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$calcRibbonLength = function (Sequence $line) {
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

    $p1 = ($l * 2) + ($w * 2);
    $p2 = ($l * 2) + ($h * 2);
    $p3 = ($w * 2) + ($h * 2);
    $bow = $l * $w * $h;

    $lowestPerimeter = Sequence::of($p1, $p2, $p3)
        ->sort(static fn (int $a, int $b) => $a <=> $b)
        ->first()
        ->match(
            static fn (int $lowestPerimeter) => $lowestPerimeter,
            static fn () => null
        );

    return $lowestPerimeter + $bow;
};

$totalLength = Str::of(\file_get_contents('./../data/day2.txt'))
    ->split("\n")
    ->filter(static function(Str $line): bool {
        return !$line->empty();
    })
    ->map(static fn (Str $line) => Sequence::of(...\explode('x', $line->toString())))
    ->map($calcRibbonLength(...))
    ->reduce(0, static fn ($carry, $item) => $carry + $item);

print_r($totalLength);