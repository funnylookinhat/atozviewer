<?php 

$file = $_SERVER['REQUEST_URI'];

if( strpos($file, '?') !== FALSE ) {
    $file = substr($file,0,strrpos($file, '?') );
}

if (file_exists(__DIR__ . '/' . $file)) {
    return false;
} else {
    include_once 'index.php';
}