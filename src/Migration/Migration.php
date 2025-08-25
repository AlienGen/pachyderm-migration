<?php

namespace Pachyderm\Migration;

use Pachyderm\Migration\Service\MigrationManager;

class Migration
{
    private MigrationManager $migrationManager;
    
    public function __construct(MigrationManager $migrationManager)
    {
        $this->migrationManager = $migrationManager;
    }
    
    /**
     * Run all pending migrations
     */
    public function up(): void
    {
        $this->migrationManager->runMigrations();
    }
    
    /**
     * Get migration status
     * 
     * @return array
     */
    public function status(): array
    {
        return $this->migrationManager->getMigrationStatus();
    }
}
