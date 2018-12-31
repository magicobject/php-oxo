<?php
declare(strict_types=1);

require_once __DIR__.'/../src/list.php';

function calculate_computers_move(flist $board) : int
{
    $available_squares = available_squares($board);
    $available_squares_array = $available_squares->toArray();

    $next_scores = next_scores($board);
    $next_scores_array = $next_scores->toArray();

    $square_score = max_pair(zip2($available_squares, $next_scores));
    return $square_score->element();
}

function next_scores($board)
{
    return max_scores($board, 'O', 'X');
}

function max_scores(flist $board, string $last_piece_moved, $computers_piece) : flist {
    $next_boards = next_boards($board, $last_piece_moved);
    $mxs = map(
        function($board) use ($last_piece_moved, $computers_piece, $next_boards) : int {
            $max = max_score($board, $last_piece_moved, $computers_piece);
            return $max;
        }, $next_boards );
    return $mxs;
}

function max_score(flist $board, string $last_piece_moved, string $computers_piece) : int {
    $last_piece_moved = piece_to_move_next($last_piece_moved);
    if (state($board) != 'OK') return score($board, $last_piece_moved, $computers_piece);
    return flmin(min_scores($board, $last_piece_moved, $computers_piece));
}

function min_scores(flist $board, string $last_piece_moved, string $computers_piece) : flist {
    $ms = map(
        function(flist $board) use ($last_piece_moved, $computers_piece) : int {
            return min_score($board, $last_piece_moved, $computers_piece);
            },
        next_boards($board, $last_piece_moved)
    );
    return $ms;
}

function min_score($board, string $last_piece_moved, $computers_piece) : int {
    $last_piece_moved = piece_to_move_next($last_piece_moved);
    if (state($board) != 'OK') return score($board, $last_piece_moved, $computers_piece);
    return flmax(max_scores($board, $last_piece_moved, $computers_piece));
}

// Return the square/score pair with the highest score
function max_pair(flist $zipped_square_score) : flist {
    $compare = function(flist $square_score, flist $acc) : flist {
        $score = $square_score->list()->element();
        $acc_score = $acc->list()->element();

        if ($score > $acc_score) return $square_score;
        return $acc;
    };
    return fold_l(fl2list(-1, PHP_INT_MIN), $compare, $zipped_square_score);
}

function score(flist $board, string $last_piece_moved, string $computers_piece) {
    $state = state($board);
    if ($state == 'OK') return 0;
    if ($state == 'DRAWN') return 0;
    // WON case
    if ($last_piece_moved == $computers_piece) return 10;
    return -10;
}