<?php
declare(strict_types=1);

function assertEquals($a, $b, string $description = '')
{
    if ($a == $b) {
        return;
    }
    echo __METHOD__.' test failed ' . $description. ' expected '.print_r($a, true).' actual '. print_r($b, true)."\n";
    exit(1);
}

function assertNotEquals($a, $b, string $description = '')
{
    if ($a == $b) {
        return;
    }
    echo __METHOD__.' test failed ' . $description. ' expected '.print_r($a, true).' actual '. print_r($b, true)."\n";
    exit(1);
}


function assertSame($a, $b, string $description = '')
{
    if ($a === $b) {
        return;
    }
    echo __METHOD__.' test failed ' . $description. ' expected '.print_r($a, true).' actual '. print_r($b, true)."\n";
    exit(1);
}


function assertNotSame($a, $b, string $description = '')
{
    if ($a !== $b) {
        return;
    }
    echo __METHOD__.' test failed ' . $description. ' expected '.print_r($a, true).' actual '. print_r($b, true)."\n";
    exit(1);
}