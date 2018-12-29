<?php
declare(strict_types=1);

require_once __DIR__.'/list.php';

// Create an empty board with 9 empty squares (0-8)
function board() {
    return flist(fl2list(0,''),
            flist(fl2list(1, ''),
            flist(fl2list(2,''),
            flist(fl2list(3,''),
            flist(fl2list(4,''),
            flist(fl2list(5,''),
            flist(fl2list(6,''),
            flist(fl2list(7,''),
            flist(fl2list(8,'')
            )))))))));
}
