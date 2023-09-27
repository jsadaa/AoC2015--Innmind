<?php

declare(strict_types = 1);

use Innmind\Immutable\Map;
use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'/../data/day3.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line);
    }
};

function updatePos(string $direction, array $pos): array {
    switch ($direction) {
        case '^':
            $pos[0]++;
            break;
        case 'v':
            $pos[0]--;
            break;
        case '>':
            $pos[1]++;
            break;
        case '<':
            $pos[1]--;
            break;
        default:
            throw new \Exception('Unexpected value');
    }
    return $pos;
};

$directions =
    Sequence::lazy($data(...))
        ->map(static fn (Str $line) => $line->split())
        ->get(0)
        ->match(
            static fn (Sequence $sequence): Sequence => $sequence,
            static fn () => throw new \Exception('Unexpected empty sequence'),
        );

$nbrOfLuckyHouses =
    $directions
        ->map(static fn (Str $char) => $char->toString())
        ->reduce(Sequence::of([0,0]), static function (Sequence $carry, string $direction) {
            $pos = updatePos(
                $direction,
                $carry
                    ->last()
                    ->match(
                        static fn (array $pos): array => $pos,
                        static fn () => throw new \Exception('Unexpected value')
                    )
            );
            return $carry->add($pos);
        })
        ->distinct()
        ->count();

echo 'Advent of Code 2015 - Day 3 - Part 1', "\n";
echo 'Number of lucky houses: ', $nbrOfLuckyHouses, "\n";