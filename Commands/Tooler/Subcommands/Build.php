<?php
namespace ToolerFramework\Commands\Tooler\Subcommands;

use ToolerFramework\Helpers\Input;
use ToolerFramework\Helpers\Output;
use ToolerFramework\Commands\Tooler\Helpers\CommandsFilesManager;
use Exception;

/**
 * The class for the 'build' subcommand.
 */
class Build
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
     * The helper for operating on commands files.
     */
    private $files_manager;

    public function __construct(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->files_manager = new CommandsFilesManager;
    }

    public function execute()
    {
        if (!isset($this->input->arguments[1])) {
            throw new Exception("Please provide the name of the command you wish to build.");
        }

        $name = $this->input->arguments[1];
        
        $this->files_manager->makeCommandMainFile($name);
        $this->files_manager->makeCommandDir($name);
        $this->files_manager->makeCommandFile($name);

        $this->output->success("The command $name has been created");
    }
}