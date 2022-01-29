<?php

use function Pest\Faker\faker;

test('if haystack contains at least an anagram of needle it returns true', function () {

    $needle = faker()->word(3);
    $shuffledNeedle = str_shuffle($needle);

    $haystack = faker()->word(10);
    $haystack .= $shuffledNeedle;

    $this->artisan(
        "detect",
        ['needle' => $needle, 'haystack' => $haystack]
    )
        ->expectsOutput('Vero')
        ->assertExitCode(true);
});

test('if haystack does not contains at least an anagram of needle it returns false', function () {

    $needle = 'abc';
    $haystack = 'waterfall';

    $this->artisan(
        "detect",
        ['needle' => $needle, 'haystack' => $haystack]
    )
        ->expectsOutput('Falso')
        ->assertExitCode(false);
});
