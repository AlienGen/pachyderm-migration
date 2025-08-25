<?php

namespace Pachyderm\Migration\Repository;

interface MigrationRepositoryInterface
{
    /**
     * Create the migrations table if it doesn't exist
     */
    public function createMigrationsTable(): void;
    
    /**
     * Get all executed migration filenames
     * 
     * @return array<string>
     */
    public function getExecutedMigrations(): array;
    
    /**
     * Record a migration as executed
     * 
     * @param string $filename
     */
    public function recordMigrationExecution(string $filename): void;
} 