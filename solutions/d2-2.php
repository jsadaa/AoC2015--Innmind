<?php

declare(strict_types = 1);

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'/../data/day2.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line);
    }
};

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

$totalLength = Sequence::lazy($data(...))
    ->map(static fn (Str $line) => $line->split('x'))
    ->map(static fn (Sequence $line) => $line->map(static fn (Str $value) => $value->toString()))
    ->map($calcRibbonLength(...))
    ->reduce(0, static fn ($carry, $item) => $carry + $item);

echo 'Advent of Code 2015 - Day 2 - Part 2', "\n";
echo 'Total ribbon length needed: ', $totalLength, "\n";