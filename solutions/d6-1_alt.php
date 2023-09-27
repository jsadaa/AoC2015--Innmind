<?php

declare(strict_types=1);

use Innmind\Immutable\Map;
use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function () {
    $data = \fopen(__DIR__ . '/../data/day6.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line)
            ->replace('n o', 'n-o')
            ->replace(' ', '#')
            ->replace('#through#', '#')
            ->split('#');
    }
};

########
# Note #
########
# After trying to implement the solution with the Map::of() method, I realized that it was too long to execute for the 1000x1000 grid.
# It easily takes 10+++ minutes to execute the instructions, like never ending.
# I think that the problem in this specific case is that the Map is reinstanciated at each time we add a new light or perform a mutation as the Map is immutable.
# I tried to use the Map::of() method with a 10x10 grid, and it worked fine with :
#
# turn on 0,0 through 9,9
# toggle 0,0 through 9,0
# turn off 0,0 through 0,9
#
# PS[0]: see d6-1.php for a faster solution using a 2D array
# PS[1]: can still improve the code by a lot

$size = 1000; // Ne pas oublier de changer la taille du tableau si on change la taille de la grille
$lights = Map::of();
for ($i = 0; $i < $size; $i++) {
    for ($j = 0; $j < $size; $j++) {
        $lights = $lights->put([$i, $j], 0);
    }
}

$nbrOfLightsOn = Sequence::lazy($data)
    ->reduce($lights, static function (Map $lights, Sequence $instruction) {
        $action = $instruction->first()->match(
            static fn(Str $action) => $action,
            static fn() => throw new \Exception('No action')
        );
        $from = $instruction->get(1)->match(
            static fn(Str $from) => $from,
            static fn() => throw new \Exception('No from')
        )
        ->split(',')
        ->toList();
        $to = $instruction->get(2)->match(
            static fn(Str $to) => $to,
            static fn() => throw new \Exception('No to')
        )
        ->split(',')
        ->toList();

        for ($i = \intval($from[0]->toString()); $i <= \intval($to[0]->toString()); $i++) {
            for ($j = \intval($from[1]->toString()); $j <= \intval($to[1]->toString()); $j++) {
                switch ($action->toString()) {
                    case 'turn-on':
                        $lights = $lights
                            ->remove([$i, $j])
                            ->put([$i, $j], 1);
                        break;
                    case 'turn-off':
                        $lights = $lights
                            ->remove([$i, $j])
                            ->put([$i, $j], 0);
                        break;
                    case 'toggle':
                        $lights = $lights
                            ->put([$i, $j], $lights
                                ->get([$i, $j])
                                ->match(
                                    static fn(int $value) => $value === 1 ? 0 : 1,
                                    static fn() => throw new \Exception('No value')
                                )
                            );
                        break;
                }
            }
        }

        return $lights;
    })
    ->filter(static fn($key, $value) => $value === 1)
    ->count();

echo 'Advent of Code 2015 - Day 6 - Part 1', "\n";
echo "Number of lights on: {$nbrOfLightsOn}", "\n";