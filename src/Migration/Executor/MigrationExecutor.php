<?php

namespace Pachyderm\Migration\Executor;

use Pachyderm\Service;
use Pachyderm\Migration\Repository\MigrationRepositoryInterface;

class MigrationExecutor
{
    private $db;
    private MigrationRepositoryInterface $repository;
    
    public function __construct(MigrationRepositoryInterface $repository)
    {
        $this->db = Service::get('database');
        $this->repository = $repository;
    }
    
    /**
     * Execute a single migration file
     * 
     * @param string $filename
     */
    public function executeMigration(string $filename): void
    {
        echo "Executing migration: {$filename}\n";
        
        try {
            // Read and execute SQL file
            $sql = $this->getMigrationSql($filename);
            
            if (empty(trim($sql))) {
                echo "Warning: Migration file {$filename} is empty.\n";
            } else {
                $this->db->exec($sql);
                echo "Successfully executed: {$filename}\n";
            }
            
            // Record the migration as executed
            $this->repository->recordMigrationExecution($filename);
            
        } catch (\Exception $e) {
            echo "Error executing migration {$filename}: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
    
    /**
     * Get the SQL content from a migration file
     * This method is abstracted to allow for different file reading strategies
     * 
     * @param string $filename
     * @return string
     */
    private function getMigrationSql(string $filename): string
    {
        // This could be injected as a dependency for better testability
        $filepath = getcwd() . '/database/migrations/' . $filename;
        
        if (!file_exists($filepath)) {
            throw new \RuntimeException("Migration file not found: {$filename}");
        }
        
        return file_get_contents($filepath);
    }
} 