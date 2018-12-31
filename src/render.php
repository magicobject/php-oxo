<?php
declare(strict_types=1);

require_once __DIR__.'/board.php';

function render(flist $board) : void
{
    List($p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8) = list_pieces($board)->toArray();

    printf("%.2s | %.2s | %.2s\n", $p0, $p1, $p2);
    printf("%.2s | %.2s | %.2s\n", $p3, $p4, $p5);
    printf("%.2s | %.2s | %.2s\n", $p6, $p7, $p8);
}