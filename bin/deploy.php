<?php
$zip = new ZipArchive();
if ($zip->open('/home/kmlnetu/domains/maclews.eu/package.zip') === true) {
    $zip->extractTo('/home/kmlnetu/domains/maclews.eu/');
    $zip->close();
    echo 'Extraction successful' . PHP_EOL;
} else {
    echo 'Extraction failed' . PHP_EOL;
}