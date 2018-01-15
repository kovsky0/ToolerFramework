<?php
namespace ToolerFramework\Commands;

/**
 * Defines base class for all commands.
 */
abstract class Command
{
    /**
     * Executes the command.
     */
    abstract public function execute();
}