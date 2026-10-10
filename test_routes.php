<?php
$file = 'routes/web.php';
$content = file_get_contents($file);
$content = str_replace("use App\Livewire\PeringkatViewer;", "use App\Livewire\PeringkatViewer;\nuse App\Livewire\SektoralViewer;", $content);
$content = str_replace("Route::get('/peringkat', PeringkatViewer::class);", "Route::get('/peringkat', PeringkatViewer::class);\nRoute::get('/sektoral', SektoralViewer::class)->name('sektoral');", $content);
file_put_contents($file, $content);
