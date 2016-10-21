<?php

function argv_options($options = array())
{
    $argc = isset($_SERVER['argc']) ? $_SERVER['argc'] : 0;
    $argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();
    for($i = 1; $i < $argc; $i++) {
        list($key,$value) = explode('=',$argv[$i].'=');
        if (empty($value) || 'true'==strtolower($value)) { // в опциях можно указать --daily=true или просто --daily.
            $value = true;
        } elseif('false'==strtolower($value)) {
            $value = false;
        }
        $options[$key] = $value;
    }
    return $options;
}

/**
 * Преобразует указанно имя иконки в общее имя, по которому должны связаться
 * все иконки между платформами и с разными модицикациями внешнего вида
 * @param string $iconName
 * @return string
 */
function iconName($iconName)
{
    // удалить расширение файла
    if ('.svg' == strtolower(substr($iconName, -4)))
        $iconName = substr($iconName, 0, -4);

    return $iconName;
}

$options = argv_options($options);

if (!$options['--path']) {
//    echo '--path=?', PHP_EOL;
    return;
}
if (!is_dir($options['--path'])) {
//    echo '--path=', $options['--path'], ' not a folder', PHP_EOL;
    return;
}
$templatePath = __DIR__.'/templates/' . $options['--template'] . '.phtml';
if (!is_file($templatePath)) {
//    echo '--template=', $options['--template'], ' not found', PHP_EOL;
    return;
}

$zip = zip_open("211016.zip");

$files = [];
$dirIterator = new \RecursiveDirectoryIterator($options['--path']);
$fileIterator = new \RecursiveIteratorIterator($dirIterator);
foreach($fileIterator as $file) {
    /** @var $file \SplFileInfo */
    $fileName = trim($file->getFilename());
    if(empty($fileName) || '.' == $fileName{0}) {
        continue;
    }

    if ('svg' != strtolower($file->getExtension())) {
        continue;
    }

    // офисные собираем по директориям сименами 16, 30, 40..
    if($options['--template'] == 'office') {
        $dirName = array_pop(explode('/', dirname($file)));
        $files[$fileName][$dirName] = $file;
    } else {
        $files[(string)$file] = $file;
    }
}
if($options['--template'] == 'office') {
    $filesForSort = $files;
    foreach($filesForSort as $name => $sizesFiles) {
        krsort($sizesFiles);
        $files[$name] = $sizesFiles;
    }
}

ob_start();
include($templatePath);
$content = ob_get_clean();

if ('--' == $options['--out']) {
    echo $content, PHP_EOL;
} else {
    file_put_contents($options['--out'], $content);
    $zip = new ZipArchive;
    if ($zip->open(date("dmy").'.zip') === TRUE) {
            $zip->addFromString('preview.html', $content);
        $zip->close();
    }
}