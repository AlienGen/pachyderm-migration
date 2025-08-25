<?php

namespace Aliengen\Pachyderm\Migration\Service;

class FileSystemService
{
    private string $migrationsDir;
    
    public function __construct(string $migrationsDir = null)
    {
        $this->migrationsDir = $migrationsDir ?? getcwd() . '/database/migrations';
    }
    
    /**
     * Get all SQL files in the migrations directory
     * 
     * @return array<string>
     */
    public function getSqlFiles(): array
    {
        if (!is_dir($this->migrationsDir)) {
            echo "Migrations directory not found: {$this->migrationsDir}\n";
            return [];
        }
        
        $files = glob($this->migrationsDir . '/*.sql');
        $filenames = array_map('basename', $files);
        sort($filenames); // Sort alphabetically
        
        return $filenames;
    }
    
    /**
     * Read the contents of a migration file
     * 
     * @param string $filename
     * @return string
     */
    public function readMigrationFile(string $filename): string
    {
        $filepath = $this->migrationsDir . '/' . $filename;
        
        if (!file_exists($filepath)) {
            throw new \RuntimeException("Migration file not found: {$filename}");
        }
        
        return file_get_contents($filepath);
    }
    
    /**
     * Check if a migration file exists
     * 
     * @param string $filename
     * @return bool
     */
    public function migrationFileExists(string $filename): bool
    {
        return file_exists($this->migrationsDir . '/' . $filename);
    }
    
    /**
     * Get the migrations directory path
     * 
     * @return string
     */
    public function getMigrationsDirectory(): string
    {
        return $this->migrationsDir;
    }
} 