<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

$find = '/<tr>\r?\n\s*<th class="px-4 py-3 align-middle h-12.*?<\/tr>/s';

$replace = '<tr style="height:48px">
                        <th class="px-4 w-16 text-center bg-gray-100 rounded-l-full" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="50%" y="24" text-anchor="middle" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">RANK</text></svg>
                        </th>
                        <th class="px-4 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="8" y="24" text-anchor="start" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">TEKNISI</text></svg>
                        </th>
                        <th class="px-4 w-32 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="50%" y="24" text-anchor="middle" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">PROD</text></svg>
                        </th>
                        <th class="px-4 w-40 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="8" y="24" text-anchor="start" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">TERHADAP TARGET</text></svg>
                        </th>
                        <th class="px-4 w-48 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="8" y="24" text-anchor="start" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">HARIAN</text></svg>
                        </th>
                        <th class="px-4 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="50%" y="24" text-anchor="middle" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">KEHADIRAN</text></svg>
                        </th>
                        <th class="px-4 w-24 bg-gray-100 rounded-r-full" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="90%" y="24" text-anchor="end" dominant-baseline="central" font-family="Arial,sans-serif" font-size="10" font-weight="700" fill="#111827" letter-spacing="1">GAP</text></svg>
                        </th>
                    </tr>';

$content = preg_replace($find, $replace, $content);
file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'SVG headers applied! Count: '.substr_count($content, 'dominant-baseline="central"');
