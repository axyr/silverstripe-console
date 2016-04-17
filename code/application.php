<?php

/**
 * Lets be clear on the cli...
 */
ini_set('display_errors', 1);

/**
 * Prevents Out of Memory error for composers AutoLoader
 */
ini_set("memory_limit","32M");

/**
 * Load minimal core requirements
 */
require_once __DIR__.'/../../framework/core/Core.php';
require_once __DIR__.'/../../framework/model/DB.php';

/**
 * Set file to url mapping so we can communicate with Silverstripe core tasks and controllers.
 */
global $_FILE_TO_URL_MAPPING;
if(!isset($_FILE_TO_URL_MAPPING[BASE_PATH])) {
    $_FILE_TO_URL_MAPPING[BASE_PATH] = 'http://localhost';
}

/**
 * Connect to the database
 */
global $databaseConfig;
if ($databaseConfig) DB::connect($databaseConfig);

/**
 * Load the available Commands into the Symfony Console Application
 */
$application = new Symfony\Component\Console\Application();

/**
 * Why does this not work
 */
$commands = ClassInfo::subclassesFor('SilverstripeCommand');
//var_dump($commands);exit();
// This works, but does not load custom Commands
$commands = ClassInfo::classes_for_folder(BASE_PATH . '/console/code/');

/** @var SilverstripeCommand $command */
foreach ($commands as $command) {
    $reflection     = new ReflectionClass($command);
    if (!$reflection->isAbstract() &&  $reflection->isSubclassOf('SilverstripeCommand')) {
        $application->add(new $command());
    }
}

/**
 * Run Forest..
 */
$application->run(new Symfony\Component\Console\Input\ArgvInput($_SERVER['argv']));
