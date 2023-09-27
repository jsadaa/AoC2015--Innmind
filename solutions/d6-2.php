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

$size = 1000; // Ne pas oublier de changer la taille du tableau si on change la taille de la grille
$grid = array_fill(0, $size, array_fill(0, $size, 0));

$lights = Sequence::lazy($data)
    ->map(static function (array $line) {
        $action = $line[0];
        $from = explode(',', $line[1]);
        $to = explode(',', $line[2]);

        return [$action, $from, $to];
    })
    ->reduce($grid, static function (array $lights, array $instruction) {
        [$action, $from, $to] = $instruction;

        for ($i = \intval($from[0]); $i <= \intval($to[0]); $i++) {
            for ($j = \intval($from[1]); $j <= \intval($to[1]); $j++) {
                $lights[$i][$j] = match ($action) {
                    'turn-on' => $lights[$i][$j] + 1,
                    'turn-off' => \max(0, $lights[$i][$j] - 1),
                    'toggle' => $lights[$i][$j] + 2,
                    default => throw new \Exception('Unknown action'),
                };
            }
        }

        return $lights;
    });

$totalBrightness =
    Sequence::of(...$lights)
        ->reduce(0, static fn (int $total, array $line) => $total + \array_sum($line));

echo 'Advent of Code 2015 - Day 6 - Part 2', "\n";
echo "Total brightness: {$totalBrightness}\n";