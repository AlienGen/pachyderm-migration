<?php

namespace Pachyderm\Migration\Composer;

use Composer\Script\Event;
use Pachyderm\Migration\Service\SetupService;

/**
 * Installer for the Pachyderm migration tool.
 * 
 * This class is used to scaffold the project with the necessary files for the migration tool.
 * 
 * - Create a database/migrations folder in the project root
 * - Create a migration.php file in the project root
 * 
 * @see https://github.com/aliengen/pachyderm-migration
 * @package Pachyderm\Migration\Composer
 * @author Timothé Mermet-Buffet <mermetbt@gmail.com>
 * @version 1.0.0
 */
class Installer
{
    /**
     * Post install hook.
     * 
     * @param Event $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        self::scaffold($event);
    }

    /**
     * Post update hook.
     * 
     * @param Event $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        self::scaffold($event);
    }

    /**
     * Scaffold the project with the necessary files for the migration tool.
     * 
     * @param Event $event
     * @return void
     */
    private static function scaffold(Event $event)
    {
        $io = $event->getIO();
        $projectRoot = getcwd();
        
        // Use the SetupService with Composer's IO output
        SetupService::setup($projectRoot, function($message) use ($io) {
            $io->write("<info>{$message}</info>");
        });
    }
}