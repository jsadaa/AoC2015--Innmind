<?php

$input = "bgvyzdsv";
$nbrToTest = 1;

while (!$found = false) {
    $hash = \md5($input . $nbrToTest);
    if (\str_starts_with($hash, "00000")) {
        $found = true;
        break;
    }
    $nbrToTest++;
}

print_r($nbrToTest);