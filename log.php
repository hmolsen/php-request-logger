<?php
# ini_set('display_errors', 1); // Enable error display
# ini_set('log_errors', 0);     // Disable logging to the error log file
# error_reporting(E_ALL);       // Report all errors
$filename = 'log.txt';
$maxFileSize = 1024 * 1024; // 1MB


$logentry = date('Y-m-d H:i:s') . "\n" . 
             print_r(filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING), true) . "\n" . 
             print_r(filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING), true) . "\n\n";

$existingContent = '';
if (file_exists($filename)) {
    $existingContent = file_get_contents($filename);
    if ($existingContent === false) {
        error_log("Could not read the log file: $filename");
        exit;
    }
}

// Prepend the new log entry to the existing content
$newContent = $logentry . $existingContent;

// Truncate the content to the max file size
if (strlen($newContent) > $maxFileSize) {
    $newContent = substr($newContent, 0, $maxFileSize);
}

// Write the updated content back to the file
if (!file_put_contents($filename, $newContent, LOCK_EX)) {
    error_log("Could not write to the log file: $filename");
    exit;
}
?>
