<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

$fileSystem = new Filesystem();
$result = [];
$docResult = [];
$templateFinder = new Finder();
$templateFinder->directories()->depth(0)->in('/app/src')->sortByName();

foreach ($templateFinder as $compose) {
    $name = $compose->getBasename();
    $files = [
        'name' => $name,
        'files' => []
    ];
    $filesFinder = new Finder();
    $filesFinder->files()->in('/app/src/' . $name);

    foreach ($filesFinder as $file) {
        $filename = $file->getFilename();
        $files['files'][] = [
            'directory' => $file->getRelativePath(),
            'filename' => $filename,
            'contents' => $file->getContents(),
        ];
    }

    $result[] = $files;
}

$fileSystem->remove(['/app/data/templates.json', '/app/doc/templates.json']);
$fileSystem->dumpFile('/app/data/templates.json', json_encode($result));