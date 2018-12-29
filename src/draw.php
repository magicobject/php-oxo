<?php
declare(strict_types=1);

require_once __DIR__.'/board.php';


$b0 = board();
$b0 = replace($b0, 0, 'A');
$b0 = replace($b0, 1, 'B');
$b0 = replace($b0, 2, 'C');
$b0 = replace($b0, 3, 'D');
$b0 = replace($b0, 4, 'E');
$b0 = replace($b0, 5, 'F');
$b0 = replace($b0, 6, 'G');
$b0 = replace($b0, 7, 'H');
$b0 = replace($b0, 8, 'X');

$board = $b0;

$pieces_list = map(
    function($node) {
        return $node->list()->element();
        }, $board);

List($p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8)  = $pieces_list->toArray();


printf("%.2s | %.2s | %.2s\n", $p0, $p1, $p2);
printf("%.2s | %.2s | %.2s\n", $p3, $p4, $p5);
printf("%.2s | %.2s | %.2s\n", $p6, $p7, $p8);
