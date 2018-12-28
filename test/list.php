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
$nillist = new flist(null, null);
$onelist = new flist(1, $nillist);
$twolist = new flist(2, $onelist);
$threelist = new flist(3, $twolist);
$fourlist = new flist(4, $threelist);

/** @var flist $concatinated */
$concatinated = concatinate($threelist, $twolist);
$copied = fcopy($concatinated);
$appended = new flist('copied', $copied);

assertNotSame($concatinated, $copied);

$sumList = function(flist $list) {
    $plus = function ($a, $b) {return $a + $b;};
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
assertEquals(new flist(), concatinate(new flist(), new flist()));
assertEquals($fourlist, concatinate($fourlist, new flist()));
assertEquals($fourlist, concatinate(new flist(), $fourlist));

assertEquals(3, flmax($concatinated));
assertEquals(1, flmin($concatinated));
assertEquals(17, flmax(new flist(17, new flist())));
assertEquals(18, flmin(new flist(18, new flist())));

assertEquals("3 - 2 - 1 - 2 - 1", fljoin(' - ', $concatinated));

$onetwolist = fl2list(1,2);
$zippedFlist = new flist($onetwolist, new flist());

assertEquals(
    $zippedFlist,
    zip2(new flist(1, new flist()), new flist(2, new flist())),
    'zipping [1] to [2]'
);

// Test zipping two three lists together
$oneOneList = fl2list(1, 1);
$twoTwoList = fl2list(2, 2);
$threeThreeList = fl2list(3, 3);
$expectedList = new flist($threeThreeList, new flist($twoTwoList, new flist($oneOneList, new flist())));

$zippedThreeLists = zip2($threelist, $threelist);
assertEquals($expectedList, $zippedThreeLists, 'zipping two 3lists together' );


// Replacement testing
$initial = new flist(fl2list('alpha', 'beta'),
    new flist(fl2list('gamma', 'delta'),
        new flist(fl2list('epsilon', 'theta'), new flist() )));

$expected = new flist(fl2list('alpha', 'beta'),
    new flist(fl2list('gamma', 'DELTA'),
        new flist(fl2list('epsilon', 'theta'), new flist() )));

$replaced = replace($initial, 'gamma', 'DELTA');

assertEquals($expected, $replaced, 'replacement failed');

// Removal testing
$initial = new flist(fl2list('alpha', 'beta'),
    new flist(fl2list('gamma', 'delta'),
        new flist(fl2list('epsilon', 'theta'), new flist() )));

$expected = new flist(fl2list('alpha', 'beta'),
        new flist(fl2list('epsilon', 'theta'), new flist() ));

$removed = remove($initial, 'gamma');
assertEquals($expected, $removed, 'removal failed');

