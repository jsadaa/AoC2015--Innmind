<?php

declare(strict_types = 1);

use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = Str::of(file_get_contents('./../data/day3.txt'));

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

$santa =
    $data
        ->split()
        ->filter(static function (): bool {
            static $index = 0;
            $take = $index % 2 === 0;
            $index++;
            return $take;
        })
        ->map(fn (Str $char) => $char->toString());

$robotSanta =
    $data
        ->split()
        ->filter(static function (): bool {
            static $index = 0;
            $take = $index % 2 !== 0;
            $index++;
            return $take;
        })
        ->map(fn (Str $char) => $char->toString());

$santaPositions =
    $santa
        ->map(static function (string $direction): array {
            static $pos = [0, 0];
            return $pos = updatePos($direction, $pos);
        })
        ->add([0,0]) // add st  art position as it was not added by the map callback (initial position is updated before the first iteration return)
        ->distinct();

$robotSantaPositions =
    $robotSanta
        ->map(static function (string $direction): array {
            static $pos = [0, 0];
            return $pos = updatePos($direction, $pos);
        })
        ->add([0,0]) // add start position as it was not added by the map callback (initial position is updated before the first iteration return)
        ->distinct();

$nbrOfLuckyHouses =
    $santaPositions
        ->append($robotSantaPositions)
        ->distinct()
        ->count();

print_r($nbrOfLuckyHouses);