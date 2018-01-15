<?php
namespace ToolerFramework\Helpers;

/**
 * Defines the helper for parsing user's input.
 */
class Input
{
    /**
     * @var array The list of params that are yet to be assigned as command, flags, or arguments.
     */
    private $unparsedParams;
    /**
     * @var boolean Holds info about command being already found.
     */
    private $commandAlreadyParsed;
    /**
     * @var boolean Holds info about flags being already parsed.
     */
    private $flagsAlreadyParsed;
    /**
     * @var string The command found in given input.
     */
    public $command;
    /**
     * @var array The flags (-short or --long) found in given input.
     */
    public $flags;
    /**
     * @var array The arguments found in given input.
     */
    public $arguments;

    /**
     * @param array $argv The array with original input to the terminal.
     */
    public function __construct($argv)
    {
        $this->unparsedParams = $argv;
        $this->commandAlreadyParsed = false;
        $this->flagsAlreadyParsed = false;

        $this->command = $this->parseCommand();
        $this->flags = $this->parseFlags();
        $this->arguments = $this->parseArguments();
    }

    /**
     * Finds the command in the original input.
     *
     * Looks for the first argument of unparsed list,
     * after checking if it has not been already found.
     *
     * @return string|null $command The command used in the input.
     */
    protected function parseCommand()
    {
        //We make sure that command is assigned only once.
        if (!$this->commandAlreadyParsed) {
            $command = $this->unparsedParams[0];
            unset($this->unparsedParams[0]);
            $this->commandAlreadyParsed = true;
            return $command;
        }        
    }

    /**
     * Finds the flags in the original input.
     *
     * Looks for all unparsed params that start with - or --,
     * after checking if it has not been already done.
     *
     * @return array $flags The flags used in the input.
     */
    protected function parseFlags()
    {
        if (!$this->flagsAlreadyParsed) {
            $flags = array();

            foreach ($this->unparsedParams as $key => $param) {
                if (mb_strpos($param, '--') === 0) { //--longFlag
                    $flags[] = mb_substr($param, 2);
                    unset($this->unparsedParams[$key]);
                }
                elseif (mb_strpos($param, '-') === 0) { //-shortFlag
                    $flags[] = mb_substr($param, 1);
                    unset($this->unparsedParams[$key]);
                }
            }

            $this->flagsAlreadyParsed = true;
            return $flags;
        }
    }

    /**
     * Finds the arguments in the original input.
     *
     * Looks for everything that is not command or flags,
     * after checking that both flags and command has already
     * been looked for.
     *
     * @return array $arguments The arguments used in the input.
     */
    protected function parseArguments()
    {
        //we make sure that $this->unparsedParams does not contain
        //command name or flags
        if ($this->commandAlreadyParsed and $this->flagsAlreadyParsed) {
            $arguments = array_values($this->unparsedParams);
            $this->unparsedParams = array(); //clear unparsedParams array;
            return $arguments;
        }        
    }
}