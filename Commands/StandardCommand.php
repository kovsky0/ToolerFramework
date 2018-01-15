<?php
namespace ToolerFramework\Commands;

use ToolerFramework\Commands\Command;
use ToolerFramework\Helpers\Input;
use ToolerFramework\Helpers\Output;
use ToolerFramework\Helpers\Prompt;

/**
 * Defines a base class with helpers loaded.
 */
abstract class StandardCommand extends Command
{
    /**
     * @var array Contains the original input to the terminal.
     */
    protected $argv;
    /**
     * @var The input parser helper.
     */
    protected $input;
    /**
     * @var The helper for displaying output.
     */
    protected $output;
    /**
     * @var The helper for displaying prompts and receiving user's input.
     */
    protected $prompt;

    /**
     *  @param array $argv The array with original input to the terminal.
     */
    public function __construct(Array $argv)
    {
        $this->argv = $argv;
        $this->input = new Input($argv);
        $this->output = new Output;
        $this->prompt = new Prompt;
    }
}