<?php
declare(strict_types = 1);

/**
 * Class flist - a type for a single linked list,
 * containing an element and a link to the next element
 */
class flist {
    private $element;
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
        return $this->list == null;
    }

    /**
     * Only use as a helper for printing/testing
     * @return array
     */
    public function toArray() {
        $returnArray = [];
        $list = $this;
        while($list->list() != null) {
            $returnArray[] = $list->element;
            $list = $list->list();
        }

        return $returnArray;
    }
}
//
// functions on flist
//
/**
 * Concatinate two flists
 *
 * @param flist $left
 * @param flist $right
 * @return flist
 */
function concatinate(flist $left, flist $right) : flist {
    $append_element = function($a, flist $b) {
        return new flist($a, $b);
    };
    return fold_r($right, $append_element, $left);
}

function fcopy(flist $list) {
    $append_element = function($a, flist $b) {
        return new flist($a, $b);
    };
    return fold_r(new flist(), $append_element, $list);
}

function length(flist $flist) {
    $counter = function($element, $count) { return $count+1; };
    return fold_r(0, $counter, $flist);
}

function map($op, flist $flist) {
    $mapAcc = function($a, $accFlist) use ($op) {
        return new flist($op($a), $accFlist);
    };
    return fold_r(new flist(), $mapAcc, $flist);
}

function fold_r($base, $op, flist $flist) {
    if ($flist->isNull()) {
        return $base;
    }
    return $op($flist->element(), fold_r($base, $op, $flist->list()));
}

function fold_l($base, $op, flist $flist) {
    if ($flist->isNull()) {
        return $base;
    }
    $accumulated = $op($flist->element(), $base);
    return fold_l($accumulated, $op, $flist->list());
}

function reverse(flist $list) {
    $builder = function($a, $acc) {
        return new flist($a, $acc);
    };
    return fold_l(new flist(), $builder, $list);
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
    return new flist($a, new flist($b, new flist()));
}

function zip2(flist $a, flist $b)
{
    if ($a->isNull()) return new flist();
    return new flist(fl2list($a->element(), $b->element()), zip2($a->list(), $b->list()));
}

// Return a new flist with $element replaced by the ($kay, $value) tuple flist
function replace (flist $flist, $key, $value) {

    $replacement = function(flist $old_list, flist $accumulating_list) use ($key, $value) {
        if ($old_list->isNull()) return $accumulating_list;

        if ($old_list->element() == $key) {
            $newf2list = fl2list($key, $value);
        } else {
            $newf2list = fl2list($old_list->element(), $old_list->list()->element());
        }
        return new flist($newf2list, $accumulating_list);
    };

    return fold_r(new flist(), $replacement, $flist);
}

// Return a new flist with the $key element removed
function remove (flist $flist, $key) {

    $replacement = function(flist $next_element, flist $accumulating_list) use ($key) {
        if ($next_element->isNull()) return $accumulating_list;

        if ($next_element->element() == $key) {
            return $accumulating_list;
        }
        return new flist($next_element, $accumulating_list);
    };

    return fold_r(new flist(), $replacement, $flist);
}