<?php

namespace Kanboard\Plugin\gantt\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec("ALTER TABLE links ADD COLUMN gantt_visible INT DEFAULT '0'");
}
