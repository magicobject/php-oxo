<?php
declare(strict_types=1);
require_once __DIR__.'/../src/list.php';
require_once __DIR__.'/../src/board.php';
require_once __DIR__.'/../lib/assertions.php';

// Create an empty board with 9 empty squares (0-8)
$board = flist(fl2list(0, 'X'),
    flist(fl2list(1, 'O'),
        flist(fl2list(2, 'O'),
            flist(fl2list(3, 'X'),
                flist(fl2list(4, 'X'),
                    flist(fl2list(5, 'O'),
                        flist(fl2list(6, ' '),
                            flist(fl2list(7, ' '),
                                flist(fl2list(8, 'X')
                                )))))))));

assertEquals(3, count_pieces_on_board($board, 'O'));
assertEquals(4, count_pieces_on_board($board, 'X'));
assertEquals([6,7], available_squares($board)->toArray());

// Valid tests
$board = board();
assertEquals(true, valid($board), 'empty board should be valid');

$board = move($board, 'X', 0);
$board = move($board, 'X', 1);
assertEquals(false, valid($board), 'XX is invalid');


// won tests
$board = board();
$board = move($board,'X',0);
$board = move($board,'X',4);
$board = move($board,'X',8);
$board = move($board,'O',1);
$board = move($board,'O',3);

assertEquals(true, win($board));