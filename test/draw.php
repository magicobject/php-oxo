<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/render.php';


$b0 = board();
$b0 = replace($b0, 0, 'X');
$b0 = replace($b0, 1, 'B');
$b0 = replace($b0, 2, 'C');
$b0 = replace($b0, 3, 'D');
$b0 = replace($b0, 4, 'X');
$b0 = replace($b0, 5, 'F');
$b0 = replace($b0, 6, 'G');
$b0 = replace($b0, 7, 'H');
$b0 = replace($b0, 8, 'X');

$board = $b0;

render($board);