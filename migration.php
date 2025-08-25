<?php

/**
 * Pachyderm migration tool.
 * 
 * This file is the entry point for the migration tool.
 * 
 * It will:
 * - Check if the project is installed with Composer
 * - Check if the `config.php` file exists
 * - Require the autoload.php file
 * - Require the config.php file
 * - Create a new instance of the Migration class using the factory
 * - Execute the up method
 * 
 * @see https://github.com/aliengen/pachyderm-migration
 * @package Aliengen\Pachyderm\Migration
 * @author Timothé Mermet-Buffet <mermetbt@gmail.com>
 * @version 1.0.0
 */

// Get the package directory
define('PACHYDERM_MIGRATION_ROOT', __DIR__);

// Get the user project root from the package directory
define('PACHYDERM_USER_PROJECT_ROOT', __DIR__ . '/../../../');

// Check if the project is installed with Composer
if (!file_exists(PACHYDERM_USER_PROJECT_ROOT . '/vendor/autoload.php')) {
    echo "Please run `composer install` to install the dependencies.\n";
    exit(1);
}

// Require the autoload.php file
require_once PACHYDERM_USER_PROJECT_ROOT . '/vendor/autoload.php';

// Check if the `config.php` file exists
if (!file_exists(PACHYDERM_USER_PROJECT_ROOT . '/config.php')) {
    echo "Please create a `config.php` file in the root of your project.\n";
    exit(1);
}

// Require the config.php file
require_once PACHYDERM_USER_PROJECT_ROOT . '/config.php';

// Create a new instance of the Migration class using the factory
use Pachyderm\Migration\Factory\MigrationFactory;

// Execute the up method
$migration = MigrationFactory::create();
$migration->up();
