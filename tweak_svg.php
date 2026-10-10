<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

// Find the rank circle pattern and replace with SVG
$find = '/<div class="w-8 h-8 rounded-full mx-auto shadow-sm relative[\s\S]*?<\/div>\s*<\/div>\s*(<\/td>)/';

$replace = '@php
                                    $bgColor  = $i === 0 ? \'#FACC15\' : ($i === 1 ? \'#D1D5DB\' : ($i === 2 ? \'#FDBA74\' : \'#1F2937\'));
                                    $txtColor = $i === 0 ? \'#713F12\' : ($i === 1 ? \'#374151\' : ($i === 2 ? \'#7C2D12\' : \'#FFFFFF\'));
                                @endphp
                                <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" class="mx-auto block">
                                    <circle cx="16" cy="16" r="16" fill="{{ $bgColor }}" />
                                    <text x="16" y="16" text-anchor="middle" dominant-baseline="central"
                                          font-family="Arial, Helvetica, sans-serif" font-size="12" font-weight="800"
                                          fill="{{ $txtColor }}">{{ $i + 1 }}</text>
                                </svg>
                            $1';

$content = preg_replace($find, $replace, $content);
file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo "SVG rank circle applied!\n";
echo substr_count($content, 'dominant-baseline').' occurrence(s) found';
