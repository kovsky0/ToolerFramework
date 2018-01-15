<?php
namespace ToolerFramework\Commands\Tooler\Subcommands;

use ToolerFramework\Helpers\Input;
use ToolerFramework\Helpers\Output;
use ToolerFramework\Helpers\Prompt;
use ToolerFramework\Commands\Tooler\Helpers\CommandsFilesManager;
use Exception;

/**
 * The class for 'rename' subcommand.
 */
class Rename
{
    /**
     * The helper for parsing the input.
     */
    private $input;
    /**
     * The helper for formatting the output.
     */
    private $output;
    /**
     * The helper for prompting.
     */
    private $prompt;
    /**
     * The helper for operating on commands files.
     */
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
        if (!isset($this->input->arguments[1]) || !isset($this->input->arguments[2])) {
            throw new Exception("Please point which command's name you want to change as well as its new name");
        }

        $from = $this->input->arguments[1];
        $to = $this->input->arguments[2];

        if (!$this->files_manager->directoryExists($this->files_manager->main_dir . '/' . $from)) {
            throw new Exception("Provided command does not exist");
        }        

        $this->prompt->confirmation("Are you sure you want to rename the command? [y/n]");
        $is_confirmed = $this->prompt->getConfirmationResponse();

        if ($is_confirmed) {
            $this->files_manager->updateMainFile($from, $to);
            $this->files_manager->updateCommandDir($from, $to);
        }
    }
}

?>