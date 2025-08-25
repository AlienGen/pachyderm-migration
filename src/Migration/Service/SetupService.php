<?php

namespace Pachyderm\Migration\Service;

class SetupService
{
    /**
     * Set up the migration structure in the given project root
     * 
     * @param string $projectRoot
     * @param callable|null $outputCallback Optional callback for output messages
     */
    public static function setup(string $projectRoot, ?callable $outputCallback = null): void
    {
        $output = $outputCallback ?? function($message) {
            echo $message . "\n";
        };
        
        // Create migrations directory
        $migrationsDir = $projectRoot . '/database/migrations';
        if (!is_dir($migrationsDir)) {
            mkdir($migrationsDir, 0777, true);
            $output("Created database/migrations folder");
        } else {
            $output("database/migrations folder already exists");
        }
        
        // Create migration.php file
        $migrationFile = $projectRoot . '/migration.php';
        if (!file_exists($migrationFile)) {
            $migrationContent = "<?php\n\nrequire_once __DIR__ . '/vendor/aliengen/pachyderm-migration/migration.php';\n";
            file_put_contents($migrationFile, $migrationContent);
            $output("Created migration.php file");
        } else {
            $output("migration.php file already exists");
        }
        
        $output("Setup complete! You can now run migrations with: php migration.php");
    }
} 