<?php
declare(strict_types=1);
require_once __DIR__.'/../src/board.php';
require_once __DIR__.'/../src/strategy.php';
require_once __DIR__.'/../src/render.php';

// Create an empty board with 9 empty squares (0-8)
$board = board();

function read_square($board) {
    $square = (int)readline('Enter your move: ');

    if ($square < 0 || $square > 8) {
        echo "No such square\n";
        return read_square($board);
    }
    if (occupied($board, $square)) {
        echo "That square is already taken\n";
        return read_square($board);
    }
    return $square;
}

render($board);

$computers_piece = 'X';
$players_piece = 'O';

do {
    // Player's move
    $square = read_square($board);
    $board = move($board, $players_piece, $square);
    render($board);
    if (game_over($board)) break;

    // Computer's move
    $square = calculate_computers_move($board);
    $board = move($board, $computers_piece, $square);
    render($board);
} while(!game_over($board));