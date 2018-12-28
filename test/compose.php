<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/compose.php';
require_once __DIR__ . '/../lib/assertions.php';


$f1 = function (int $x) {
    return $x + 1;
};
$f2 = function (int $x) {
    return $x * 2;
};

$compose = compose($f2, $f1);

// Test composition
assertEquals(15, $compose(7), 'Functional composition failed');

// Test left and right composition with an identity function

$id = function ($x) {
    return $x;
};
$compose1 = compose($id, $f2);

$c1 = compose($id, $id);
assertEquals(3, $c1(3), '(identity, identity) composition failed');

$c2 = compose($f2, $id);
assertEquals(18, $c2(9), 'right identity composition failed');

$c3 = compose($id, $f2);
assertEquals(18, $c3(9), 'left identity composition failed');


// Test multiple composition
$c4 = compose($f2, compose($f1, $f2));
assertEquals(38, $c4(9), 'multiple composition failed');

