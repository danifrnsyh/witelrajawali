<?php

$start = microtime(true);
$res = file_get_contents('https://google.com');
echo 'Time: '.(microtime(true) - $start)."s\n";
