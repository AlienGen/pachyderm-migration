<?php

namespace Pachyderm\Migration\Repository;

use Pachyderm\Service;

class DatabaseMigrationRepository implements MigrationRepositoryInterface
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = Service::get('database')->mysql();
    }

    public function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS db_migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $result = $this->mysqli->query($sql);
            if ($result === false) {
                throw new \Exception("Failed to create migrations table: " . $this->mysqli->error);
            }
            echo "Migrations table ready.\n";
        } catch (\Exception $e) {
            echo "Error creating migrations table: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function getExecutedMigrations(): array
    {
        try {
            $result = $this->mysqli->query("SELECT filename FROM db_migrations");
            if ($result === false) {
                throw new \Exception("Failed to query migrations: " . $this->mysqli->error);
            }

            $filenames = [];
            while ($row = $result->fetch_assoc()) {
                $filenames[] = $row['filename'];
            }
            $result->free();

            return $filenames;
        } catch (\Exception $e) {
            echo "Error getting executed migrations: " . $e->getMessage() . "\n";
            return [];
        }
    }

    public function recordMigrationExecution(string $filename): void
    {
        try {
            $stmt = $this->mysqli->prepare("INSERT INTO db_migrations (filename) VALUES (?)");
            if ($stmt === false) {
                throw new \Exception("Failed to prepare statement: " . $this->mysqli->error);
            }

            $stmt->bind_param("s", $filename);
            $result = $stmt->execute();

            if ($result === false) {
                throw new \Exception("Failed to execute statement: " . $stmt->error);
            }

            $stmt->close();
        } catch (\Exception $e) {
            echo "Error recording migration execution: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
