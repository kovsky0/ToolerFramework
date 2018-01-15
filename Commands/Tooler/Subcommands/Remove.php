<?php
namespace ToolerFramework\Commands\Tooler\Subcommands;

use ToolerFramework\Helpers\Input;
use ToolerFramework\Helpers\Output;
use ToolerFramework\Helpers\Prompt;
use ToolerFramework\Commands\Tooler\Helpers\CommandsFilesManager;
use Exception;

class Remove
{
    private $input;
    private $output;
    private $prompt;
    private $files_manager;

    public function __construct(Input $input, Output $output, Prompt $prompt)
    {
        $this->input = $input;
        $this->output = $output;
        $this->prompt = $prompt;
        $this->files_manager = new CommandsFilesManager;
    }

    public function execute()
    {
        if (!isset($this->input->arguments[1])) {
            throw new Exception("Please provide a command you wish to remove.");
        }

        $name = $this->input->arguments[1];

        if (!$this->files_manager->directoryExists($this->files_manager->main_dir . '/' . $name)) {
            throw new Exception("Provided command does not exist.");
        }
        //ask to confirm first
        $this->output->warning("After deletion the command cannot be restored.");
        $this->prompt->confirmation("Are you sure you want to delete the command? [y/n]");
        $is_confirmed = $this->prompt->getConfirmationResponse();

        if ($is_confirmed) {
                        
            $this->files_manager->removeMainFile($name);
            $this->files_manager->removeCommandDir($name);
        }
        
    }

}