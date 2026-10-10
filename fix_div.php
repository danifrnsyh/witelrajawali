<?php
$file = "resources/views/livewire/data-viewer.blade.php";
$content = file_get_contents($file);

// We have too many </div> right before <!-- Master Grid Container -->
// Let's find exactly that spot.
$find = "            </div>\n        </div>\n    </div>\n  \n  <!-- Master Grid Container --><!-- Master Grid Container -->";
$replace = "            </div>\n        </div>\n  \n  <!-- Master Grid Container -->";

// Since line endings might be \r\n, let's normalize everything for the replace
$content = str_replace("\r\n", "\n", $content);
$content = preg_replace('/<\/div>\s*<\/div>\s*<\/div>\s*<!-- Master Grid Container --><!-- Master Grid Container -->/', "</div>\n        </div>\n\n  <!-- Master Grid Container -->", $content);

file_put_contents($file, $content);
echo "Fixed div structure\n";
?>
