<?php

declare(strict_types=1);

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
            ->split('#')
            ->map(fn (Str $part) => $part->toString())
            ->toList();
    }
};

########
# Note #
########
# This solution is faster.
# (We can even say that it is the only one that works for a 1000x1000 grid between the two)
# The reason is that we use a 2D array instead of a Map
# But the way we mixed the functional and imperative paradigms is not very elegant
#
# PS : See d6-1_alt.php for a solution using the Map::of() method

$size = 1000; // Ne pas oublier de changer la taille du tableau si on change la taille de la grille
$lights = array_fill(0, $size, array_fill(0, $size, 0));

$lightsOn = Sequence::lazy($data)
    ->map(static function (array $line) {
        $action = $line[0];
        $from = explode(',', $line[1]);
        $to = explode(',', $line[2]);

        return [$action, $from, $to];
    })
    ->reduce($lights, static function (array $lights, array $instruction) {
        $action = $instruction[0];
        $from = $instruction[1];
        $to = $instruction[2];

        for ($i = \intval($from[0]); $i <= \intval($to[0]); $i++) {
            for ($j = \intval($from[1]); $j <= \intval($to[1]); $j++) {
                switch ($action) {
                    case 'turn-on':
                        $lights[$i][$j] = 1;
                        break;
                    case 'turn-off':
                        $lights[$i][$j] = 0;
                        break;
                    case 'toggle':
                        $lights[$i][$j] = 1 - $lights[$i][$j];
                        break;
                }
            }
        }

        return $lights;
    });

$nbrOfLightsOn = array_reduce($lightsOn, static function ($carry, $row) {
    return $carry + array_sum($row);
}, 0);

echo 'Advent of Code 2015 - Day 6 - Part 1', "\n";
echo "Number of lights on : {$nbrOfLightsOn}", "\n";