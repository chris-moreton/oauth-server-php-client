<?php
include 'vendor/autoload.php';


function config($key) {
    $spec_config = parse_ini_file('.test.config');
    
    return $spec_config[$key];
}
