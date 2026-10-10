<?php

$f = 'app/Livewire/KinerjaTeknisi.php';
$c = file_get_contents($f);

// Remove the admin properties
$c = preg_replace('/\/\/ Admin Edit Properties.*?public \$isEditModalOpen = false;/s', '', $c);

// Remove editTicket, saveTicket, closeEditModal
$c = preg_replace('/public function editTicket\(.*?}    }/s', '', $c);
$c = preg_replace('/public function saveTicket\(.*?}    }/s', '', $c);
$c = preg_replace('/public function closeEditModal\(.*?}    }/s', '', $c);

file_put_contents($f, $c);
