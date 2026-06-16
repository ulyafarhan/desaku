<?php
header('Content-Type: text/plain');
$storage = __DIR__.'/storage';
echo "Storage path: $storage\n";
echo "Exists: " . (file_exists($storage) ? 'YES' : 'NO') . "\n";
echo "Is Link: " . (is_link($storage) ? 'YES' : 'NO') . "\n";
echo "Is Dir: " . (is_dir($storage) ? 'YES' : 'NO') . "\n";
if (is_link($storage)) {
    $target = readlink($storage);
    echo "Link target: " . $target . "\n";
    echo "Target exists: " . (file_exists($target) ? 'YES' : 'NO') . "\n";
}
