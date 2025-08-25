<?php

namespace Pachyderm\Migration\Factory;

use Pachyderm\Migration\Migration;
use Pachyderm\Migration\Repository\DatabaseMigrationRepository;
use Pachyderm\Migration\Service\FileSystemService;
use Pachyderm\Migration\Executor\MigrationExecutor;
use Pachyderm\Migration\Service\MigrationManager;

class MigrationFactory
{
    /**
     * Create a new Migration instance with all dependencies
     * 
     * @return Migration
     */
    public static function create(): Migration
    {
        $repository = new DatabaseMigrationRepository();
        $fileSystemService = new FileSystemService();
        $executor = new MigrationExecutor($repository);
        $migrationManager = new MigrationManager($repository, $fileSystemService, $executor);
        
        return new Migration($migrationManager);
    }
    
    /**
     * Create a Migration instance with custom migrations directory
     * 
     * @param string $migrationsDir
     * @return Migration
     */
    public static function createWithCustomDirectory(string $migrationsDir): Migration
    {
        $repository = new DatabaseMigrationRepository();
        $fileSystemService = new FileSystemService($migrationsDir);
        $executor = new MigrationExecutor($repository);
        $migrationManager = new MigrationManager($repository, $fileSystemService, $executor);
        
        return new Migration($migrationManager);
    }
} 