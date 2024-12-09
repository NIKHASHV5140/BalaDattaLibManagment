<?php

// Define the path to the PhpSpreadsheet library
$phpSpreadsheetPath = 'PhpSpreadsheet/src';  // Adjust the path if necessary

// Include the main PhpSpreadsheet autoload file
spl_autoload_register(function ($class) use ($phpSpreadsheetPath) {
    // Replace backslashes with directory separators for namespace-based paths
    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    
    // Include the file if it exists
    $classFilePath = $phpSpreadsheetPath . DIRECTORY_SEPARATOR . $classFile;
    if (file_exists($classFilePath)) {
        require_once $classFilePath;
    }
});
