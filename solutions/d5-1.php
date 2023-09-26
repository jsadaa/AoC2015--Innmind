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
$badSubstrings = Sequence::of(Str::of('ab'), Str::of('cd'), Str::of('pq'), Str::of('xy'));

$has3Vowels = static fn (Str $line) => $line->matches('/(?:[aeiou].*){3}/');
$hasConsecutiveChars = static fn (Str $line) => $line->matches('/([a-zA-Z])\1/');
$hasNoBadStrings = static function (Str $line) use ($badSubstrings) {
    return !$badSubstrings->reduce(
        false,
        static function (bool $hasBadString, Str $badString) use ($line) {
            $badString = $badString->toString();
            return $hasBadString || $line->matches("/$badString/");
        },
    );
};

$nbrOfNiceStrings = $strings
    ->filter($has3Vowels)
    ->filter($hasConsecutiveChars)
    ->filter($hasNoBadStrings)
    ->count();

print_r($nbrOfNiceStrings);