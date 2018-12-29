<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/list.php';
require_once __DIR__ . '/../lib/assertions.php';

// Pretty print helper
function printfn($arg) {
    if ($arg InstanceOf flist) {
        echo join(',', $arg->toArray())."\n";
    }
    else if (!is_object($arg)) {
        echo $arg;
        echo "\n";
    } else {
        print_r($arg);
    }
}

// Some test lists
$onelist = flist(1);
$twolist = flist(2, $onelist);
$threelist = flist(3, $twolist);
$fourlist = flist(4, $threelist);

/** @var flist $concatinated */
$concatinated = concatenate($threelist, $twolist);
$copied = fcopy($concatinated);
$appended = flist('copied', $copied);

assertNotSame($concatinated, $copied);

$sumList = function(flist $list) {
    $plus = function ($a, $b) {
        return $a + $b;
    };
    return fold_r(0, $plus, $list);
};

$multiplyList = function(flist $list) {
    $times = function ($a, $b) { return $a * $b; };
    return fold_r(1, $times, $list);
};

assertEquals(10, $sumList($fourlist));
assertEquals(24, $multiplyList($fourlist));
assertEquals([3,2,1,2,1], $concatinated->toArray());

assertEquals( 5, length($concatinated) );
assertEquals( 6, length($appended) );

$x3 = function($a) { return 3*$a; };
assertEquals([9,6,3,6,3], map($x3, $concatinated)->toArray() );
assertEquals([1,2,1,2,3], reverse( $concatinated )->toArray() );

// Concatination tests
assertEquals(flist(), concatenate(flist(), flist()));
assertEquals($fourlist, concatenate($fourlist, flist()));
assertEquals($fourlist, concatenate(flist(), $fourlist));

assertEquals(3, flmax($concatinated));
assertEquals(1, flmin($concatinated));
assertEquals(17, flmax(flist(17)));
assertEquals(18, flmin(flist(18)));

assertEquals("3 - 2 - 1 - 2 - 1", fljoin(' - ', $concatinated));

$onetwolist = flist(fl2list(1,2));
$zippedList = zip2(flist(1), flist(2));

assertEquals($onetwolist, $zippedList, 'zipping [1] to [2]');

// Test zipping two three lists together
$oneOneList = fl2list(1, 1);
$twoTwoList = fl2list(2, 2);
$threeThreeList = fl2list(3, 3);
$expectedList = flist($threeThreeList, flist($twoTwoList, flist($oneOneList, flist())));

$zippedThreeLists = zip2($threelist, $threelist);
assertEquals($expectedList, $zippedThreeLists, 'zipping two 3lists together' );


// Replacement testing
$initial = flist(fl2list('alpha', 'beta'),
    flist(fl2list('gamma', 'delta'),
        flist(fl2list('epsilon', 'theta') )));

$expected = flist(fl2list('alpha', 'beta'),
    flist(fl2list('gamma', 'DELTA'),
        flist(fl2list('epsilon', 'theta') )));

$replaced = replace($initial, 'gamma', 'DELTA');

assertEquals($expected, $replaced, 'replacement failed');

// Removal testing
$initial = flist(fl2list('alpha', 'beta'),
    flist(fl2list('gamma', 'delta'),
        flist(fl2list('epsilon', 'theta') )));

// Remove middle element
$expected = flist(fl2list('alpha', 'beta'),
        flist(fl2list('epsilon', 'theta') ));

$removed = remove($initial, 'gamma');
assertEquals($expected, $removed, 'middle element removal failed');

// first element removal
$expected = flist(fl2list('gamma', 'delta'),
    flist(fl2list('epsilon', 'theta') ));

$removed = remove($initial, 'alpha');
assertEquals($expected, $removed, 'first element removal failed');

// last element removal
$expected = flist(fl2list('alpha', 'beta'),
    flist(fl2list('gamma', 'delta')));

$removed = remove($initial, 'epsilon');
assertEquals($expected, $removed, 'last element removal failed');

// Test offset
$list = flist(1, flist(2, flist(3, flist(4, flist(5)))));
assertEquals(5, $list(4), 'offset check');
assertEquals(3, $list(2), 'offset check');

