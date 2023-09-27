<?php

$input = "bgvyzdsv";
$nbrToTest = 1;

while (!$found = false) {
    $hash = \md5($input . $nbrToTest);
    if (\str_starts_with($hash, "00000")) {
        break;
    }
    $nbrToTest++;
}

echo 'Advent of Code 2015 - Day 4 - Part 1', "\n";
echo 'Lowest number to produce a hash starting with 00000: ', $nbrToTest, "\n";