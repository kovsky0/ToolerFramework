<?php
namespace ToolerFramework\Commands\Tooler;

use ToolerFramework\Commands\StandardCommand;
use ToolerFramework\Commands\Tooler\Subcommands\Build;
use ToolerFramework\Commands\Tooler\Subcommands\Remove;
use ToolerFramework\Commands\Tooler\Subcommands\Rename;
use Exception;

class ToolerCommand extends StandardCommand
{
    public function execute()
    {
        try {
            if (!$this->input->arguments) {
                throw new Exception("Please provide at least one argument.");
            }

            $subcommand = $this->input->arguments[0];

            switch ($subcommand) {
                case 'build':
                    $build = new Build($this->input, $this->output);
                    $build->execute();
                    break;

                case 'remove':
                    $remove = new Remove($this->input, $this->output, $this->prompt);
                    $remove->execute();
                    break;

                case 'rename':
                    $rename = new Rename($this->input, $this->output, $this->prompt);
                    $rename->execute();
                    break;

                case 'help':
                    $this->output->title("Available commands:");
                    $this->output->writeln("build <command_name> Creates new command with a provided name.");
                    $this->output->writeln("remove <command_name> Removes given command (all files are permanently deleted).");
                    $this->output->writeln("rename <old_name> <new_name> Updates the name of provided command.");
                    $this->output->emptyLine();
                    break;

                default:
                    throw new Exception("Wrong argument. Please type 'php tooler help' to see available commands");
                    break;
            }
        }
        catch (Exception $e) {
            $this->output->warning($e->getMessage());
        }        
    }
}