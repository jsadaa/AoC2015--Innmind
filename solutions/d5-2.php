<?php

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'/../data/day5.txt', 'r');

    while ($line = \fgets($data)) {
        yield Str::of($line);
    }
};

$strings = Sequence::lazy($data(...));
$vowels = Sequence::strings('a', 'e', 'i', 'o', 'u');
$badSubstrings = Sequence::strings('ab', 'cd', 'pq', 'xy');

$has2IdenticalPairs = static fn (Str $line) => $line->matches('/(?:(..).*\1)/');
$has1LetterRepeatingWith1LetterBetween = static fn (Str $line) => $line->matches('/(?:(.).\1)/');

$nbrOfNiceStrings = $strings
    ->filter($has2IdenticalPairs)
    ->filter($has1LetterRepeatingWith1LetterBetween)
    ->count();

echo 'Advent of Code 2015 - Day 5 - Part 2', "\n";
echo 'Number of nice strings: ', $nbrOfNiceStrings, "\n";