<?php

namespace Aliengen\Pachyderm\Migration\Service;

use Aliengen\Pachyderm\Migration\Repository\MigrationRepositoryInterface;
use Aliengen\Pachyderm\Migration\Executor\MigrationExecutor;

class MigrationManager
{
    private MigrationRepositoryInterface $repository;
    private FileSystemService $fileSystemService;
    private MigrationExecutor $executor;
    
    public function __construct(
        MigrationRepositoryInterface $repository,
        FileSystemService $fileSystemService,
        MigrationExecutor $executor
    ) {
        $this->repository = $repository;
        $this->fileSystemService = $fileSystemService;
        $this->executor = $executor;
    }
    
    /**
     * Run all pending migrations
     */
    public function runMigrations(): void
    {
        echo "Starting migration process...\n";
        
        // Create the db_migrations table if it doesn't exist
        $this->repository->createMigrationsTable();
        
        // Get list of SQL files in the migrations directory
        $sqlFiles = $this->fileSystemService->getSqlFiles();
        
        if (empty($sqlFiles)) {
            echo "No SQL migration files found in {$this->fileSystemService->getMigrationsDirectory()}\n";
            return;
        }
        
        // Get already executed migrations
        $executedMigrations = $this->repository->getExecutedMigrations();
        
        // Execute pending migrations
        $pendingMigrations = array_diff($sqlFiles, $executedMigrations);
        
        if (empty($pendingMigrations)) {
            echo "All migrations are already up to date.\n";
            return;
        }
        
        echo "Found " . count($pendingMigrations) . " pending migrations.\n";
        
        foreach ($pendingMigrations as $migrationFile) {
            $this->executor->executeMigration($migrationFile);
        }
        
        echo "Migration process completed successfully.\n";
    }
    
    /**
     * Get the status of migrations
     * 
     * @return array
     */
    public function getMigrationStatus(): array
    {
        $sqlFiles = $this->fileSystemService->getSqlFiles();
        $executedMigrations = $this->repository->getExecutedMigrations();
        $pendingMigrations = array_diff($sqlFiles, $executedMigrations);
        
        return [
            'total' => count($sqlFiles),
            'executed' => count($executedMigrations),
            'pending' => count($pendingMigrations),
            'pending_files' => $pendingMigrations,
            'executed_files' => $executedMigrations
        ];
    }
} 