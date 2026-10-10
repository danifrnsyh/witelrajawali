<?php
$file = 'resources/views/components/layouts/app.blade.php';
$content = file_get_contents($file);

// check if inter is already there
if (strpos($content, 'fonts.googleapis.com') === false) {
    // Add it after <title>
    $inter = "\n    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n    <link href=\"https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap\" rel=\"stylesheet\">\n    <style>\n        body, html { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }\n    </style>\n";
    $content = str_replace("</title>", "</title>" . $inter, $content);
    file_put_contents($file, $content);
    echo "Added Inter font to layout\n";
} else {
    echo "Google fonts already exist.\n";
}
