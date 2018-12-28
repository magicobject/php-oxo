<?php
declare(strict_types=1);

/**
 * return a closure which composed the two passed in funcitons
 * @param $f1
 * @param $f2
 * @return Closure
 */
function compose(Callable $f1, Callable $f2) {
    return function($x) use ($f1, $f2) { return $f2($f1($x)); };
}

