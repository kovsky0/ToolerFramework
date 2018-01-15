<?php
namespace ToolerFramework\Commands\Tooler\Helpers;

use Exception;

/**
 * The files manager helper class for creating, removing, and renaming commands files.
 *
 * Most likely will be refactored and depricated in future versions. This type of 
 * "everything goes in here" helpers seem to be a rather bad design decision.
 */
class CommandsFilesManager
{
    /**
     * @var The location of the main dir for this console application.
     */
    public $main_dir;
    /**
     * @var The location of the main dir containing commands files.
     */
    public $commands_dir;
    /**
     * @var The location of the dir containing stubs. 
     */
    public $stubs_dir;

    public function __construct()
    {
        $this->main_dir = strstr(__DIR__, "/Commands", true);
        $this->commands_dir = strstr(__DIR__, "/Commands", true) . "/Commands";
        $this->stubs_dir = strstr(__DIR__, "/Tooler", true) . "/Tooler/Stubs";
    }

    /**
     * Verifies if either directory or file without any extension
     * already exists in a given location.
     *
     * @param string $dir The location to be verified.
     */
    public function directoryExists($dir)
    {
        return file_exists($dir) or is_dir($dir);
    }

    /**
     * Returns formmated command name.
     *
     * Formatted command name means that there are no spaces, dashes,
     * or underscores, and each word start with an uppercase.
     * THIS FUNCTION NEEDS A BETTER NAME.
     *
     * @param string $name The name of the command to be formatted.
     *
     * @return string Formmated command name.
     */
    public function formatCommandName($name)
    {
        if (strpos($name, '-')) {
            $words = explode('-', $name);
            $formatted_name = '';
            foreach ($words as $word)
            {
                $formatted_name .= ucfirst($word);
            } 
        } else {
            $formatted_name = ucfirst($name);
        }
        
        return $formatted_name;
    }

    /**
     * Returns formmated variable name (for the main command file).
     *
     * Formatted variable name means that each word is seperated by underscores
     * instead of dashes and all the letter ar lowercase.
     * THIS FUNCTION NEEDS A BETTER NAME.
     *
     * @param string $name The name of the command to be formatted.
     *
     * @return string Formmated variable for command name.
     */
    public function formatVariableName($name)
    {
        return str_replace('-', '_', strtolower($name));
    }

    /**
     * Returns the content of a stub.
     *
     * @param string $stub The stub to be returned.
     *
     * @return string Content of the stub.
     */
    public function getStub($stub)
    {
        return file_get_contents($this->stubs_dir . "/" . $stub . ".stub");
    }

    /**
     * Creates a new dir for a new command.
     *
     * @param string $name The name of the command for which the dir will be created.
     */
    public function makeCommandDir($name)
    {
        $formatted_name = $this->formatCommandName($name);
        $new_command_dir = $this->commands_dir . "/" . $formatted_name;

        if (!is_writable($this->commands_dir)) {
            throw new Exception("The commands directory is not writable");
        }
        
        if ($this->directoryExists($new_command_dir)) {
            throw new Exception("Directory for this command already exists " .
                                "(remove that directory or choose different name)");
        }

        mkdir($new_command_dir);
    }

    /**
     * Creates a file for a new command (inside the newly created command dir).
     *
     * @param string $name The name of the command for which the file should be created.
     * @param string $stub The name of the stub used as a source for the new file.
     */
    public function makeCommandFile($name, $stub = "StandardCommand")
    {
        $formatted_name = $this->formatCommandName($name);

        if (!is_dir($this->commands_dir . '/' . $formatted_name)) {
            throw new Exception("Connot create a command file - the command directory does not exist");
        }

        $path = $this->commands_dir . "/" . $formatted_name . "/" . $formatted_name . "Command.php";
        $this->makeFileFromStub($stub, $path, $name);
    }

    /**
     * Creates a main file (it has no extension) for a new command in the main dir.
     *
     * @param string $name The name of the command for which the file should be created.
     */
    public function makeCommandMainFile($name)
    {
        if (file_exists($name)) {
            throw new Exception("Cannot create a command file in main directory - the file already exists");
        }

        $path = $this->main_dir . '/' . $name;
        $this->makeFileFromStub('mainfile', $path, $name);
    }

    /**
     * Creates a new file based on the stub.
     *
     * @param string $stub Name of the stub which should be used to create the new file.
     * @param string $path The path of the file that should be created.
     * @param string $replacer The replacer for 'dummy' and 'Dummy' strings in the stub.
     */
    public function makeFileFromStub($stub, $path, $replacer)
    {
        $ucfirst_replacer = $this->formatCommandName($replacer);
        $variable_replacer = $this->formatVariableName($replacer);
        $stub_content = $this->getStub($stub);
        $new_file_content = str_replace('Dummy', $ucfirst_replacer, $stub_content);
        $new_file_content = str_replace('dummy', $variable_replacer, $new_file_content);

        file_put_contents($path, $new_file_content);
    }

    /**
     * Removes the dir of the command with given name.
     *
     * @param string $name The name of the command dir to be removed.
     */
    public function removeCommandDir($name)
    {
        if (!$name) {
            throw new Exception("Please provide a command name to be removed");
        }

        $ucfirst_name = $this->formatCommandName($name);
        $path = $this->commands_dir . '/' . $ucfirst_name;

        $this->removeDirRecursively($path);
    }

    /**
     * Removes all the dirs and files inside provided dir.
     *
     * @param string $dir The path to the dir that should be removed.
     */
    public function removeDirRecursively($dir)
    {
        $objects = array_diff(scandir($dir), array('.','..'));

        foreach ($objects as $object) {
            $object_path = $dir . '/' . $object;

            if (is_dir($object_path)) {
                $this->removeDirRecursively($object_path);
            }
            else {
                unlink($object_path);
            }
        }

        rmdir($dir);
    } 

    /**
     * Removes the main file of the command with given name.
     *
     * @param string $name The name of the command which main file should be romved.
     */
    public function removeMainFile($name)
    {
        if (!$name) {
            throw new Exception("Please provide a command name to be removed");
        }

        $path = $this->main_dir . '/' . $name;
        unlink($path);
    }

    /**
     * Updates the name and content of the dirs and files of given command.
     *
     * @param string $from The old name of the command.
     * @param string $to The new name of the command.
     */
    public function updateCommandDir($from, $to)
    {
        //rename the dir
        $old_command_dir = $this->commands_dir . "/" . $this->formatCommandName($from);
        $new_command_dir = $this->commands_dir . "/" . $this->formatCommandName($to);
        rename($old_command_dir, $new_command_dir);

        //update the command file
        $this->updateCommandFile($from, $to);
    }

    /**
     * Updates the content of given command's file.
     *
     * @param string $from The old name of the command.
     * @param string $to The new name of the command.
     */
    public function updateCommandFile($from, $to)
    {
        //rename command file first
        $old_command_file = $this->commands_dir . '/' . $this->formatCommandName($to) . '/' . $this->formatCommandName($from) . 'Command.php';
        $new_command_file = $this->commands_dir . "/" . $this->formatCommandName($to) . "/" . $this->formatCommandName($to) . 'Command.php';
        rename($old_command_file, $new_command_file);

        //update the content of renamed file
        $this->updateContent($from, $to, $new_command_file);
    }

    /**
     * Updates the content of the file using standard formatting.
     *
     * @param string $from The old name of the command based on which the file is updated.
     * @param string $to The new name of the command based on which the file is updated.
     * @param string $path The path of the file to be updated.
     */
    public function updateContent($from, $to, $path)
    {
        $ucfirst_from = $this->formatCommandName($from);
        $ucfirst_to = $this->formatCommandName($to);

        $variable_from = $this->formatVariableName($from);
        $variable_to = $this->formatVariableName($to);

        $old_content = file_get_contents($path);
        $new_content = str_replace($ucfirst_from, $ucfirst_to, $old_content);
        $new_content = str_replace($variable_from, $variable_to, $new_content);

        file_put_contents($path, $new_content);
    }

    /**
     * Updates the content of given command's main file.
     *
     * @param string $from The old name of the command.
     * @param string $to The new name of the command.
     */
    public function updateMainFile($from, $to)
    {
        //rename the file
        rename($this->main_dir . '/' . $from, $this->main_dir . '/' . $to);
        //update the content
        $this->updateContent($from, $to, $this->main_dir . '/' . $to);
    }


}