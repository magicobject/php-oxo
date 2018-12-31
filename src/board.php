<?php
declare(strict_types=1);

require_once __DIR__.'/list.php';

// Create an empty board with 9 empty squares (0-8)
function board() {
    return flist(fl2list(0,' '),
            flist(fl2list(1, ' '),
            flist(fl2list(2,' '),
            flist(fl2list(3,' '),
            flist(fl2list(4,' '),
            flist(fl2list(5,' '),
            flist(fl2list(6,' '),
            flist(fl2list(7,' '),
            flist(fl2list(8,' ')
            )))))))));
}

function count_pieces_on_board(flist $board, $piece) {
    $counter = function(flist $square, int $count) use ($piece) : int {
      if ($square->list()->element() == $piece) $count++;
      return $count;
    };
    return fold_l(0, $counter, $board);
}

function available_squares(flist $board) : iflist {
    $available = function(iflist $square, iflist $accumulator) {
        if ($square->isNull()) return $accumulator;
        if ($square->list()->element() == ' ') {
            $accumulator = flist($square->element(), $accumulator);
        }
        return $accumulator;
    };
    return fold_r(flist(), $available, $board);
}

function x_count($board) {
    return count_pieces_on_board($board, 'X');
}

function o_count($board) {
    return count_pieces_on_board($board, 'O');
}

function occupied(flist $board, int $square) {
    return $board($square)->list()->element() != ' ';
}

function full($board) {
    return x_count($board) + o_count($board) >= 9;
}

function win($board) {
    if (x_count($board) + o_count($board) < 5) return false;
    List($p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8) = list_pieces($board)->toArray();
    return
        $p1 != ' ' && $p0 == $p1 && $p0 == $p2 ||
        $p3 != ' ' && $p3 == $p4 && $p3 == $p5 ||
        $p6 != ' ' && $p6 == $p7 && $p6 == $p8 ||
        $p0 != ' ' && $p0 == $p3 && $p0 == $p6 ||
        $p1 != ' ' && $p1 == $p4 && $p1 == $p7 ||
        $p2 != ' ' && $p2 == $p5 && $p2 == $p8 ||
        $p0 != ' ' && $p0 == $p4 && $p0 == $p8 ||
        $p2 != ' ' && $p2 == $p4 && $p2 == $p6;
}

// Not strictly needed as can be deduced from full() and won()
function drawn($board) {
    return !full($board) && !win($board);
}

function move(flist $board, $piece, $square) {
    return replace_piece_on_board($board, $square, $piece);
}

// Return a new flist with $element replaced by the ($key, $value) tuple flist
function replace_piece_on_board (iflist $flist, $key, $value) {

    $replacement = function(?iflist $old_list, ?iflist $accumulating_list) use ($key, $value) {
        if ($old_list->isNull()) return $accumulating_list;

        if ($old_list->element() == $key) {
            $newf2list = fl2list($key, $value);
        } else {
            $newf2list = fl2list($old_list->element(), $old_list->list()->element());
        }
        return flist($newf2list, $accumulating_list);
    };

    return fold_r(flist(), $replacement, $flist);
}

/**
 * I taks a board(flist) and return an flist of boards with the possible next move played
 * @param flist $board
 * @param $last_piece_moved
 * @return flist
 */
function next_boards(flist $board, $last_piece_moved) : flist {
    if (state($board) != 'OK') return flist();
    $piece = piece_to_move_next($last_piece_moved);
    return map(
        function($square) use ($board, $piece) {
            return move($board, $piece, $square);
            }, available_squares($board));
}

function list_pieces(flist $board) : flist {
    return map(
        function(flist $node) {
            return $node->list()->element();
        }, $board);
}

function state($board) {
    $won = win($board);
    $full = full($board);
    if ($won) return 'WON';
    if ($full) return 'DRAWN';
    return 'OK';
}

function valid($board) {
    $xc = x_count($board);
    $oc = o_count($board);

    return $xc <= 5 && $oc <= 5 && abs($xc - $oc) <= 1;
}

function piece_to_move_next($last_piece_moved) {
    if ($last_piece_moved == 'X') return 'O';
    return 'X';
}

function game_over($board) {
    return state($board) != 'OK';
}

function square(flist $flist) {
    return $flist->element()->element();
}

function piece(flist $flist) {
    return $flist->element()->list()->element();
}
