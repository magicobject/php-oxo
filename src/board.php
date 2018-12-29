<?php
declare(strict_types=1);

require_once __DIR__.'/list.php';

function board() {
    return new flist(fl2list(0,''),
        new flist(fl2list(1, ''),
            new flist(fl2list(2,''),
            new flist(fl2list(3,''),
            new flist(fl2list(4,''),
            new flist(fl2list(5,''),
            new flist(fl2list(6,''),
            new flist(fl2list(7,''),
            new flist(fl2list(8,''),
            new flist()
            )))))))));
}
