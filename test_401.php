<?php

$start = microtime(true);
$url = 'https://sheets.googleapis.com/v4/spreadsheets/18qDxr2gSCRJrkIlNbBCn3lBuHEhIKX-jwLSDsBocfZ8';
$res = @file_get_contents($url);
echo 'Time: '.(microtime(true) - $start)."s\n";
echo 'Response: '.($res === false ? 'Failed (likely 401/403)' : 'Success')."\n";
