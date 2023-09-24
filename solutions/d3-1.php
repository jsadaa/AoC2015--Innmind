<?php

declare(strict_types = 1);

use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = Str::of(file_get_contents('./../data/day3.txt'));

$nbrOfLuckyHouses =
    $data
        ->split()
        ->map(static fn (Str $char) => $char->toString())
        ->map(static function (string $direction) {
            static $pos = [0, 0];
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
        })
        ->add([0,0]) // add start position as it was not added by the map callback
        ->distinct()
        ->count();

print_r($nbrOfLuckyHouses);