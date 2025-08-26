<?php

namespace Pachyderm\Migration\Executor;

use Pachyderm\Service;
use Pachyderm\Migration\Repository\MigrationRepositoryInterface;

class MigrationExecutor
{
    private $mysqli;
    private MigrationRepositoryInterface $repository;

    public function __construct(MigrationRepositoryInterface $repository)
    {
        $this->mysqli = Service::get('database')->mysql();
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
                // Split SQL into individual statements and execute them
                $statements = $this->splitSqlStatements($sql);

                foreach ($statements as $index => $statement) {
                    $statement = trim($statement);
                    if (empty($statement)) {
                        continue; // Skip empty statements
                    }

                    echo "  Executing statement " . ($index + 1) . " of " . count($statements) . "...\n";

                    $result = $this->mysqli->query($statement);
                    if ($result === false) {
                        throw new \Exception("Failed to execute statement " . ($index + 1) . ": " . $this->mysqli->error);
                    }
                }

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
     * Split SQL content into individual statements
     *
     * @param string $sql
     * @return array
     */
    private function splitSqlStatements(string $sql): array
    {
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // Split by semicolon, but be careful with semicolons inside strings
        $statements = [];
        $currentStatement = '';
        $inString = false;
        $stringChar = '';

        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];

            if (!$inString && ($char === "'" || $char === '"')) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && $sql[$i - 1] !== '\\') {
                $inString = false;
            }

            if (!$inString && $char === ';') {
                $statements[] = trim($currentStatement);
                $currentStatement = '';
            } else {
                $currentStatement .= $char;
            }
        }

        // Add the last statement if it's not empty
        if (!empty(trim($currentStatement))) {
            $statements[] = trim($currentStatement);
        }

        // Filter out empty statements
        return array_filter($statements, function($stmt) {
            return !empty(trim($stmt));
        });
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
