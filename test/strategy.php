<?php
declare(strict_types=1);

require_once __DIR__.'/../src/board.php';
require_once __DIR__.'/../src/strategy.php';
require_once __DIR__.'/../lib/assertions.php';

$board = board();
$board = move($board, 'X', 0);
$available_squares = available_squares($board);

assertEquals([1,2,3,4,5,6,7,8], $available_squares->toArray(), 'Available squares mismatch');


$board = move($board, 'O', 4);
$available_squares = available_squares($board);
assertEquals([1,2,3,5,6,7,8], $available_squares->toArray(), 'Available squares mismatch');