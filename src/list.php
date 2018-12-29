<?php
declare(strict_types = 1);

interface iflist {
    public function isEmpty();
}

class emptyList implements iflist {
    public function isEmpty() : bool {
        return true;
    }
}
/**
 * Class flist - a type for a single linked list,
 * containing an element and a link to the next element
 */
class flist implements iflist {
    private $element;

    public function isEmpty() : bool {
        return false;
    }

    /** @var iflist */
    private $list;

    public function __construct($element = null, $list = null) {
        $this->element = $element;
        $this->list = $list;
    }

    public function element() {
        return $this->element;
    }

    /**
     * @return flist|null
     */
    public function list() {
        return $this->list;
    }

    /**
     * @return bool
     */
    public function isNull() {
        return is_null($this->list);
    }

    /**
     * Only use as a helper for printing/testing
     * @return array
     */
    public function toArray() {
        $returnArray = [];
        $list = $this;
        do {
            $returnArray[] = $list->element;
            $list = $list->list();
        } while(!$list->isEmpty());

        return $returnArray;
    }

    public function __invoke(int $offset) {
        if ($offset < 0) throw new \RuntimeException('You asked for a negative offset on an flist');
        if ($offset == 0) return $this->element();
        $next = $this->list();
        return $next($offset - 1);
    }
}

function flist(...$argv) {
    if (empty($argv)) return new emptyList();
    if (sizeof($argv) == 1) return new flist($argv[0], new emptyList());
    if (sizeof($argv) == 2) return new flist($argv[0], $argv[1]);
    throw new \RuntimeException("Invalid arguments passed to flist function");
}

//
// functions on flist
//
/**
 * Concatenate two flists.
 * Firstly handle concatenation with empty lists
 *
 * @param flist $left
 * @param flist $right
 * @return flist
 */
function concatenate(iflist $left, iflist $right) : iflist {
    if ($left->isEmpty()) return $right;
    if ($right->isEmpty()) return $left;

    $append_element = function($a, flist $b) {
        return flist($a, $b);
    };
    return fold_r($right, $append_element, $left);
}

function fcopy(flist $list) {
    $append_element = function($a, iflist $b) {
        return flist($a, $b);
    };
    return fold_r(flist(), $append_element, $list);
}

function length(flist $flist) {
    if ($flist->isEmpty()) return 0;
    $counter = function($element, $count) { return $count+1; };
    return fold_r(0, $counter, $flist);
}

function map($op, ?flist $flist) {
    $mapAcc = function($a, $accFlist) use ($op) {
        return flist($op($a), $accFlist);
    };
    return fold_r(flist(), $mapAcc, $flist);
}

function fold_r($base, $op, iflist $flist) {
    if ($flist->isEmpty()) {
        return $base;
    }
    return $op($flist->element(), fold_r($base, $op, $flist->list()));
}

function fold_l($base, $op, iflist $flist) {
    if ($flist->isEmpty()) {
        return $base;
    }
    $accumulated = $op($flist->element(), $base);

    return fold_l($accumulated, $op, $flist->list());
}

function reverse(flist $list) {
    $builder = function($a, $acc) {
        return flist($a, $acc);
    };
    return fold_l(flist(), $builder, $list);
}

function flmax(flist $list) {
    return fold_l(PHP_INT_MIN, function($a, $b) { if ($a > $b) return $a; return $b;}, $list);
}

function flmin(flist $list) {
    return fold_l(PHP_INT_MAX, function($a, $b) { if ($a < $b) return $a; return $b;}, $list);
}

function fljoin(string $separator, flist $list) {
    return fold_l($list->element(), function($a, $b) use ($separator) { return $b.$separator.$a; }, $list->list());
}

/**
 * Helper for making a tuple
 * @param $a
 * @param $b
 * @return flist
 */
function fl2list($a, $b) {
    return flist($a, flist($b));
}

function zip2(iflist $a, iflist $b) : iflist
{
    if ($a->isEmpty()) return $b;
    if ($b->isEmpty()) return $a;
    return flist(fl2list($a->element(), $b->element()), zip2($a->list(), $b->list()));
}

// Return a new flist with $element replaced by the ($key, $value) tuple flist
function replace (iflist $flist, $key, $value) {

    $replacement = function(?iflist $old_list, ?iflist $accumulating_list) use ($key, $value) {
        if ($old_list->isEmpty()) return $accumulating_list;

        if ($old_list->element() == $key) {
            $newf2list = fl2list($key, $value);
        } else {
            $newf2list = fl2list($old_list->element(), $old_list->list()->element());
        }
        return flist($newf2list, $accumulating_list);
    };

    return fold_r(flist(), $replacement, $flist);
}

// Return a new flist with the $key element removed
function remove (flist $flist, $key) {

    $replacement = function(iflist $next_element, iflist $accumulating_list) use ($key) {
        if ($next_element->isEmpty()) return $accumulating_list;

        if ($next_element->element() == $key) {
            return $accumulating_list;
        }
        return flist($next_element, $accumulating_list);
    };

    return fold_r(flist(), $replacement, $flist);
}