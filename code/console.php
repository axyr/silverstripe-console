<?php

/**
 * Lets be clear on the cli...
 */
ini_set('display_errors', 1);

/*
 * Prevents Out of Memory error for composers AutoLoader
 */
ini_set('memory_limit', '32M');

/**
 * Load minimal core requirements.
 */
require_once __DIR__.'/../../framework/core/Core.php';
require_once __DIR__.'/../../framework/model/DB.php';

/*
 * Set file to url mapping so we can communicate with Silverstripe core tasks and controllers.
 */
global $_FILE_TO_URL_MAPPING;
if (!isset($_FILE_TO_URL_MAPPING[BASE_PATH])) {
    $_FILE_TO_URL_MAPPING[BASE_PATH] = 'http://localhost';
}

/*
 * Connect to the database
 */
global $databaseConfig;
if ($databaseConfig) {
    DB::connect($databaseConfig);
}

/*
 * Boostrap the Console Application and run it...
 */
$application = new SilverstripeApplication();
$application->run(new Symfony\Component\Console\Input\ArgvInput($_SERVER['argv']));
