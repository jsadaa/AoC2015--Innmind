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
            static fn () => throw new \Exception('Unexpected value'),
        )
        ->toList();

$directionsMap = Map::of();
for ($i = 0; $i < count($directions); $i++) {
    $directionsMap = ($directionsMap)($i, $directions[$i]);
}

$santaPositions =
    $directionsMap
        ->filter(fn(int $key, $value) => $key % 2 === 0)
        ->map(static fn (int $key, Str $char) => $char->toString())
        ->reduce(Sequence::of([0,0]), static function (Sequence $carry, int $key, string $direction) {
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
        ->distinct();

$robotSantaPositions =
    $directionsMap
        ->filter(fn($key, $value) => $key % 2 !== 0)
        ->map(static fn (int $key, Str $char) => $char->toString())
        ->reduce(Sequence::of([0,0]), static function (Sequence $carry, int $key, string $direction) {
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
        ->distinct();

$nbrOfLuckyHouses =
    $santaPositions
        ->append($robotSantaPositions)
        ->distinct()
        ->count();

print_r($nbrOfLuckyHouses);