<?php

use Innmind\Immutable\Sequence;
use Innmind\Immutable\Str;

require __DIR__ . '/../vendor/autoload.php';

$data = function() {
    $data = \fopen(__DIR__.'/../data/day5.txt', 'r');

    while ($line = \fgets($data)) {
        yield $line;
    }
};

$strings = Sequence::lazy($data(...))
    ->map(fn (string $line) => Str::of($line));
$vowels = Sequence::strings('a', 'e', 'i', 'o', 'u');
$badSubstrings = Sequence::strings('ab', 'cd', 'pq', 'xy');

$has2IdenticalPairs = static fn (Str $line) => $line->matches('/(?:(..).*\1)/');
$has1LetterRepeatingWith1LetterBetween = static fn (Str $line) => $line->matches('/(?:(.).\1)/');

$nbrOfNiceStrings = $strings
    ->filter($has2IdenticalPairs)
    ->filter($has1LetterRepeatingWith1LetterBetween)
    ->count();

print_r($nbrOfNiceStrings);