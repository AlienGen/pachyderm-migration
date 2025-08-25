<?php

namespace Pachyderm\Migration\Repository;

use Pachyderm\Service;

class DatabaseMigrationRepository implements MigrationRepositoryInterface
{
    private $db;
    
    public function __construct()
    {
        $this->db = Service::get('database');
    }
    
    public function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS db_migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            $this->db->exec($sql);
            echo "Migrations table ready.\n";
        } catch (\Exception $e) {
            echo "Error creating migrations table: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
    
    public function getExecutedMigrations(): array
    {
        try {
            $stmt = $this->db->query("SELECT filename FROM db_migrations");
            $results = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            return $results;
        } catch (\Exception $e) {
            echo "Error getting executed migrations: " . $e->getMessage() . "\n";
            return [];
        }
    }
    
    public function recordMigrationExecution(string $filename): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO db_migrations (filename) VALUES (?)");
            $stmt->execute([$filename]);
        } catch (\Exception $e) {
            echo "Error recording migration execution: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
} 